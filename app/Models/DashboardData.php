<?php

// app/Models/DashboardData.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DashboardData extends Model
{
    public function getUsers()
    {
        return User::all();
    }
}
