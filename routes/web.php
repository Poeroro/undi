<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvitationManagementController;
use App\Http\Controllers\InvitationSectionController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PublicInvitationController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\SpreadsheetController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/pricing', [LandingController::class, 'pricing'])->name('pricing');
Route::get('/template', [LandingController::class, 'templates'])->name('templates');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.authenticate');
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'store'])->name('register.store');
    Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.request');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/u/{slug}', [PublicInvitationController::class, 'show'])->name('invitations.show');
Route::post('/u/{slug}/unlock', [PublicInvitationController::class, 'unlock'])->middleware('throttle:public-forms')->name('invitations.unlock');
Route::post('/u/{slug}/rsvp', [PublicInvitationController::class, 'rsvp'])->middleware('throttle:public-forms')->name('invitations.rsvp');
Route::post('/u/{slug}/message', [PublicInvitationController::class, 'message'])->middleware('throttle:public-forms')->name('invitations.message');
Route::get('/u/{slug}/share', [PublicInvitationController::class, 'share'])->name('invitations.share');

Route::middleware('auth')->prefix('dashboard')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::prefix('invitations')->name('dashboard.invitations.')->group(function () {
        Route::get('/', [InvitationManagementController::class, 'index'])->name('index');
        Route::get('/create', [InvitationManagementController::class, 'create'])->name('create');
        Route::post('/', [InvitationManagementController::class, 'store'])->name('store');
        Route::get('/{invitation:slug}/edit', [InvitationManagementController::class, 'edit'])->name('edit');
        Route::put('/{invitation:slug}', [InvitationManagementController::class, 'update'])->name('update');

        Route::get('/{invitation:slug}/guests', [InvitationSectionController::class, 'guests'])->name('guests');
        Route::post('/{invitation:slug}/guests', [InvitationSectionController::class, 'storeGuest'])->name('guests.store');
        Route::post('/{invitation:slug}/guests/import', [SpreadsheetController::class, 'importGuests'])->name('guests.import');
        Route::get('/{invitation:slug}/guests/export', [SpreadsheetController::class, 'exportGuests'])->name('guests.export');
        Route::get('/{invitation:slug}/rsvps', [InvitationSectionController::class, 'rsvps'])->name('rsvps');
        Route::get('/{invitation:slug}/rsvps/export', [SpreadsheetController::class, 'exportRsvps'])->name('rsvps.export');
        Route::get('/{invitation:slug}/messages', [InvitationSectionController::class, 'messages'])->name('messages');
        Route::post('/{invitation:slug}/messages/{message}/approve', [InvitationSectionController::class, 'approveMessage'])->name('messages.approve');
        Route::get('/{invitation:slug}/gallery', [InvitationSectionController::class, 'gallery'])->name('gallery');
        Route::post('/{invitation:slug}/gallery', [InvitationSectionController::class, 'storeGallery'])->name('gallery.store');
        Route::get('/{invitation:slug}/stories', [InvitationSectionController::class, 'stories'])->name('stories');
        Route::post('/{invitation:slug}/stories', [InvitationSectionController::class, 'storeStory'])->name('stories.store');
        Route::get('/{invitation:slug}/gift', [InvitationSectionController::class, 'gifts'])->name('gift');
        Route::post('/{invitation:slug}/gift', [InvitationSectionController::class, 'storeGift'])->name('gift.store');
        Route::get('/{invitation:slug}/statistics', [InvitationSectionController::class, 'statistics'])->name('statistics');
    });
});
