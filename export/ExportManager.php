<?php
/**
 * Export Manager
 * Mengelola semua proses export dengan struktur folder yang terorganisir
 */

class ExportManager {
    private $base_path;
    private $download_path;
    
    public function __construct() {
        $this->base_path = __DIR__ . '/../downloads';
        $this->ensureDownloadStructure();
    }
    
    /**
     * Pastikan struktur folder downloads exist
     * Structure: downloads/YYYY/MM/
     */
    private function ensureDownloadStructure() {
        $year = date('Y');
        $month = date('m');
        $this->download_path = $this->base_path . '/' . $year . '/' . $month;
        
        if (!is_dir($this->download_path)) {
            mkdir($this->download_path, 0755, true);
        }
    }
    
    /**
     * Generate Excel format (HTML table yang dibuka Excel)
     */
    public function exportToExcel($filename, $title, $data, $headers) {
        $file_path = $this->download_path . '/' . $filename;

        $colCount = max(1, count($headers));

        $css = '
            body { font-family: Calibri, Arial, sans-serif; font-size: 11pt; color: #111827; }
            table.report { border-collapse: collapse; width: 100%; }
            table.report th, table.report td { border: 1px solid #D1D5DB; padding: 8px; vertical-align: top; }
            tr.header-row th { background: #1e40af; color: #ffffff; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
            tr.meta-row td { border: none; padding: 2px 0; }
            tr.meta-gap td { border: none; height: 10px; }
            tr.total-row td { background: #D9E1F2; font-weight: 700; border-top: 2px solid #1e40af; }
            td.right { text-align: right; }
            td.center { text-align: center; }
            .title { font-size: 18pt; font-weight: 800; color: #1e40af; }
            .subtitle { font-size: 10pt; color: #374151; }
            .generated { font-size: 9pt; color: #6B7280; font-style: italic; }
        ';

        $full_html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <style>' . $css . '</style>
</head>
<body>
    <table class="report">';

        $full_html .= '<tr class="meta-row"><td colspan="' . $colCount . '" class="title">' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</td></tr>';
        $full_html .= '<tr class="meta-row"><td colspan="' . $colCount . '" class="subtitle">Coffee Shop Management System</td></tr>';
        $full_html .= '<tr class="meta-row"><td colspan="' . $colCount . '" class="generated">Generated: ' . htmlspecialchars(date('d-m-Y H:i:s'), ENT_QUOTES, 'UTF-8') . '</td></tr>';
        $full_html .= '<tr class="meta-gap"><td colspan="' . $colCount . '">&nbsp;</td></tr>';

        // Header row
        $full_html .= '<tr class="header-row">';
        foreach ($headers as $header) {
            $full_html .= '<th>' . htmlspecialchars((string)$header, ENT_QUOTES, 'UTF-8') . '</th>';
        }
        $full_html .= '</tr>';

        // Data rows
        if (is_array($data) && count($data) > 0) {
            foreach ($data as $row) {
                if (!is_array($row)) {
                    $row = [$row];
                }

                // Normalize column count
                if (count($row) < $colCount) {
                    $row = array_pad($row, $colCount, '');
                } elseif (count($row) > $colCount) {
                    $row = array_slice($row, 0, $colCount);
                }

                $allEmpty = true;
                $isTotalRow = false;
                foreach ($row as $cell) {
                    $cellStr = is_scalar($cell) ? (string)$cell : '';
                    if (trim($cellStr) !== '') {
                        $allEmpty = false;
                    }
                    if ($cellStr !== '' && stripos($cellStr, 'TOTAL') !== false) {
                        $isTotalRow = true;
                    }
                }

                if ($allEmpty) {
                    $full_html .= '<tr class="meta-gap"><td colspan="' . $colCount . '">&nbsp;</td></tr>';
                    continue;
                }

                $full_html .= $isTotalRow ? '<tr class="total-row">' : '<tr>';

                foreach ($row as $cell) {
                    $cellStr = is_scalar($cell) ? (string)$cell : '';
                    $trimmed = trim($cellStr);

                    $classes = [];
                    if ($trimmed !== '' && (stripos($trimmed, 'Rp') === 0 || preg_match('/^\d+[\d\s\.\,]*$/', str_replace(['item', 'items'], '', strtolower($trimmed))))) {
                        $classes[] = 'right';
                    }

                    $classAttr = count($classes) ? ' class="' . implode(' ', $classes) . '"' : '';
                    $full_html .= '<td' . $classAttr . '>' . htmlspecialchars($cellStr, ENT_QUOTES, 'UTF-8') . '</td>';
                }

                $full_html .= '</tr>';
            }
        } else {
            $full_html .= '<tr><td colspan="' . $colCount . '">Tidak ada data.</td></tr>';
        }

        // Footer
        $full_html .= '<tr class="meta-gap"><td colspan="' . $colCount . '">&nbsp;</td></tr>';
        $full_html .= '<tr class="meta-row"><td colspan="' . $colCount . '" class="subtitle">© ' . htmlspecialchars(date('Y'), ENT_QUOTES, 'UTF-8') . ' Coffee Shop. All rights reserved.</td></tr>';
        $full_html .= '</table>
</body>
</html>';

        // Write with UTF-8 BOM for better Excel compatibility
        file_put_contents($file_path, chr(0xEF) . chr(0xBB) . chr(0xBF) . $full_html);
        
        // Send file to browser
        header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($file_path));
        readfile($file_path);
        exit;
    }
    
    /**
     * Generate PDF format (HTML yang bisa dicetak)
     */
    public function exportToPDF($filename, $title, $html_content) {
        $file_path = $this->download_path . '/' . str_replace('.pdf', '.html', $filename);
        
        $full_html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . htmlspecialchars($title) . '</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: "Segoe UI", "Arial", sans-serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #2c3e50;
            background: #f8f9fa;
        }
        .container { 
            max-width: 210mm;
            margin: 0 auto; 
            padding: 20px;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #1e40af;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }
        .header h1 {
            font-size: 22pt;
            color: #1e40af;
            margin-bottom: 8px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .header .subtitle {
            font-size: 10pt;
            color: #555;
            margin-bottom: 5px;
            font-weight: 500;
        }
        .header .generated {
            font-size: 9pt;
            color: #777;
            margin-top: 8px;
            font-style: italic;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        th {
            background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);
            color: white;
            padding: 12px 10px;
            text-align: left;
            font-weight: 600;
            font-size: 10pt;
            border: 1px solid #1e3a8a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        th:last-child {
            text-align: right;
        }
        td {
            padding: 11px 10px;
            border: 1px solid #e0e0e0;
            font-size: 10pt;
        }
        td:last-child {
            text-align: right;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        tr:hover {
            background-color: #eff6ff;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .bold { font-weight: 600; }
        .total-row {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            font-weight: 700;
            border-top: 2px solid #1e40af;
            border-bottom: 2px solid #1e40af;
            color: #0c2340;
        }
        .total-row td {
            border-color: #1e40af;
            padding: 13px 10px;
        }
        .footer {
            text-align: center;
            border-top: 2px solid #1e40af;
            padding-top: 20px;
            margin-top: 30px;
            font-size: 9pt;
            color: #666;
        }
        .footer p {
            margin: 3px 0;
        }
        @media print {
            body { 
                margin: 0; 
                padding: 10mm; 
                background: white;
            }
            .container { 
                padding: 0; 
                max-width: 100%;
                box-shadow: none;
            }
            table { box-shadow: none; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>' . htmlspecialchars($title) . '</h1>
            <div class="subtitle">Coffee Shop Management System</div>
            <div class="generated">Generated: ' . date('d-m-Y H:i:s') . '</div>
        </div>
        
        ' . $html_content . '
        
        <div class="footer">
            <p>&copy; ' . date('Y') . ' Coffee Shop. All rights reserved.</p>
            <p>This document was automatically generated by Coffee Shop Management System</p>
        </div>
    </div>
</body>
</html>';
        
        file_put_contents($file_path, $full_html);
        
        // Send file to browser
        header('Content-Type: text/html; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . str_replace('.pdf', '.html', $filename) . '"');
        header('Content-Length: ' . filesize($file_path));
        readfile($file_path);
        exit;
    }
    
    /**
     * Get file path untuk view
     */
    public function getFilePath($filename) {
        return $this->download_path . '/' . $filename;
    }
    
    /**
     * Get list of recent downloads
     */
    public function getRecentDownloads($limit = 10) {
        $files = [];
        if (is_dir($this->download_path)) {
            $all_files = scandir($this->download_path, SCANDIR_SORT_DESCENDING);
            $files = array_slice(array_filter($all_files, function($f) {
                return $f != '.' && $f != '..';
            }), 0, $limit);
        }
        return $files;
    }
}
?>
