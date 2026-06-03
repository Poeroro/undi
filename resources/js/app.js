document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-copy]').forEach((button) => {
        button.addEventListener('click', async () => {
            await navigator.clipboard.writeText(button.dataset.copy || '');
            const original = button.textContent;
            button.textContent = 'Tersalin';
            window.setTimeout(() => {
                button.textContent = original;
            }, 1400);
        });
    });

    const cover = document.getElementById('invitation-cover');
    const music = document.getElementById('invitation-music');

    document.querySelectorAll('[data-open-invitation]').forEach((button) => {
        button.addEventListener('click', async () => {
            if (cover?.classList.contains('is-opening')) return;

            const openingLabel = button.dataset.openingLabel;
            if (openingLabel) button.textContent = openingLabel;
            button.disabled = true;
            cover?.classList.add('is-opening');

            if (music) {
                try {
                    await music.play();
                } catch {
                    // Browsers may still block autoplay until a second tap.
                }
            }
        });
    });

    document.querySelectorAll('[data-music-toggle]').forEach((button) => {
        button.addEventListener('click', async () => {
            if (! music) return;
            if (music.paused) {
                await music.play();
                button.textContent = '♪';
            } else {
                music.pause();
                button.textContent = 'II';
            }
        });
    });

    document.querySelectorAll('[data-countdown]').forEach((countdown) => {
        const target = Number(countdown.dataset.countdown) * 1000;
        if (! target) return;

        const update = () => {
            const diff = Math.max(0, target - Date.now());
            const days = Math.floor(diff / 86400000);
            const hours = Math.floor((diff % 86400000) / 3600000);
            const minutes = Math.floor((diff % 3600000) / 60000);
            const seconds = Math.floor((diff % 60000) / 1000);

            Object.entries({ days, hours, minutes, seconds }).forEach(([key, value]) => {
                const el = countdown.querySelector(`[data-countdown-part="${key}"]`);
                if (el) el.textContent = String(value).padStart(2, '0');
            });
        };

        update();
        window.setInterval(update, 1000);
    });
});
