<?php

namespace Database\Seeders;

use App\Models\Gallery;
use App\Models\GiftAccount;
use App\Models\Guest;
use App\Models\GuestMessage;
use App\Models\Invitation;
use App\Models\InvitationTemplate;
use App\Models\Plan;
use App\Models\Role;
use App\Models\Rsvp;
use App\Models\Setting;
use App\Models\Story;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $superAdminRole = Role::query()->firstOrCreate(['slug' => 'super-admin'], ['name' => 'Super Admin']);
        $customerRole = Role::query()->firstOrCreate(['slug' => 'customer'], ['name' => 'Customer']);

        $plans = collect([
            ['name' => 'Free', 'slug' => 'free', 'description' => 'Mulai cepat untuk satu undangan sederhana.', 'price' => 0, 'invitation_limit' => 1, 'guest_limit' => 50, 'gallery_limit' => 6, 'custom_music' => false, 'qr_code' => true, 'rsvp' => true, 'custom_domain' => false, 'active_days' => 14],
            ['name' => 'Basic', 'slug' => 'basic', 'description' => 'Cocok untuk acara kecil dengan RSVP dan galeri lebih lega.', 'price' => 79000, 'invitation_limit' => 3, 'guest_limit' => 250, 'gallery_limit' => 20, 'custom_music' => true, 'qr_code' => true, 'rsvp' => true, 'custom_domain' => false, 'active_days' => 60],
            ['name' => 'Premium', 'slug' => 'premium', 'description' => 'Untuk undangan personal dengan custom domain dan statistik lengkap.', 'price' => 149000, 'invitation_limit' => 8, 'guest_limit' => 800, 'gallery_limit' => 60, 'custom_music' => true, 'qr_code' => true, 'rsvp' => true, 'custom_domain' => true, 'active_days' => 180, 'is_featured' => true],
            ['name' => 'Exclusive', 'slug' => 'exclusive', 'description' => 'Paket prioritas untuk banyak acara, tamu besar, dan kebutuhan khusus.', 'price' => 349000, 'invitation_limit' => 20, 'guest_limit' => 2500, 'gallery_limit' => 120, 'custom_music' => true, 'qr_code' => true, 'rsvp' => true, 'custom_domain' => true, 'active_days' => 365],
        ])->map(fn ($plan) => Plan::query()->updateOrCreate(['slug' => $plan['slug']], $plan));

        $templates = collect([
            ['name' => 'Elegant Wedding', 'slug' => 'elegant-wedding', 'category' => 'wedding', 'description' => 'Layout tenang dengan aksen editorial dan foto besar.', 'preview_image_path' => 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=1200&q=80', 'view_path' => 'invitations.templates.elegant', 'default_theme' => ['color' => '#a4785b', 'font' => 'Georgia']],
            ['name' => 'Modern Minimalist', 'slug' => 'modern-minimalist', 'category' => 'custom', 'description' => 'Ruang putih lega, komposisi bersih, dan tipografi tegas.', 'preview_image_path' => 'https://images.unsplash.com/photo-1523438885200-e635ba2c371e?auto=format&fit=crop&w=1200&q=80', 'view_path' => 'invitations.templates.elegant', 'default_theme' => ['color' => '#5d6f73', 'font' => 'Instrument Sans']],
            ['name' => 'Floral Luxury', 'slug' => 'floral-luxury', 'category' => 'wedding', 'description' => 'Nuansa floral lembut untuk acara yang formal namun hangat.', 'preview_image_path' => 'https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?auto=format&fit=crop&w=1200&q=80', 'view_path' => 'invitations.templates.elegant', 'default_theme' => ['color' => '#b87885', 'font' => 'Garamond'], 'is_premium' => true],
            ['name' => 'Islamic Soft', 'slug' => 'islamic-soft', 'category' => 'wedding', 'description' => 'Palet sejuk dan detail sederhana untuk undangan bernapas Islami.', 'preview_image_path' => 'https://images.unsplash.com/photo-1564769625905-50e93615e769?auto=format&fit=crop&w=1200&q=80', 'view_path' => 'invitations.templates.elegant', 'default_theme' => ['color' => '#71816d', 'font' => 'Georgia']],
            ['name' => 'Birthday Fun', 'slug' => 'birthday-fun', 'category' => 'birthday', 'description' => 'Lebih cerah tanpa terasa ramai berlebihan.', 'preview_image_path' => 'https://images.unsplash.com/photo-1530103862676-de8c9debad1d?auto=format&fit=crop&w=1200&q=80', 'view_path' => 'invitations.templates.elegant', 'default_theme' => ['color' => '#d08b6b', 'font' => 'Trebuchet MS']],
            ['name' => 'Corporate Event', 'slug' => 'corporate-event', 'category' => 'seminar', 'description' => 'Rapi dan profesional untuk seminar, gathering, dan peluncuran.', 'preview_image_path' => 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=1200&q=80', 'view_path' => 'invitations.templates.elegant', 'default_theme' => ['color' => '#4f6873', 'font' => 'Verdana']],
        ])->map(fn ($template) => InvitationTemplate::query()->updateOrCreate(['slug' => $template['slug']], $template));

        User::query()->updateOrCreate(
            ['email' => 'admin@undi.test'],
            [
                'role_id' => $superAdminRole->id,
                'name' => 'Undi Admin',
                'password' => Hash::make('password'),
            ]
        );

        $customer = User::query()->updateOrCreate(
            ['email' => 'customer@undi.test'],
            [
                'role_id' => $customerRole->id,
                'name' => 'Andi Pratama',
                'password' => Hash::make('password'),
            ]
        );

        $customer->subscriptions()->updateOrCreate(
            ['plan_id' => $plans->firstWhere('slug', 'premium')->id],
            ['status' => 'active', 'starts_at' => now(), 'ends_at' => now()->addMonths(6)]
        );

        $invitation = Invitation::query()->updateOrCreate(
            ['slug' => 'andi-sinta'],
            [
                'user_id' => $customer->id,
                'template_id' => $templates->firstWhere('slug', 'elegant-wedding')->id,
                'title' => 'Pernikahan Andi & Sinta',
                'event_type' => 'wedding',
                'primary_name' => 'Andi',
                'secondary_name' => 'Sinta',
                'host_name' => 'Keluarga Besar Andi dan Sinta',
                'event_date' => now()->addMonths(2)->toDateString(),
                'event_time' => '10:00',
                'venue_name' => 'The Langham Jakarta',
                'venue_address' => 'SCBD, Jakarta Selatan',
                'maps_url' => 'https://maps.google.com/?q=The+Langham+Jakarta',
                'maps_embed_url' => 'https://www.google.com/maps?q=The%20Langham%20Jakarta&output=embed',
                'description' => 'Dengan penuh syukur, kami mengundang Bapak/Ibu/Saudara/i untuk hadir dan memberi doa restu.',
                'status' => 'active',
                'theme_color' => '#a4785b',
                'theme_font' => 'Georgia',
                'music_enabled' => false,
            ]
        );

        foreach (['Bapak Ahmad', 'Ibu Rina', 'Dimas & Keluarga'] as $guestName) {
            Guest::query()->updateOrCreate(
                ['invitation_id' => $invitation->id, 'name' => $guestName],
                ['category' => 'family', 'max_companions' => 2, 'status' => 'draft']
            );
        }

        foreach ([
            ['image_path' => 'https://images.unsplash.com/photo-1529636798458-92182e662485?auto=format&fit=crop&w=1200&q=80', 'caption' => 'Prewedding di sore yang tenang'],
            ['image_path' => 'https://images.unsplash.com/photo-1525258946800-98cfd641d0de?auto=format&fit=crop&w=1200&q=80', 'caption' => 'Momen sederhana yang kami simpan'],
            ['image_path' => 'https://images.unsplash.com/photo-1509927083803-4bd519298ac4?auto=format&fit=crop&w=1200&q=80', 'caption' => 'Menuju hari baik'],
        ] as $index => $gallery) {
            Gallery::query()->updateOrCreate(
                ['invitation_id' => $invitation->id, 'image_path' => $gallery['image_path']],
                ['caption' => $gallery['caption'], 'sort_order' => $index]
            );
        }

        foreach ([
            ['title' => 'Pertama Bertemu', 'description' => 'Kami bertemu lewat proyek kecil yang ternyata menjadi awal cerita panjang.', 'story_date' => now()->subYears(4)->toDateString()],
            ['title' => 'Lamaran', 'description' => 'Di antara keluarga terdekat, kami memantapkan langkah untuk berjalan bersama.', 'story_date' => now()->subMonths(6)->toDateString()],
        ] as $index => $story) {
            Story::query()->updateOrCreate(
                ['invitation_id' => $invitation->id, 'title' => $story['title']],
                [...$story, 'sort_order' => $index]
            );
        }

        GiftAccount::query()->updateOrCreate(
            ['invitation_id' => $invitation->id, 'type' => 'bank', 'account_number' => '1234567890'],
            ['provider_name' => 'BCA', 'account_holder' => 'Sinta Maharani', 'is_visible' => true]
        );

        Rsvp::query()->updateOrCreate(
            ['invitation_id' => $invitation->id, 'name' => 'Bapak Ahmad'],
            ['attendance' => 'attending', 'guests_count' => 2]
        );

        GuestMessage::query()->updateOrCreate(
            ['invitation_id' => $invitation->id, 'name' => 'Ibu Rina'],
            ['message' => 'Selamat menempuh hidup baru. Semoga menjadi keluarga yang hangat dan penuh berkah.', 'is_visible' => true, 'approved_at' => now()]
        );

        foreach ([
            ['group' => 'general', 'key' => 'site_name', 'value' => 'Undi', 'is_public' => true],
            ['group' => 'general', 'key' => 'meta_description', 'value' => 'Platform undangan digital modern untuk acara personal dan profesional.', 'is_public' => true],
            ['group' => 'support', 'key' => 'support_whatsapp', 'value' => '+6281234567890', 'is_public' => true],
        ] as $setting) {
            Setting::query()->updateOrCreate(['group' => $setting['group'], 'key' => $setting['key']], $setting);
        }

        User::factory(5)->create(['role_id' => $customerRole->id]);
    }
}
