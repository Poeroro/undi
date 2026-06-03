<?php

namespace App\Exports;

use App\Models\Invitation;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RsvpsExport implements FromArray, WithHeadings
{
    public function __construct(private readonly Invitation $invitation)
    {
    }

    public function headings(): array
    {
        return ['Nama', 'Status', 'Jumlah Hadir', 'Catatan', 'Waktu'];
    }

    public function array(): array
    {
        return $this->invitation->rsvps()
            ->latest()
            ->get()
            ->map(fn ($rsvp) => [
                $rsvp->name,
                $rsvp->attendance,
                $rsvp->guests_count,
                $rsvp->notes,
                $rsvp->created_at?->toDateTimeString(),
            ])
            ->all();
    }
}
