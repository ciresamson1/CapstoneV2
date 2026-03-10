<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'status',
        'manager_id',
        'created_by'
    ];

    /**
     * Project Manager
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Creator of the project
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Project tasks
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

        /**
     * Calculate project progress percentage
     */
    public function progress()
    {
        $totalTasks = $this->tasks()->count();

        if ($totalTasks === 0) {
            return 0;
        }

        $completedTasks = $this->tasks()
            ->where('status', 'completed')
            ->count();

        return round(($completedTasks / $totalTasks) * 100);
    }


}