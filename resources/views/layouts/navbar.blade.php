@php $user = auth()->user(); @endphp
<header class="h-16 bg-white dark:bg-[#1A1F1B] border-b border-bordered dark:border-[#2A332C] px-3 sm:px-6 lg:px-8 flex items-center justify-between sticky top-0 z-20">
    <div class="flex items-center gap-2 sm:gap-3 min-w-0 flex-1">
        <button id="sidebar-toggle" type="button"
                class="lg:hidden p-2 -ml-1 rounded-lg hover:bg-primary-light text-ink shrink-0"
                aria-label="Buka menu">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 6h18M3 12h18M3 18h18"/>
            </svg>
        </button>
        <h1 class="text-base sm:text-lg font-semibold text-ink truncate">@yield('page_title', 'DIGILAP MBG')</h1>
    </div>

    <div class="flex items-center gap-2 sm:gap-3 shrink-0">
        <button id="dark-mode-toggle" type="button"
                onclick="toggleDarkMode()"
                class="p-2 rounded-lg hover:bg-primary-light dark:hover:bg-[#1A2E23] text-ink min-h-[44px] min-w-[44px] flex items-center justify-center transition-colors"
                title="Mode Gelap"
                aria-label="Toggle dark mode">
            <svg id="dark-icon-moon" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
            </svg>
            <svg id="dark-icon-sun" class="hidden" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="5"/>
                <path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
            </svg>
        </button>

        <div class="relative" id="notif-wrapper">
            <button id="notif-bell" type="button"
                    class="relative p-2 rounded-lg hover:bg-primary-light text-ink min-h-[44px] min-w-[44px] flex items-center justify-center"
                    aria-label="Notifikasi">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path d="M13.73 21a2 2 0 01-3.46 0"/>
                </svg>
                <span id="notif-badge"
                      class="hidden absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] px-1 rounded-full bg-danger text-white text-[10px] font-bold flex items-center justify-center">
                    0
                </span>
                <span id="notif-pulse"
                      class="hidden absolute -top-0.5 -right-0.5 w-[18px] h-[18px] rounded-full bg-danger opacity-60 animate-ping"></span>
            </button>

            <div id="notif-dropdown"
                 class="hidden absolute right-0 mt-2 w-[320px] sm:w-[360px] bg-white dark:bg-[#1A1F1B] border border-bordered dark:border-[#2A332C] rounded-xl shadow-lg z-30 max-w-[calc(100vw-1.5rem)]">
                <div class="flex items-center justify-between px-4 py-3 border-b border-bordered dark:border-[#2A332C]">
                    <p class="text-sm font-semibold text-ink">Notifikasi</p>
                    <button id="notif-mark-all" type="button"
                            class="text-xs text-primary hover:underline font-medium">
                        Tandai semua dibaca
                    </button>
                </div>
                <div id="notif-list" class="max-h-80 overflow-y-auto">
                    <p id="notif-empty" class="text-sm text-muted text-center py-8 px-4">
                        Tidak ada notifikasi baru
                    </p>
                </div>
                <div class="border-t border-bordered dark:border-[#2A332C] px-4 py-2.5 text-center">
                    <a href="{{ route('chat.index') }}" class="text-xs text-primary hover:underline font-medium">
                        Lihat semua
                    </a>
                </div>
            </div>
        </div>

        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 group">
            <div class="text-right hidden sm:block">
                <div class="text-sm font-semibold text-ink leading-tight group-hover:text-primary">{{ $user->name }}</div>
                <div class="text-[11px] text-muted leading-tight uppercase">{{ $user->role }}</div>
            </div>
            <div class="w-9 h-9 rounded-full bg-primary text-white font-semibold flex items-center justify-center text-sm">
                {{ $user->initials }}
            </div>
        </a>
    </div>
</header>

