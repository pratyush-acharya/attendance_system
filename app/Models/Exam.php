<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id',
        'type',
        'start_date',
        'end_date',
    ];

    public function batch(){
        return $this->belongsTo(Batch::class);
    }

}
