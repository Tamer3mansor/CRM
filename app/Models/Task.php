<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withDefault(
            [
                "name" => 'no name',
            ]
        );
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }
    public function scopeStatus($query, $status = 'completed')
    {
        return $query->where('status', $status);
    }
}
