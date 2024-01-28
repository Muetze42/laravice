<?php

namespace App\Services\Images\RemoveBackground;

use App\Services\AbstractService;
use Illuminate\Support\Facades\Process;

class ImglyBackgroundRemovalNodeService extends AbstractService
{
    /**
     * The standard output of the process.
     */
    protected string $output;

    /**
     * Determine the required packages for this service.
     */
    public static function requiredPackages(): array|string
    {
        return ['@imgly/background-removal-node'];
    }

    public function __construct(string $relativePath)
    {
        $result = Process::path(base_path('packages/imgly-background-removal-node'))
            ->run([config('process.commands.node'), 'script.js', $relativePath]);

        $this->output = $result->successful() ? trim($result->output()) : $result->errorOutput();
    }

    /**
     * Determine if the request was successful.
     */
    public function successful(): bool
    {
        return str_contains($this->output, 'Image saved to ');
    }

    /**
     * Determine if the request was not successful.
     */
    public function failed(): bool
    {
        return !$this->successful();
    }

    /**
     * Get the relative path to generated file.
     */
    public function targetPath(): string
    {
        return last(explode(' ', $this->output));
    }

    /**
     * Get the standard output of the process.
     */
    public function output(): string
    {
        return $this->output;
    }
}
