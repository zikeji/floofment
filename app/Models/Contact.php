<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property string $phone
 * @property string $nickname
 * @property \Illuminate\Database\Eloquent\Collection<PhoneRecording> $phoneRecordings
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Contact extends Model
{
    use HasUlids;

    protected $fillable = ['phone', 'nickname'];

    public function phoneRecordings()
    {
        return $this->hasMany(PhoneRecording::class, 'from', 'phone');
    }
}
