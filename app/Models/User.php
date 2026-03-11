<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Each user belongs to one role
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

        /**
     * Projects managed by this user
     */
    public function managedProjects()
    {
        return $this->hasMany(Project::class, 'manager_id');
    }

    public function tasks()
    {
        return $this->hasMany(\App\Models\Task::class, 'assigned_to');
    }

}
