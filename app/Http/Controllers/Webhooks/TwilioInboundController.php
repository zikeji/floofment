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
    public function receive(Request $request) {
        Log::debug('inbound-initiated', $request->all());

        $recording = new PhoneRecording;
        $recording->sid = $request->get('CallSid');
        $recording->from = $request->get('From');
        $recording->save();

        $response = new VoiceResponse();
        $response->say('Please leave a message', ['voice' => 'alice']);
        $response->record([
            'action' => 'record',
            'method' => 'POST',
            'timeout' => 5,
            'maxLength' => 5 * 60,
            'playBeep' => true,
            'trim' => 'trim-silence',
            'recordingStatusCallback' => 'record/status',
        ]);

        return $response;
    }

    public function status(Request $request) {
        Log::debug('status-update', $request->all());
        if (collect(['completed', 'failed'])->contains($request->get('status'))) {
            $recording = PhoneRecording::findOrFail('sid', $request->get('CallSid'));
            if ($recording->status === PhoneRecordingStatus::Started) {
                Log::debug('deleted recording for non-recorded call');
                $recording->delete();
            }
        }
    }
}
