<?php

namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\AbstractController;
use Dompdf\Dompdf;
use Illuminate\Http\Request;

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
        ]);

        return pdf()
            ->html($request->input('html'))
            ->name($request->input('filename', 'document.pdf'));
    }
}
