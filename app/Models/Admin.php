<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles; // استيراد الترايت

class Admin extends Model
{
    use HasRoles, HasFactory;
    protected $guard_nae = 'admin';
}
