<?php

return [
    'support_whatsapp' => env('UNDI_SUPPORT_WHATSAPP', '+6281234567890'),

    'event_types' => [
        'wedding' => 'Pernikahan',
        'birthday' => 'Ulang Tahun',
        'aqiqah' => 'Aqiqah',
        'khitanan' => 'Khitanan',
        'seminar' => 'Seminar',
        'gathering' => 'Gathering',
        'custom' => 'Custom',
    ],

    'guest_categories' => [
        'family' => 'Keluarga',
        'friend' => 'Teman',
        'coworker' => 'Rekan Kerja',
        'vip' => 'VIP',
        'custom' => 'Custom',
    ],

    'theme_fonts' => [
        'Instrument Sans' => [
            'label' => 'Instrument Sans',
            'family' => '"Instrument Sans", ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif',
        ],
        'Inter' => [
            'label' => 'Inter',
            'family' => 'Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif',
        ],
        'Georgia' => [
            'label' => 'Georgia',
            'family' => 'Georgia, "Times New Roman", serif',
        ],
        'Trebuchet MS' => [
            'label' => 'Trebuchet MS',
            'family' => '"Trebuchet MS", "Segoe UI", ui-sans-serif, sans-serif',
        ],
        'Verdana' => [
            'label' => 'Verdana',
            'family' => 'Verdana, Geneva, ui-sans-serif, sans-serif',
        ],
        'Garamond' => [
            'label' => 'Garamond',
            'family' => 'Garamond, Georgia, "Times New Roman", serif',
        ],
    ],

    'template_skins' => [
        'elegant-wedding' => [
            'label' => 'Elegant Wedding',
            'cover_label' => 'The Wedding Invitation',
            'default_theme' => ['color' => '#a4785b', 'font' => 'Georgia'],
            'main_bg' => '#fbf7f1',
            'soft_bg' => '#faf7f2',
            'section_bg' => '#ffffff',
            'dark_bg' => '#24211e',
            'hero_overlay' => 'from-black/20 via-black/35 to-black/80',
            'intro_label' => 'Dengan penuh syukur',
        ],
        'modern-minimalist' => [
            'label' => 'Modern Minimalist',
            'cover_label' => 'You Are Invited',
            'default_theme' => ['color' => '#5d6f73', 'font' => 'Instrument Sans'],
            'main_bg' => '#f7faf8',
            'soft_bg' => '#edf3ef',
            'section_bg' => '#ffffff',
            'dark_bg' => '#263233',
            'hero_overlay' => 'from-black/10 via-black/25 to-black/70',
            'intro_label' => 'Undangan yang ringkas dan hangat',
        ],
        'floral-luxury' => [
            'label' => 'Floral Luxury',
            'cover_label' => 'A Floral Celebration',
            'default_theme' => ['color' => '#b87885', 'font' => 'Garamond'],
            'main_bg' => '#fff7f8',
            'soft_bg' => '#f8ecef',
            'section_bg' => '#ffffff',
            'dark_bg' => '#35242a',
            'hero_overlay' => 'from-[#3a1722]/20 via-[#3a1722]/35 to-[#1f1015]/85',
            'intro_label' => 'Dengan cinta dan doa',
        ],
        'islamic-soft' => [
            'label' => 'Islamic Soft',
            'cover_label' => 'Walimatul Ursy',
            'default_theme' => ['color' => '#71816d', 'font' => 'Georgia'],
            'main_bg' => '#f6f8f1',
            'soft_bg' => '#edf1e5',
            'section_bg' => '#ffffff',
            'dark_bg' => '#223024',
            'hero_overlay' => 'from-[#172017]/20 via-[#172017]/35 to-[#111911]/85',
            'intro_label' => 'Bismillahirrahmanirrahim',
        ],
        'birthday-fun' => [
            'label' => 'Birthday Fun',
            'cover_label' => 'Birthday Invitation',
            'default_theme' => ['color' => '#d08b6b', 'font' => 'Trebuchet MS'],
            'main_bg' => '#fff8f2',
            'soft_bg' => '#fde9dc',
            'section_bg' => '#ffffff',
            'dark_bg' => '#3a2a24',
            'hero_overlay' => 'from-[#3b2418]/10 via-[#3b2418]/28 to-[#25140d]/78',
            'intro_label' => 'Saatnya merayakan',
        ],
        'corporate-event' => [
            'label' => 'Corporate Event',
            'cover_label' => 'Event Invitation',
            'default_theme' => ['color' => '#4f6873', 'font' => 'Verdana'],
            'main_bg' => '#f4f7f8',
            'soft_bg' => '#e8eef1',
            'section_bg' => '#ffffff',
            'dark_bg' => '#1f3037',
            'hero_overlay' => 'from-[#102129]/10 via-[#102129]/28 to-[#0e1a20]/78',
            'intro_label' => 'Undangan resmi',
        ],
    ],

    'share_message' => "Assalamu'alaikum Warahmatullahi Wabarakatuh.\n\nDengan penuh rasa syukur, kami mengundang Bapak/Ibu/Saudara/i {nama_tamu} untuk menghadiri acara kami:\n\n{judul_undangan}\nTanggal: {tanggal}\nLokasi: {lokasi}\n\nBuka undangan:\n{link_undangan}\n\nMerupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir.",
];
