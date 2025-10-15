<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use danog\MadelineProto\API;
use danog\MadelineProto\Settings;
use danog\MadelineProto\Settings\AppInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

$settings = (new Settings)
    ->setAppInfo(
        (new AppInfo)
            ->setApiId(28325545)
            ->setApiHash('7a08be7dafe6ac82d34beace5083836f')
    );

$MadelineProto = new API('session.madeline', $settings);
$MadelineProto->start();

try {
    $MadelineProto->channels->joinChannel(['channel' => '@solanawhaletracking']);
} catch (\Throwable $e) {
    echo "⚠️ Already joined or failed to join: " . $e->getMessage() . "\n";
}

$updates = $MadelineProto->messages->getHistory([
    'peer' => '@solanawhaletracking',
    'limit' => 20
]);

foreach ($updates['messages'] as $message) {
    $text = $message['message'] ?? '[No text]';
    echo "\n📨 Message: $text\n";

    $url = null;
    $txId = null;

    if (isset($message['entities'])) {
        foreach ($message['entities'] as $entity) {
            if (isset($entity['url']) && str_contains($entity['url'], 'solscan.io/tx/')) {
                $url = $entity['url'];
                $txId = basename(parse_url($url, PHP_URL_PATH));
                echo "🚀 Extracted TX: $txId\n";
            }
        }
    }

    if (!$txId || !$url) {
        echo "⚠️ No Solscan URL found — skipping\n";
        continue;
    }

    preg_match('/Amount:\s([\d,\.]+)\s(\w+)/', $text, $amountMatch);
    preg_match('/Value:\s\$(\d[\d,\.]+)/', $text, $valueMatch);
    preg_match('/From:\s(.+)/', $text, $fromMatch);
    preg_match('/To:\s(.+)/', $text, $toMatch);
    preg_match('/Time:\s(.+)/', $text, $timeMatch);

    $amount = isset($amountMatch[1]) ? str_replace(',', '', $amountMatch[1]) : null;
    $token = $amountMatch[2] ?? null;
    $valueUsd = isset($valueMatch[1]) ? str_replace(',', '', $valueMatch[1]) : null;
    $from = trim($fromMatch[1] ?? '');
    $to = trim($toMatch[1] ?? '');
    $timestamp = isset($timeMatch[1]) ? Carbon::parse($timeMatch[1], 'GMT')->setTimezone('UTC') : now();

    echo "🔍 Parsed: token=$token, amount=$amount, value=$valueUsd, from=$from, to=$to\n";

    if (!$amount || !$token || !$valueUsd) {
        echo "❌ Missing required fields — skipping insert for TX: $txId\n";
        continue;
    }

    try {
        DB::table('whale_activities')->updateOrInsert(
            ['tx_id' => $txId],
            [
                'token_name' => $token,
                'wallet' => $from,
                'to_wallet' => $to,
                'amount' => $amount,
                'value_usd' => $valueUsd,
                'solscan_url' => $url,
                'detected_at' => $timestamp,
            ]
        );
        echo "✅ Stored whale activity: $token | $amount | $from → $to | $valueUsd\n";
    } catch (\Throwable $e) {
        echo "💥 DB Error for TX $txId: " . $e->getMessage() . "\n";
    }
}
