<?php

namespace App\Exports;

use App\Models\Invitation;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GuestsExport implements FromArray, WithHeadings
{
    public function __construct(private readonly Invitation $invitation)
    {
    }

    public function headings(): array
    {
        return ['Nama', 'WhatsApp', 'Email', 'Kategori', 'Maksimal Tamu', 'Status', 'Link Personal'];
    }

    public function array(): array
    {
        return $this->invitation->guests()
            ->get()
            ->map(fn ($guest) => [
                $guest->name,
                $guest->whatsapp,
                $guest->email,
                $guest->category,
                $guest->max_companions,
                $guest->status,
                $guest->invitation->publicUrl($guest),
            ])
            ->all();
    }
}
