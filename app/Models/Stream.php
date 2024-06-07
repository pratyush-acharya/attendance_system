<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stream extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable=[
        'name',
    ];
    
    /**
     * Defines one-to-many relationship between batch and stream
     *
     * @return void
     */
    public function batches(){
        return $this->hasMany(Batch::class,'stream_id','id');
    }
    
    /**
     * Defines one-to-many relationship between stream and subject
     *
     * @return void
     */
    public function subjects(){
        return $this->hasMany(Subject::class);
    }
}
