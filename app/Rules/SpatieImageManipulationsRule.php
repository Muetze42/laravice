<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Spatie\Image\Enums\Fit;
use Spatie\Image\Enums\CropPosition;
use Spatie\Image\Enums\Orientation;
use Spatie\Image\Enums\FlipDirection;

use function Symfony\Component\String\b;

class SpatieImageManipulationsRule implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_array($value)) {
            $fail('validation.array')->translate();
        }

        foreach ($value as $method => $item) {
            switch ($method) {
                case 'width':
                case 'height':
                    if (!$this->isIntegerString($item)) {
                        $fail(__('validation.integer', ['attribute' => 'action.' . $method]));
                    }
                    break;
                case 'fit':
                    $parts = explode(',', $item);
                    $cases = array_map(fn(Fit $case) => $case->value, Fit::cases());
                    if (
                        count($parts) != 3 || !in_array($parts[0], $cases) ||
                        !$this->isIntegerString($parts[1]) || !$this->isIntegerString($parts[2])
                    ) {
                        $fail(
                            __('validation.custom.format', [
                                'attribute' => 'action.' . $method,
                                'format' => '<' . implode('|', $cases) . ',int:width,int:height>',
                            ])
                        );
                    }
                    break;
                case 'crop':
                    $parts = explode(',', $item);
                    $cases = array_map(fn(CropPosition $case) => $case->value, CropPosition::cases());
                    if (
                        count($parts) != 3 || !$this->isIntegerString($parts[0]) || !$this->isIntegerString($parts[1])
                        || !in_array($parts[0], $cases)
                    ) {
                        $fail(
                            __('validation.custom.format', [
                                'attribute' => 'action.' . $method,
                                'format' => '<' . implode('|', $cases) . ',int:width,int:height>',
                            ])
                        );
                    }
                    break;
                case 'manualCrop':
                    $parts = explode(',', $item);
                    if (
                        count($parts) != 4 || !$this->isIntegerString($parts[0]) || !$this->isIntegerString($parts[1])
                        || !$this->isIntegerString($parts[2]) || !$this->isIntegerString($parts[3])
                    ) {
                        $fail(
                            __('validation.custom.format', [
                                'attribute' => 'action.' . $method,
                                'format' => '<int:width,int:height,int:x,int:y>',
                            ])
                        );
                    }
                    break;
                case 'brightness':
                case 'contrast':
                    if (!$this->isIntegerString($item) || (int) $item > 100 || (int) $item < -100) {
                        $fail(
                            __('validation.digits_between', [
                                'attribute' => 'action.' . $method,
                                'min' => -100,
                                'max' => 100,
                            ])
                        );
                    }
                    break;
                case 'gamma':
                    if (!is_numeric($item) || (float) $item > 9.99 || (float) $item < 0.1) {
                        $fail(__('validation.between.numeric', [
                            'attribute' => 'action.' . $method,
                            'min' => 0.1,
                            'max' => 9.99,
                        ]));
                    }
                    break;
                case 'colorize':
                    $parts = explode(',', $item);
                    if (
                        count($parts) != 3 || !$this->isIntBetween($parts[0]) || !$this->isIntBetween($parts[1])
                        || !$this->isIntBetween($parts[2])
                    ) {
                        $fail(__('validation.custom.format', [
                            'attribute' => 'action.' . $method,
                            'format' => '<int:red,int:green,int:blue>',
                        ]));
                    }
                    break;
                case 'orientation':
                    $cases = array_map(fn(Orientation $case) => $case->degrees(), Orientation::cases());
                    if (!in_array($item, $cases)) {
                        $fail(__('validation.in_array', [
                            'attribute' => 'action.' . $method,
                            'other' => implode('|', $cases),
                        ]));
                    }
                    break;
                case 'flip':
                    $cases = array_map(fn(FlipDirection $case) => $case->value, FlipDirection::cases());
                    if (!in_array($item, $cases)) {
                        $fail(__('validation.in_array', [
                            'attribute' => 'action.' . $method,
                            'other' => implode('|', $cases),
                        ]));
                    }
                    break;
                case 'blur':
                case 'pixelate':
                case 'greyscale':
                case 'sharpen':
                    if (!$this->isIntegerString($item) || (int) $item > 100 || (int) $item < 0) {
                        $fail(
                            __('validation.digits_between', [
                            'attribute' => 'action.' . $method,
                            'min' => 0,
                            'max' => 100,
                            ])
                        );
                    }
                    break;
                case 'sepia':
                    // Silent
                    break;
                default:
                    $fail(__('validation.custom.action_not_supported', ['attribute' => $method]));
            }
        }
    }

    protected function isIntBetween(string $value, int $min = -100, int $max = 100): bool
    {
        // is_numeric not good working
        if (!is_numeric($value) || str_contains($value, '.')) {
            return false;
        }

        return (int) $value <= $max && (int) $value >= $min;
    }

    protected function isIntegerString(string $value): bool
    {
        return is_numeric($value) || !str_contains($value, '.');
    }
}
