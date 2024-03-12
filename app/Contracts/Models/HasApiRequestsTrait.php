<?php

namespace App\Contracts\Models;

use App\Models\ApiRequest;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasApiRequestsTrait
{
    /**
     * Get the API requests for the Model.
     */
    public function apiRequests(): HasMany
    {
        return $this->hasMany(ApiRequest::class);
    }
}
