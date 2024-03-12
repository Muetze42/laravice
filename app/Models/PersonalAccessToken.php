<?php

namespace App\Models;

use App\Contracts\Models\HasApiRequestsTrait;
use Laravel\Sanctum\PersonalAccessToken as Model;

class PersonalAccessToken extends Model
{
    use HasApiRequestsTrait;
}
