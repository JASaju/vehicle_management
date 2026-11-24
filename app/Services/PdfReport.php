<?php

namespace App\Services;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;

/**
 * Renders a Blade view to a PDF with mPDF and returns it as an HTTP response.
 * Views must use plain CSS (tables, borders, colours): mPDF does not support flexbox, grid or Tailwind.
 */
class PdfReport
{
    public static function make(
        string $view,
        array $data,
        string $filename,
        bool $landscape = false,
        bool $download = false,
    ): Response {
        $tempDir = storage_path('app/mpdf');
        File::ensureDirectoryExists($tempDir);

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => $landscape ? 'A4-L' : 'A4',
            'default_font' => 'dejavusans',
            'margin_top' => 14,
            'margin_bottom' => 16,
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_footer' => 6,
            'tempDir' => $tempDir,
        ]);

        $mpdf->SetTitle($data['title'] ?? 'Report');
        $mpdf->SetAuthor(config('app.name'));

        $mpdf->SetHTMLFooter(
            '<table width="100%" style="font-size:8pt;color:#666666;border-top:0.5pt solid #cccccc;">'
            . '<tr><td>' . e(config('app.name')) . ' · generated ' . now()->format('d M Y H:i') . '</td>'
            . '<td align="right">Page {PAGENO} of {nbpg}</td></tr></table>'
        );

        $mpdf->WriteHTML(view($view, $data)->render());

        return response($mpdf->Output($filename, Destination::STRING_RETURN), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => ($download ? 'attachment' : 'inline') . '; filename="' . $filename . '"',
        ]);
    }
}
