<?php

// @formatter:off
// phpcs:ignoreFile

/**
 * Manuell created helper file for macros
 */

namespace Illuminate\Contracts\Routing {
    class ResponseFactory
    {
        /**
         * @see \App\Providers\AppServiceProvider::bootMacros()
         *
         * @param string $message
         * @param int $status
         * @param array $headers
         * @param int $options
         *
         * @return \Illuminate\Http\JsonResponse
         *
         * @static
         */
        public static function message($message, $status = 200, $headers = [], $options = 0)
        {
            return \Illuminate\Routing\ResponseFactory::message($message, $status, $headers, $options);
        }

        /**
         * @see \App\Providers\AppServiceProvider::bootMacros()
         *
         * @param string $message
         * @param int $status
         * @param array $headers
         * @param int $options
         *
         * @return \Illuminate\Http\JsonResponse
         *
         * @static
         */
        public static function error($message, $status = 500, $headers = [], $options = 192)
        {
            return \Illuminate\Routing\ResponseFactory::error($message, $status, $headers, $options);
        }
    }
}

