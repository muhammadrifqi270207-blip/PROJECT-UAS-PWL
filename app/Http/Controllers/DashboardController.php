<?php

namespace App\Http\Controllers;

use App\Models\Konser;
use App\Models\Tiket;
use App\Models\Order;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class DashboardController extends Controller
{
    public function index()
    {
        $totalKonser     = Konser::count();
        $konserAktif     = Konser::where('status', 'aktif')->count();
        $totalTiket      = Tiket::sum('kuota');
        $totalTerjual    = Tiket::sum('terjual');
        $totalPendapatan = Tiket::selectRaw('SUM(harga * terjual) as total')->value('total') ?? 0;
        $konserTerbaru   = Konser::with('tikets')->latest()->take(5)->get();

        $grafikKonser = Konser::with('tikets')
            ->where('status', 'aktif')
            ->get()
            ->map(function($k) {
                return [
                    'nama'    => $k->nama_konser,
                    'terjual' => $k->tikets->sum('terjual'),
                    'kuota'   => $k->tikets->sum('kuota'),
                    'sisa'    => $k->tikets->sum('kuota') - $k->tikets->sum('terjual'),
                    'persen'  => $k->tikets->sum('kuota') > 0
                        ? round(($k->tikets->sum('terjual') / $k->tikets->sum('kuota')) * 100)
                        : 0,
                ];
            });

        $grafikPendapatan = Konser::with('tikets')
            ->where('status', 'aktif')
            ->get()
            ->map(function($k) {
                return [
                    'nama'       => $k->nama_konser,
                    'pendapatan' => $k->tikets->sum(function($t) {
                        return $t->harga * $t->terjual;
                    }),
                ];
            });

        $grafikBulanan = collect();
        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $pendapatan = Order::where('status', 'paid')
                ->whereYear('created_at', $bulan->year)
                ->whereMonth('created_at', $bulan->month)
                ->sum('total_harga');

            $grafikBulanan->push([
                'bulan'      => $bulan->format('M Y'),
                'pendapatan' => $pendapatan,
            ]);
        }

        return view('dashboard', compact(
            'totalKonser', 'konserAktif',
            'totalTiket', 'totalTerjual',
            'totalPendapatan', 'konserTerbaru',
            'grafikKonser', 'grafikPendapatan', 'grafikBulanan'
        ));
    }

    public function exportOrders()
    {
        $orders = Order::with('items.tiket.konser', 'user')->latest()->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Orders');

        // Style header
        $headerStyle = [
            'font' => [
                'bold'  => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size'  => 12,
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4C1D95'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['rgb' => '7C3AED'],
                ],
            ],
        ];

        // Style baris genap
        $rowGenapStyle = [
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'EDE9FE'],
            ],
            'font'      => ['color' => ['rgb' => '1F2937'], 'size' => 11],
            'borders'   => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['rgb' => 'DDD6FE'],
                ],
            ],
        ];

        // Style baris ganjil
        $rowGanjilStyle = [
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFFFFF'],
            ],
            'font'      => ['color' => ['rgb' => '1F2937'], 'size' => 11],
            'borders'   => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['rgb' => 'DDD6FE'],
                ],
            ],
        ];

        // Header kolom
        $headers = [
            'A' => 'No',
            'B' => 'Kode Order',
            'C' => 'Nama Pemesan',
            'D' => 'Email',
            'E' => 'Konser',
            'F' => 'Kategori Tiket',
            'G' => 'Jumlah',
            'H' => 'Total Harga',
            'I' => 'Status',
            'J' => 'Tanggal Order',
        ];

        foreach ($headers as $col => $header) {
            $sheet->setCellValue($col . '1', $header);
            $sheet->getStyle($col . '1')->applyFromArray($headerStyle);
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $sheet->getRowDimension(1)->setRowHeight(25);
        }

        // Isi data
        $row = 2;
        foreach ($orders as $no => $order) {
            foreach ($order->items as $item) {
                $sheet->setCellValue('A' . $row, $no + 1);
                $sheet->setCellValue('B' . $row, $order->kode_order);
                $sheet->setCellValue('C' . $row, $order->nama_pemesan);
                $sheet->setCellValue('D' . $row, $order->email_pemesan);
                $sheet->setCellValue('E' . $row, $item->tiket->konser->nama_konser ?? '-');
                $sheet->setCellValue('F' . $row, $item->tiket->kategori ?? '-');
                $sheet->setCellValue('G' . $row, $item->jumlah);
                $sheet->setCellValue('H' . $row, 'Rp ' . number_format($order->total_harga));
                $sheet->setCellValue('I' . $row, ucfirst($order->status));
                $sheet->setCellValue('J' . $row, $order->created_at->format('d M Y H:i'));

                // Warna baris selang-seling
                $style = $row % 2 == 0 ? $rowGenapStyle : $rowGanjilStyle;
                $sheet->getStyle('A' . $row . ':J' . $row)->applyFromArray($style);

                // Center kolom tertentu
                $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('G' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('I' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $row++;
            }
        }

        // Download file
        $filename = 'data-orders-' . date('Y-m-d') . '.xlsx';
        $writer   = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}