@push('scripts')
    <script>
        (function () {
            const wrapper = document.getElementById('notif-wrapper');
            const bell = document.getElementById('notif-bell');
            const badge = document.getElementById('notif-badge');
            const pulse = document.getElementById('notif-pulse');
            const dropdown = document.getElementById('notif-dropdown');
            const list = document.getElementById('notif-list');
            const empty = document.getElementById('notif-empty');
            const markAll = document.getElementById('notif-mark-all');
            if (!bell || !dropdown) return;

            const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
            const URL_UNREAD = "{{ route('notifications.unread-count') }}";
            const URL_READ_ALL = "{{ route('notifications.read-all') }}";
            const URL_READ_TPL = "{{ url('/notifications') }}";

            const seenIds = new Set();
            let firstLoad = true;

            const escapeHtml = (s) => String(s).replace(/[&<>"']/g, c => ({
                '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
            }[c]));

            function renderBadge(count) {
                if (count > 0) {
                    badge.textContent = count > 9 ? '9+' : String(count);
                    badge.classList.remove('hidden');
                    pulse.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                    pulse.classList.add('hidden');
                }
            }

            function renderList(items) {
                if (!items || items.length === 0) {
                    list.innerHTML = '';
                    list.appendChild(empty);
                    empty.classList.remove('hidden');
                    return;
                }
                empty.classList.add('hidden');
                list.innerHTML = items.map(n => `
                    <a href="${escapeHtml(n.url)}"
                       data-id="${escapeHtml(n.id)}"
                       class="notif-item block px-4 py-3 border-b border-bordered dark:border-[#2A332C] last:border-b-0 hover:bg-primary-light dark:hover:bg-[#1A2E23] transition">
                        <p class="text-sm text-ink leading-snug">${escapeHtml(n.message)}</p>
                        <p class="text-[11px] text-muted mt-0.5">${escapeHtml(n.time)}</p>
                    </a>
                `).join('');

                list.querySelectorAll('.notif-item').forEach(a => {
                    a.addEventListener('click', (e) => {
                        const id = a.dataset.id;
                        fetch(`${URL_READ_TPL}/${id}/read`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrf,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                        }).catch(() => {});
                    });
                });
            }

            function showBrowserNotification(item) {
                if (!('Notification' in window)) return;
                if (Notification.permission !== 'granted') return;
                try {
                    const n = new Notification('DIGILAP MBG', {
                        body: item.message,
                        icon: '/favicon.ico',
                        tag: item.id,
                    });
                    n.onclick = () => {
                        window.focus();
                        if (item.url && item.url !== '#') window.location.href = item.url;
                        n.close();
                    };
                } catch (_) { /* ignore */ }
            }

            function poll() {
                fetch(URL_UNREAD, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                    credentials: 'same-origin',
                })
                .then(res => res.ok ? res.json() : null)
                .then(data => {
                    if (!data) return;
                    renderBadge(data.count || 0);
                    renderList(data.notifications || []);

                    (data.notifications || []).forEach(item => {
                        if (!seenIds.has(item.id)) {
                            if (!firstLoad) showBrowserNotification(item);
                            seenIds.add(item.id);
                        }
                    });
                    firstLoad = false;
                })
                .catch(() => {});
            }

            bell.addEventListener('click', (e) => {
                e.stopPropagation();
                dropdown.classList.toggle('hidden');
            });

            document.addEventListener('click', (e) => {
                if (!wrapper.contains(e.target)) dropdown.classList.add('hidden');
            });

            markAll.addEventListener('click', (e) => {
                e.stopPropagation();
                fetch(URL_READ_ALL, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                })
                .then(() => {
                    seenIds.clear();
                    firstLoad = true;
                    poll();
                })
                .catch(() => {});
            });

            if ('Notification' in window && Notification.permission === 'default') {
                setTimeout(() => { Notification.requestPermission().catch(() => {}); }, 1500);
            }

            poll();
            setInterval(poll, 15000);
        })();
    </script>
@endpush
