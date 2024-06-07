<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'batch_id',
        'date',
    ];

    public function batch(){
        return $this->belongsTo(Batch::class);
    }
}
