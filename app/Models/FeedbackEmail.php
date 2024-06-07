<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackEmail extends Model
{
    use HasFactory;

    protected $fillable = [
        'email_to',
        'email_cc',
    ];
     
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
