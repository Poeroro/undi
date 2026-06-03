<?php

namespace App\Http\Controllers;

use App\Exports\GuestsExport;
use App\Exports\RsvpsExport;
use App\Imports\GuestsImport;
use App\Models\Invitation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SpreadsheetController extends Controller
{
    public function importGuests(Request $request, Invitation $invitation): RedirectResponse
    {
        $this->authorizeOwner($request, $invitation);
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:4096'],
        ]);

        Excel::import(new GuestsImport($invitation), $request->file('file'));

        return back()->with('status', 'Import tamu selesai.');
    }

    public function exportGuests(Request $request, Invitation $invitation): BinaryFileResponse
    {
        $this->authorizeOwner($request, $invitation);

        return Excel::download(new GuestsExport($invitation), "tamu-{$invitation->slug}.xlsx");
    }

    public function exportRsvps(Request $request, Invitation $invitation): BinaryFileResponse
    {
        $this->authorizeOwner($request, $invitation);

        return Excel::download(new RsvpsExport($invitation), "rsvp-{$invitation->slug}.xlsx");
    }

    private function authorizeOwner(Request $request, Invitation $invitation): void
    {
        abort_unless($invitation->user_id === $request->user()->id || $request->user()->hasRole('super-admin'), 403);
    }
}
