<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\InteractsWithMedia;

class Task extends Model
{
    use HasFactory, InteractsWithMedia, HasApiTokens;

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
    public function filterScope($query, $filters)
    {
        $query->when(!empty($filters['status']), function ($query) use ($filters) {
            $query->where('status', $filters['status']);
        });
        when(!empty($filters['name']), function ($query) use ($filters) {
            $query->where('name', 'like', "%" . $filters['name'] . "%");
        });
        return $query;

    }
}
