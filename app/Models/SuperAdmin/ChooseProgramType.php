<?php

namespace App\Models\SuperAdmin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChooseProgramType extends Model
{
    use HasFactory;

    protected $primaryKey = 'unique_id';
}