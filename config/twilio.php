<?php

return [
    'sid' => env('TWILIO_SID'),
    'token' => env('TWILIO_TOKEN'),
    'greeting' => env('TWILIO_GREETING', 'audio/greeting.wav'),
];
