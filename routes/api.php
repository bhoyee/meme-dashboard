<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

Route::get('/whale-activities', function () {
    return DB::table('whale_activities')
        ->orderByDesc('detected_at')
        ->get();
});

Route::post('/sync-telegram', function (Request $request) {
    $request->validate([
        'type' => 'required|string',
        'from' => 'required|string',
        'to' => 'nullable|string',
        'amount' => 'required|numeric',
        'value' => 'required|numeric',
        'timestamp' => 'required|date',
        'solscan_url' => 'required|url'
    ]);

    DB::table('whale_activities')->insert([
        'type' => $request->type,
        'wallet' => $request->from,
        'to_wallet' => $request->to,
        'amount' => $request->amount,
        'value_usd' => $request->value,
        'detected_at' => $request->timestamp,
        'solscan_url' => $request->solscan_url
    ]);

    return response()->json(['status' => 'ok']);
});
