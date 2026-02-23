<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    // 📄 Sertifikat (File PDF statis)
    public function sertifikat()
    {
        $path = public_path('files/sertifikat.pdf');

        return response()->download($path, 'sertifikat.pdf');
    }

    // 📄 Undangan (Generate dari Blade)
    public function undangan()
    {
        $data = [
            'nama' => 'Bitter Tea',
            'tanggal' => '25 Februari 2026'
        ];

        $pdf = Pdf::loadView('pdf.undangan', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->download('undangan.pdf');
    }
}