<?php

namespace App;

enum PhoneRecordingStatus: string
{
    case Started = 'started';
    case Recorded = 'recorded';
    case Available = 'available';
}

