<?php

namespace App\Http\Controllers;

use App\Models\InvitationTemplate;
use App\Models\Plan;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function index(): View
    {
        return view('landing.index', [
            'plans' => Plan::query()->where('is_active', true)->orderBy('price')->get(),
            'templates' => InvitationTemplate::query()->where('is_active', true)->take(6)->get(),
        ]);
    }

    public function pricing(): View
    {
        return view('landing.pricing', [
            'plans' => Plan::query()->where('is_active', true)->orderBy('price')->get(),
        ]);
    }

    public function templates(): View
    {
        return view('landing.templates', [
            'templates' => InvitationTemplate::query()->where('is_active', true)->latest()->paginate(12),
        ]);
    }
}
