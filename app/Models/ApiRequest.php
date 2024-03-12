<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiRequest extends Model
{
    use HasFactory;

    /**
     * The name of the "updated at" column.
     *
     * @var string|null
     */
    public const UPDATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'path',
        'method',
        'user_agent',
        'ip',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'ip',
    ];

    /**
     * Get the user that owns the API request.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the personal access token for the API request.
     */
    public function token(): BelongsTo
    {
        return $this->belongsTo(PersonalAccessToken::class);
    }
}
