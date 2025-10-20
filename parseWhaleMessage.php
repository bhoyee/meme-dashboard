<?php

use Illuminate\Support\Carbon;

function parseWhaleMessage($text) {
    preg_match('/Amount:\s([\d,\.]+)\s(\w+)/', $text, $amountMatch);
    preg_match('/Value:\s\$(\d[\d,\.]+)/', $text, $valueMatch);
    preg_match('/From:\s(.+)/', $text, $fromMatch);
    preg_match('/To:\s(.+)/', $text, $toMatch);
    preg_match('/Time:\s(.+)/', $text, $timeMatch);

    return [
        'amount' => isset($amountMatch[1]) ? str_replace(',', '', $amountMatch[1]) : null,
        'token' => $amountMatch[2] ?? null,
        'value_usd' => isset($valueMatch[1]) ? str_replace(',', '', $valueMatch[1]) : null,
        'from' => trim($fromMatch[1] ?? ''),
        'to' => trim($toMatch[1] ?? ''),
        'timestamp' => isset($timeMatch[1]) ? Carbon::parse($timeMatch[1], 'GMT')->setTimezone('UTC') : now()
    ];
}
