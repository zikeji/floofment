<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Models\PhoneRecording;
use App\PhoneRecordingStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Twilio\TwiML\VoiceResponse;

class TwilioInboundController extends Controller
{
    public function receive(Request $request)
    {
        Log::debug('inbound-initiated', $request->all());

        PhoneRecording::create([
            'sid' => $request->get('CallSid'),
            'from' => $request->get('From'),
            'called' => $request->get('Called'),
        ]);

        /** @var string $greeting */
        $greeting = config('twilio.greeting');

        $response = new VoiceResponse;
        $response->play(url($greeting));
        $response->record([
            'action' => 'record',
            'method' => 'POST',
            'timeout' => 5,
            'maxLength' => 5 * 60,
            'playBeep' => false,
            'trim' => 'trim-silence',
            'recordingStatusCallback' => 'record/status',
        ]);

        return $response;
    }

    public function status(Request $request)
    {
        Log::debug('status-update', $request->all());
        if (collect(['completed', 'failed'])->contains($request->get('CallStatus'))) {
            $recording = PhoneRecording::findOrFail($request->get('CallSid'));
            if ($recording->status === PhoneRecordingStatus::Started) {
                Log::debug('deleted recording for non-recorded call');
                $recording->delete();
            }
        }
    }
}
