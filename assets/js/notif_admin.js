(function () {
    const badge = document.getElementById('notifPending');
    if (!badge) return;
    let lastCount = -1;

    async function checkPending() {
        try {
            const res = await fetch('notif_pending.php', { credentials: 'same-origin' });
            if (!res.ok) return;
            const data = await res.json();
            if (data.pending > 0) {
                badge.textContent = data.pending;
                badge.classList.remove('hidden');
                if (lastCount !== -1 && data.pending > lastCount) {
                    badge.classList.add('animate-bounce');
                    setTimeout(() => badge.classList.remove('animate-bounce'), 1500);
                    showToast('Order baru: ' + data.latest);
                }
            } else {
                badge.classList.add('hidden');
            }
            lastCount = data.pending;
        } catch (_) {}
    }

    function showToast(msg) {
        const t = document.createElement('div');
        t.className = 'fixed bottom-5 right-5 bg-espresso-900 text-cream-50 px-5 py-3 rounded-lg shadow-lg shadow-espresso-900/40 border border-matcha-700 z-50 text-sm animate-fade-up';
        t.textContent = msg;
        document.body.appendChild(t);
        setTimeout(() => t.remove(), 4000);
    }

    checkPending();
    setInterval(checkPending, 30000);
})();