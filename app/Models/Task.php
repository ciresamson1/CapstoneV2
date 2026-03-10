<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'assigned_to',
        'status',
        'start_date',
        'due_date'
    ];

    /**
     * Task belongs to a project
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Assigned user
     */
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}