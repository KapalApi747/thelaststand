<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * CsvExportService genereert dynamisch CSV-bestanden voor download.
 *
 * - De methode `export()` accepteert kolomkoppen, een datacollectie en een closure die elk item omzet naar een rij.
 * - Het CSV-bestand wordt direct naar de browser gestreamd als download (via StreamedResponse).
 *
 * Gebruikte functies:
 * - `stream()`: stuurt data real-time naar de client zonder tussenopslag in geheugen.
 * - `fopen('php://output', 'w')`: opent de output-buffer als bestand om rechtstreeks te schrijven naar de response.
 * - `chr(0xEF) . chr(0xBB) . chr(0xBF)`: voegt een UTF-8 BOM toe voor correcte weergave in Excel.
 * - `fputcsv()`: schrijft een array als CSV-regel naar het bestand/stream.
 * - `fclose()`: sluit de output-buffer veilig af.
 */

class CsvExportService
{
    public function export(array $headers, Collection $data, \Closure $rowMapper, string $filename = 'export.csv'): StreamedResponse
    {
        $responseHeaders = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return response()->stream(function () use ($headers, $data, $rowMapper) {
            $handle = fopen('php://output', 'w'); // Open direct outputstream

            echo chr(0xEF) . chr(0xBB) . chr(0xBF); // UTF-8 BOM voor Excel compatibiliteit

            fputcsv($handle, $headers); // Schrijf kolomkoppen

            foreach ($data as $item) {
                fputcsv($handle, $rowMapper($item)); // Map elk item naar CSV-rij
            }

            fclose($handle); // Sluit de stream netjes af
        }, 200, $responseHeaders);
    }
}
