<?php

namespace App\Exports;

use App\Models\Qurbani;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class QurbaniExport implements FromCollection, WithHeadings
{
   public function collection()
    {
        return Qurbani::with('hissas', 'user')->get()->map(function ($qurbani) {
            return [
                'Contact Name'     => $qurbani->contact_name,
                'Mobile'           => $qurbani->mobile,
                'Total Hissa Count'=> $qurbani->hissas->sum('hissa'),
                'Total Amount'     => $qurbani->total_amount,
                'Collected By Name'     => optional($qurbani->user)->name,
                'Collected By Mobile'     => optional($qurbani->user)->mobile,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Name',
            'Mobile',
            'Hissa Count',
            'Total Amount',
            'Collected By Name',
            'Collected By Mobile',

        ];
    }
}
