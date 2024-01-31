<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

if (!function_exists('extByMime')) {
    /**
     * Get file extension from (MIME) file string.
     */
    function extByMime(string $mime): string
    {
        $mime = last(explode('/', $mime));

        return $mime == 'jpeg' ? 'jpg' : $mime;
    }
}

if (!function_exists('filename')) {
    /**
     * Get unique filename for an uploaded file.
     */
    function filename(UploadedFile $file): string
    {
        return Str::uuid()->toString() . '.' . $file->extension();
    }
}
