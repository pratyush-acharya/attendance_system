<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The roles that belong to the user.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
        
    /**
     * Check if user has a given role
     *
     * @param  mixed $role
     * @return void
     */
    public function hasRole($role)
    {

        if ($this->roles()->where('roles', $role)->first()) {
            return true;
        }

        return false;
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }
    
    /**
     * Defines many-to-many relationship between group-subject and teachers
     *
     * @return void
     */
    public function groupSubjects()
    {
        return $this->belongsToMany(GroupSubject::class, 'group_subject_teacher','user_id','group_subject_id')
                    ->withPivot('id','max_class_per_day','days')
                    ->withTrashed();
    }



    
}
