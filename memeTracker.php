#!/usr/bin/env php
<?php
file_put_contents(__DIR__ . '/meme_listener.log', "[".date('Y-m-d H:i:s')."] Meme Tracker triggered\n", FILE_APPEND);

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

$MadelineProto = new API('meme_session.madeline', $settings);
$MadelineProto->start();

$channels = [
    '@mad_apes_call' => 'MadApes Calls',
    '@mad_apes_gambles' => 'MadApes Gambles',
    '@kobesgambles' => 'Kobe’s Gambles'
];

function convertToNumber($value, $unit) {
    $multiplier = match(strtoupper($unit)) {
        'M' => 1_000_000,
        'K' => 1_000,
        default => 1
    };
    return floatval($value) * $multiplier;
}

foreach ($channels as $handle => $sourceName) {
    try {
        $MadelineProto->channels->joinChannel(['channel' => $handle]);
    } catch (\Throwable $e) {
        echo "⚠️ Already joined or failed to join: " . $e->getMessage() . "\n";
    }

    $updates = $MadelineProto->messages->getHistory([
        'peer' => $handle,
        'limit' => 50
    ]);

    foreach ($updates['messages'] as $msg) {
        $text = $msg['message'];

        preg_match_all('/\$(\w+)/', $text, $tickers);
        preg_match('/(\d+(\.\d+)?)x/', $text, $multiplier);
        preg_match('/(\d+(\.\d+)?)(M|K)/', $text, $volume);
        preg_match('/🔥?(SOL|BSC|TRX|ETH|BASE)/i', $text, $chain);
        preg_match('/0x[a-fA-F0-9]{40}/', $text, $contract);
        preg_match('/now (\d+(\.\d+)?)(M|K)/i', $text, $capMatch);

        $marketCap = isset($capMatch[1]) ? convertToNumber($capMatch[1], $capMatch[3]) : null;

        if (!empty($tickers[1])) {
            DB::table('meme_signals')->updateOrInsert(
                ['token' => $tickers[1][0], 'detected_at' => Carbon::now()],
                [
                    'multiplier' => $multiplier[1] ?? null,
                    'volume' => $volume[1] ?? null,
                    'chain' => $chain[1] ?? null,
                    'contract' => $contract[0] ?? null,
                    'market_cap_usd' => $marketCap,
                    'price_usd' => null, // placeholder for future DEX Screener integration
                    'source' => $sourceName,
                    'raw_message' => $text,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );

            file_put_contents(
                __DIR__ . '/meme_listener.log',
                "✅ [$sourceName] Stored: " . ($tickers[1][0] ?? '-') .
                " | " . ($multiplier[1] ?? '-') . "x" .
                " | " . ($volume[1] ?? '-') .
                " | " . ($chain[1] ?? '-') .
                " | Cap: " . ($marketCap ?? '-') . "\n",
                FILE_APPEND
            );
        }
    }
}
