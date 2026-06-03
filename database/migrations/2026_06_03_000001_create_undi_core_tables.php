<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->string('phone')->nullable()->after('email');
            $table->string('avatar_path')->nullable()->after('phone');
        });

        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->string('currency', 3)->default('IDR');
            $table->unsignedInteger('invitation_limit')->default(1);
            $table->unsignedInteger('guest_limit')->default(50);
            $table->unsignedInteger('gallery_limit')->default(8);
            $table->boolean('custom_music')->default(false);
            $table->boolean('qr_code')->default(false);
            $table->boolean('rsvp')->default(true);
            $table->boolean('custom_domain')->default(false);
            $table->unsignedInteger('active_days')->default(30);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained()->restrictOnDelete();
            $table->string('status')->default('active')->index();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('invitation_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('category')->default('wedding')->index();
            $table->text('description')->nullable();
            $table->string('preview_image_path')->nullable();
            $table->string('view_path')->default('invitations.templates.elegant');
            $table->json('default_theme')->nullable();
            $table->boolean('is_premium')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('template_id')->nullable()->constrained('invitation_templates')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('event_type')->default('wedding')->index();
            $table->string('primary_name');
            $table->string('secondary_name')->nullable();
            $table->string('host_name')->nullable();
            $table->string('cover_photo_path')->nullable();
            $table->string('profile_photo_path')->nullable();
            $table->date('event_date')->nullable()->index();
            $table->time('event_time')->nullable();
            $table->string('timezone')->default('Asia/Jakarta');
            $table->string('venue_name')->nullable();
            $table->text('venue_address')->nullable();
            $table->text('maps_url')->nullable();
            $table->text('maps_embed_url')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('draft')->index();
            $table->string('password')->nullable();
            $table->string('music_path')->nullable();
            $table->boolean('music_enabled')->default(false);
            $table->string('theme_color')->default('#9f7a56');
            $table->string('theme_font')->default('Inter');
            $table->string('youtube_url')->nullable();
            $table->longText('share_message_template')->nullable();
            $table->string('subdomain')->nullable()->unique();
            $table->string('custom_domain')->nullable()->unique();
            $table->string('domain_status')->default('not_configured');
            $table->unsignedBigInteger('view_count')->default(0);
            $table->unsignedBigInteger('share_count')->default(0);
            $table->timestamp('last_viewed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invitation_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('whatsapp')->nullable();
            $table->string('email')->nullable();
            $table->string('category')->default('custom')->index();
            $table->unsignedSmallInteger('max_companions')->default(1);
            $table->string('personal_token')->unique();
            $table->string('status')->default('draft')->index();
            $table->timestamp('invitation_sent_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('rsvps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invitation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('guest_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('attendance')->index();
            $table->unsignedSmallInteger('guests_count')->default(1);
            $table->text('notes')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });

        Schema::create('guest_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invitation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('guest_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->text('message');
            $table->boolean('is_visible')->default(false)->index();
            $table->timestamp('approved_at')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });

        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invitation_id')->constrained()->cascadeOnDelete();
            $table->string('type')->default('image');
            $table->string('image_path')->nullable();
            $table->string('video_url')->nullable();
            $table->string('caption')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('stories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invitation_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->date('story_date')->nullable();
            $table->text('description');
            $table->string('image_path')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('gift_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invitation_id')->constrained()->cascadeOnDelete();
            $table->string('type')->default('bank');
            $table->string('provider_name')->nullable();
            $table->string('account_holder')->nullable();
            $table->string('account_number')->nullable();
            $table->string('qris_path')->nullable();
            $table->text('instructions')->nullable();
            $table->boolean('is_visible')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('invitation_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invitation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('guest_id')->nullable()->constrained()->nullOnDelete();
            $table->string('session_id')->nullable()->index();
            $table->string('ip_hash', 64)->nullable()->index();
            $table->text('user_agent')->nullable();
            $table->string('path')->nullable();
            $table->text('referrer')->nullable();
            $table->timestamp('viewed_at')->useCurrent()->index();
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->nullable()->constrained()->nullOnDelete();
            $table->string('invoice_number')->unique();
            $table->string('gateway')->default('manual');
            $table->string('external_reference')->nullable()->index();
            $table->decimal('amount', 12, 2)->default(0);
            $table->string('currency', 3)->default('IDR');
            $table->string('status')->default('pending')->index();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('group')->default('general')->index();
            $table->string('key');
            $table->longText('value')->nullable();
            $table->string('type')->default('string');
            $table->boolean('is_public')->default(false);
            $table->timestamps();
            $table->unique(['group', 'key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('invitation_views');
        Schema::dropIfExists('gift_accounts');
        Schema::dropIfExists('stories');
        Schema::dropIfExists('galleries');
        Schema::dropIfExists('guest_messages');
        Schema::dropIfExists('rsvps');
        Schema::dropIfExists('guests');
        Schema::dropIfExists('invitations');
        Schema::dropIfExists('invitation_templates');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('plans');

        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('role_id');
            $table->dropColumn(['phone', 'avatar_path']);
        });

        Schema::dropIfExists('roles');
    }
};
