<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasUlids;

    protected $fillable = ['phone', 'nickname'];

    public function phoneRecordings()
    {
        return $this->hasMany(PhoneRecording::class, 'from', 'phone');
    }
}
