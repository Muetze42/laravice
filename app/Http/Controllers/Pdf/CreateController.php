<?php

namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\AbstractController;
use Illuminate\Http\Request;
use Dompdf\Dompdf;

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
}
