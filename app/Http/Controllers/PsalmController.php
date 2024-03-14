<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PsalmController extends AbstractController
{
    protected string $contents = "/**\n * @return array {";

    public function jsonToArrayComment(Request $request)
    {
        $request->validate([
            'json' => 'required|json',
        ]);

        $data = json_decode($request->input('json'), true);

        $this->arrayItem($data);

        return str_replace("\t", '    ', $this->contents) . "\n * }\n */";
    }

    protected function arrayItem(array $data, $level = 1)
    {
        foreach ($data as $key => $value) {
            $this->contents .= "\n * " . str_repeat("\t", $level);
            $this->contents .= $key . ': ' . gettype($value);
            if ((is_array($value) || is_object($value)) && !empty($value)) {
                $this->contents .= ' {';
                $this->arrayItem($value, $level + 1);
                $this->contents .= "\n * " . str_repeat("\t", $level) . '}';
            }
            $this->contents .= ',';
        }
    }
}
