<?php

namespace App\Imports;

use App\Models\Guest;
use App\Models\Invitation;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GuestsImport implements ToModel, WithHeadingRow
{
    public function __construct(private readonly Invitation $invitation)
    {
    }

    public function model(array $row): ?Guest
    {
        $name = $row['nama'] ?? $row['name'] ?? null;

        if (! $name) {
            return null;
        }

        return new Guest([
            'invitation_id' => $this->invitation->id,
            'name' => $name,
            'whatsapp' => $row['whatsapp'] ?? $row['wa'] ?? null,
            'email' => $row['email'] ?? null,
            'category' => $row['kategori'] ?? $row['category'] ?? 'custom',
            'max_companions' => (int) ($row['maksimal_tamu'] ?? $row['max_companions'] ?? 1),
            'personal_token' => Str::random(32),
            'status' => 'draft',
            'notes' => $row['catatan'] ?? $row['notes'] ?? null,
        ]);
    }
}
