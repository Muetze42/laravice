<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use ValidatesRequests;

    /**
     * Return a new file response from the application.
     */
    public function fileResponse(string $path, Request $request = null)
    {
        if (!$request) {
            $request = app(Request::class);
        }

        if ($this->isDownloadRequest($request)) {
            return response()->download($path);
        }

        return response()->file($path);
    }

    /**
     * Determine if the current request is a download request.
     */
    protected function isDownloadRequest(Request $request): bool
    {
        if ($request->boolean('download')) {
            return true;
        }

        if ($request->boolean('stream')) {
            return false;
        }

        return (bool) config('laravice.download-as-default', false);
    }
}
