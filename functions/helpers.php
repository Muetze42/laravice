<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

if (!function_exists('ext_by_mime')) {
    /**
     * Get file extension from (MIME) file string.
     */
    function ext_by_mime(string $mime): string
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

if (!function_exists('minimize_abilities')) {
    /**
     * Minimize the requested abilities.
     */
    function minimize_abilities(?array $abilities): array
    {
        $abilities = array_unique(array_values((array) $abilities));

        if (empty($abilities) || in_array('*', $abilities)) {
            return ['*'];
        }

        if (count($abilities) > 1) {
            foreach ($abilities as $ability) {
                if (str_ends_with($ability, ':*')) {
                    $abilities = array_filter(
                        $abilities,
                        fn (string $value) => !str_starts_with($value, substr($ability, 0, -1))
                        || $ability == $value
                    );
                }
            }
        }

        $abilities = array_values(array_unique($abilities));
        sort($abilities);

        return $abilities;
    }
}
