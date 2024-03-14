<?php

namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\AbstractController;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Spatie\LaravelPdf\Enums\Format;
use Illuminate\Validation\Rule;

use function Spatie\LaravelPdf\Support\pdf;

class CreateController extends AbstractController
{
    public function domPdf(Request $request)
    {
        $request->validate([
            'html' => 'required|string',
            'filename' => 'nullable|string',
        ]);

        // Todo: Options for dompdf
        $dompdf = new Dompdf();
        $dompdf->loadHtml($request->input('html'));
        $dompdf->render();

        $dompdf->stream(
            $request->input('filename', 'document.pdf')
        );
    }

    public function spatieLaravelPdf(Request $request)
    {
        $request->validate([
            'html' => 'required|string',
            'filename' => 'nullable|string',
            'header_html' => 'nullable|string',
            'footer_html' => 'nullable|string',
            'landscape' => 'nullable|boolean',
            'format' => ['nullable', Rule::enum(Format::class)],
        ]);

        $pdf = pdf()
            ->html($request->input('html'));

        if ($request->input('header_html')) {
            $pdf->headerHtml($request->input('header_html'));
        }
        if ($request->input('footer_html')) {
            $pdf->footerHtml($request->input('footer_html'));
        }
        if ($request->boolean('landscape')) {
            $pdf->landscape();
        }
        if ($request->input('format')) {
            $pdf->format($request->input('format'));
        }

        // Todo: https://spatie.be/docs/laravel-pdf/v1/basic-usage/formatting-pdfs#content-paper-size
        // Todo: https://spatie.be/docs/laravel-pdf/v1/basic-usage/formatting-pdfs#content-page-margins
        // Todo: https://spatie.be/docs/laravel-pdf/v1/basic-usage/formatting-pdfs#content-background-color

        $pdf->name($request->input('filename', 'document.pdf'));
    }
}
