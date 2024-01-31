<?php

namespace App\Services\Images\RemoveBackground;

use App\Services\AbstractService;
use App\Support\Facades\NodeProcess;

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

    public function __construct(string $source, string $targetPath, string $format, float $quality)
    {
        $result = NodeProcess::path(base_path('packages/imgly-background-removal-node'))
            ->run(['script.js', $source, $targetPath, $format, $quality]);

        $this->output = trim($result->successful() ? $result->output() : $result->errorOutput());
    }

    /**
     * Determine if the request was successful.
     */
    public function successful(): bool
    {
        return $this->output == 'process was successful';
    }

    /**
     * Determine if the request was not successful.
     */
    public function failed(): bool
    {
        return !$this->successful();
    }

    /**
     * Get the standard output of the process.
     */
    public function output(): string
    {
        return $this->output;
    }
}
