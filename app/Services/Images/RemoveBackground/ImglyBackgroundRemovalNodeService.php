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

    public function __construct(string $relativePath, string $format)
    {
        $result = Process::path(base_path('packages/imgly-background-removal-node'))
            ->run([config('process.commands.node'), 'script.js', $relativePath, $format]);

        $this->output = $result->successful() ? trim($result->output()) : $result->errorOutput();
    }

    /**
     * Determine if the request was successful.
     */
    public function successful(): bool
    {
        return str_starts_with($this->lastOutput(), 'Image saved to ');
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
        return last(explode(' ', $this->lastOutput()));
    }

    /**
     * Get the standard output of the process.
     */
    public function output(): string
    {
        return $this->output;
    }

    /**
     * Get the last line of the standard output of the process.
     */
    protected function lastOutput(): string
    {
        return last(explode("\n", trim($this->output)));
    }
}
