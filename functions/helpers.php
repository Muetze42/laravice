<?php

if (!function_exists('extByMime')) {
    function extByMime(string $mime): string
    {
        $mime = last(explode('/', $mime));

        return $mime == 'jpeg' ? 'jpg' : $mime;
    }
}
