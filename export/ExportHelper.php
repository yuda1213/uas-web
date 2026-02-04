<?php
/**
 * Export Helper Functions
 * Fungsi-fungsi untuk export yang sudah terintegrasi dengan ExportManager
 */

if (!function_exists('exportOrdersToExcelProper')) {
    function exportOrdersToExcelProper($orders) {
        $manager = new ExportManager();
        
        $filename = 'Laporan_Pesanan_' . date('Y-m-d_H-i-s') . '.xls';
        $title = 'LAPORAN PESANAN PENJUALAN';
        
        $headers = [
            'No',
            'Nomor Pesanan',
            'Nama Pelanggan',
            'Email',
            'No Telepon',
            'Tanggal Pesanan',
            'Total Harga',
            'Status',
            'Catatan'
        ];
        
        $data = [];
        $no = 1;
        $total = 0;
        
        foreach ($orders as $order) {
            $data[] = [
                $no++,
                $order['no_pesanan'] ?? '',
                $order['nama'] ?? '',
                $order['email'] ?? '-',
                $order['no_telepon'] ?? '-',
                isset($order['tanggal_pesanan']) ? date('d-m-Y H:i', strtotime($order['tanggal_pesanan'])) : '-',
                'Rp ' . number_format($order['total_harga'] ?? 0, 0, ',', '.'),
                $order['status'] ?? '',
                $order['catatan'] ?? '-'
            ];
            $total += ($order['total_harga'] ?? 0);
        }
        
        // Add blank row
        $data[] = ['', '', '', '', '', '', '', '', ''];
        
        // Add total row dengan formatting
        $data[] = [
            '',
            '',
            '',
            '',
            '',
            'TOTAL PENJUALAN:',
            'Rp ' . number_format($total, 0, ',', '.'),
            '',
            ''
        ];
        
        $manager->exportToExcel($filename, $title, $data, $headers);
    }
}

if (!function_exists('exportOrdersToPDFProper')) {
    function exportOrdersToPDFProper($orders) {
        $manager = new ExportManager();
        
        $filename = 'Laporan_Pesanan_' . date('Y-m-d_H-i-s') . '.pdf';
        $title = 'LAPORAN PESANAN PENJUALAN';
        
        $table = '<table><thead><tr>';
        $table .= '<th style="width: 4%;">No</th>';
        $table .= '<th style="width: 14%;">Nomor Pesanan</th>';
        $table .= '<th style="width: 15%;">Nama Pelanggan</th>';
        $table .= '<th style="width: 18%;">Email</th>';
        $table .= '<th style="width: 10%;">Tanggal</th>';
        $table .= '<th style="width: 14%; text-align: right;">Total Harga</th>';
        $table .= '<th style="width: 10%;">Status</th>';
        $table .= '</tr></thead><tbody>';
        
        $no = 1;
        $total = 0;
        $row_count = 0;
        
        foreach ($orders as $order) {
            $table .= '<tr>';
            $table .= '<td>' . $no++ . '</td>';
            $table .= '<td><strong>' . htmlspecialchars($order['no_pesanan'] ?? '') . '</strong></td>';
            $table .= '<td>' . htmlspecialchars($order['nama'] ?? '') . '</td>';
            $table .= '<td style="font-size: 9pt;">' . htmlspecialchars($order['email'] ?? '-') . '</td>';
            $table .= '<td>' . (isset($order['tanggal_pesanan']) ? date('d-m-Y', strtotime($order['tanggal_pesanan'])) : '-') . '</td>';
            $table .= '<td style="text-align: right; font-weight: 600;">Rp ' . number_format($order['total_harga'] ?? 0, 0, ',', '.') . '</td>';
            $table .= '<td style="text-align: center;"><strong>' . htmlspecialchars($order['status'] ?? '') . '</strong></td>';
            $table .= '</tr>';
            $total += ($order['total_harga'] ?? 0);
            $row_count++;
        }
        
        $table .= '<tr class="total-row">';
        $table .= '<td colspan="5" style="text-align: right;">TOTAL PENJUALAN:</td>';
        $table .= '<td style="text-align: right;">Rp ' . number_format($total, 0, ',', '.') . '</td>';
        $table .= '<td></td>';
        $table .= '</tr>';
        $table .= '</tbody></table>';
        
        $html_content = $table;
        $manager->exportToPDF($filename, $title, $html_content);
    }
}

if (!function_exists('exportProductsToExcelProper')) {
    function exportProductsToExcelProper($products) {
        $manager = new ExportManager();
        
        $filename = 'Laporan_Produk_' . date('Y-m-d_H-i-s') . '.xls';
        $title = 'LAPORAN PENJUALAN PRODUK';
        
        $headers = [
            'No',
            'Nama Produk',
            'Kategori',
            'Jumlah Terjual',
            'Total Penjualan'
        ];
        
        $data = [];
        $no = 1;
        $total = 0;
        
        foreach ($products as $product) {
            $data[] = [
                $no++,
                $product['nama_produk'] ?? '',
                $product['nama_kategori'] ?? '',
                intval($product['total_jumlah'] ?? 0) . ' item',
                'Rp ' . number_format($product['total_penjualan'] ?? 0, 0, ',', '.')
            ];
            $total += ($product['total_penjualan'] ?? 0);
        }
        
        // Add blank row
        $data[] = ['', '', '', '', ''];
        
        // Add total row dengan formatting
        $data[] = [
            '',
            '',
            'TOTAL PENJUALAN:',
            '',
            'Rp ' . number_format($total, 0, ',', '.')
        ];
        
        $manager->exportToExcel($filename, $title, $data, $headers);
    }
}

if (!function_exists('exportProductsToPDFProper')) {
    function exportProductsToPDFProper($products) {
        $manager = new ExportManager();
        
        $filename = 'Laporan_Produk_' . date('Y-m-d_H-i-s') . '.pdf';
        $title = 'LAPORAN PENJUALAN PRODUK';
        
        $table = '<table><thead><tr>';
        $table .= '<th style="width: 4%;">No</th>';
        $table .= '<th style="width: 30%;">Nama Produk</th>';
        $table .= '<th style="width: 18%;">Kategori</th>';
        $table .= '<th style="width: 14%; text-align: right;">Jumlah Terjual</th>';
        $table .= '<th style="width: 18%; text-align: right;">Total Penjualan</th>';
        $table .= '</tr></thead><tbody>';
        
        $no = 1;
        $total = 0;
        $row_count = 0;
        
        foreach ($products as $product) {
            $table .= '<tr>';
            $table .= '<td>' . $no++ . '</td>';
            $table .= '<td><strong>' . htmlspecialchars($product['nama_produk'] ?? '') . '</strong></td>';
            $table .= '<td>' . htmlspecialchars($product['nama_kategori'] ?? '') . '</td>';
            $table .= '<td style="text-align: right;">' . intval($product['total_jumlah'] ?? 0) . ' item</td>';
            $table .= '<td style="text-align: right; font-weight: 600;">Rp ' . number_format($product['total_penjualan'] ?? 0, 0, ',', '.') . '</td>';
            $table .= '</tr>';
            $total += ($product['total_penjualan'] ?? 0);
            $row_count++;
        }
        
        $table .= '<tr class="total-row">';
        $table .= '<td colspan="4" style="text-align: right;">TOTAL PENJUALAN:</td>';
        $table .= '<td style="text-align: right;">Rp ' . number_format($total, 0, ',', '.') . '</td>';
        $table .= '</tr>';
        $table .= '</tbody></table>';
        
        $html_content = $table;
        $manager->exportToPDF($filename, $title, $html_content);
    }
}
?>
