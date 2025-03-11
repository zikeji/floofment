<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Models\PhoneRecording;
use App\PhoneRecordingStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Twilio\Rest\Client as TwilioClient;
use Twilio\TwiML\VoiceResponse;

class TwilioRecordController extends Controller
{
    public function handle(Request $request) {
        Log::debug('recording-started', $request->all());

        $recording = PhoneRecording::findOrFail($request->get('CallSid'));
        $recording->status = PhoneRecordingStatus::Recorded;
        $recording->save();

        $response = new VoiceResponse();
        $response->hangup();
        return $response;
    }

    public function status(Request $request) {
        Log::debug('recording-status', $request->all());

        $recording = PhoneRecording::findOrFail($request->get('CallSid'));

        $response = Http::withOptions(['stream' => true])->get($request->get('RecordingUrl') . '.mp3')->getBody();

        Storage::disk('s3')->put(
            "phone_recordings/{$recording->sid}.mp3",
            $response
        );

        $recording->status = PhoneRecordingStatus::Available;
        $recording->save();

        $client = new TwilioClient(config('twilio.sid'), config('twilio.token'));
        $client->recordings->getContext($request->get('RecordingSid'))->delete();
    }
}
