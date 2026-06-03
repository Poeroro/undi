<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();
        $invitations = $user->invitations()->withCount(['guests', 'rsvps', 'messages'])->latest()->get();

        return view('dashboard.index', [
            'user' => $user->load('subscription.plan'),
            'invitations' => $invitations,
            'stats' => [
                'invitations' => $invitations->count(),
                'guests' => $invitations->sum('guests_count'),
                'attending' => $invitations->sum(fn ($invitation) => $invitation->rsvps()->where('attendance', 'attending')->sum('guests_count')),
                'messages' => $invitations->sum('messages_count'),
                'views' => $invitations->sum('view_count'),
            ],
        ]);
    }
}
