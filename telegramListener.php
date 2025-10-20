#!/usr/bin/env php
<?php
file_put_contents(__DIR__ . '/listener.log', "[".date('Y-m-d H:i:s')."] Listener triggered\n", FILE_APPEND);

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
require_once __DIR__ . '/parseWhaleMessage.php';



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

    $parsed = parseWhaleMessage($text);
$amount = $parsed['amount'];
$token = $parsed['token'];
$valueUsd = $parsed['value_usd'];
$from = $parsed['from'];
$to = $parsed['to'];
$timestamp = $parsed['timestamp'];


    echo "🔍 Parsed: token=$token, amount=$amount, value=$valueUsd, from=$from, to=$to\n";

    if (!$amount || !$token || !$valueUsd) {
        echo "❌ Missing required fields — skipping insert for TX: $txId\n";
        continue;
    }

try {
    // Example: insert into DB
    if ($amount && $token && $valueUsd) {
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

        file_put_contents(__DIR__ . '/listener.log', "✅ Stored: $token | $amount | $from → $to | $valueUsd\n", FILE_APPEND);
    }
} catch (\Throwable $e) {
    file_put_contents(__DIR__ . '/error.log', "[".date('Y-m-d H:i:s')."] DB Error: " . $e->getMessage() . "\n", FILE_APPEND);
}
}
