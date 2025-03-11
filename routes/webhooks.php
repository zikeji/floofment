<?php

use App\Http\Controllers\Webhooks\TwilioInboundController;
use App\Http\Controllers\Webhooks\TwilioRecordController;
use Illuminate\Support\Facades\Route;

Route::post('webhook/twilio/inbound', [TwilioInboundController::class, 'receive']);
Route::post('webhook/twilio/inbound/status', [TwilioInboundController::class, 'status']);
Route::post('webhook/twilio/record', [TwilioRecordController::class, 'handle']);
Route::post('webhook/twilio/record/status', [TwilioRecordController::class, 'status']);
