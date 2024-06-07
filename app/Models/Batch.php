<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable=[
        'name',
        'stream_id',
        'semester',
        'start_date',
        'end_date'
    ];
    
    /**
     * Defines many-to-one relationship between stream and batch
     *
     * @return void
     */
    public function stream(){
        return $this->belongsTo(Stream::class);
    }

        
    /**
     * Defines many-to-one relationship between students and batch
     *
     * @return void
     */
    public function students(){
        return $this->hasMany(Student::class,'batch_id','id');
    }


            
    /**
     * Defines many-to-one relationship between groups and batch
     *
     * @return void
     */
    public function groups(){
        return $this->hasMany(Group::class);
    }
}
