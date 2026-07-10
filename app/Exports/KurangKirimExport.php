<?php

namespace App\Exports;

use App\Models\KurangKirim;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KurangKirimExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    use Exportable;

    private int $row = 0;
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = KurangKirim::with('toko')->where('status', 1);

        if ($this->request->filled('search')) {
            $search = $this->request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_surat_jalan', 'like', "%{$search}%")
                  ->orWhere('kode_toko', 'like', "%{$search}%")
                  ->orWhereHas('toko', function ($q2) use ($search) {
                      $q2->where('nama_toko', 'like', "%{$search}%");
                  });
            });
        }

        if ($this->request->filled('date_from')) {
            $query->where('tgl_kirim', '>=', $this->request->date_from);
        }
        if ($this->request->filled('date_to')) {
            $query->where('tgl_kirim', '<=', $this->request->date_to);
        }

        $sortField = $this->request->get('sort', 'created_at');
        $sortDirection = $this->request->get('direction', 'desc');

        $allowedSorts = ['nomor_surat_jalan', 'tgl_kirim', 'created_at'];
        if (!in_array($sortField, $allowedSorts)) {
            $sortField = 'created_at';
        }
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        return $query->orderBy($sortField, $sortDirection);
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Toko',
            'Nama Toko',
            'Tanggal Kirim',
            'Nomor Surat Jalan',
            'File Lampiran',
        ];
    }

    public function map($item): array
    {
        $this->row++;

        return [
            $this->row,
            $item->kode_toko,
            $item->toko->nama_toko ?? '-',
            $item->tgl_kirim->format('d/m/Y'),
            $item->nomor_surat_jalan,
            $item->lampiran ? basename($item->lampiran) : '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '6D28D9'],
                ],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }
}
