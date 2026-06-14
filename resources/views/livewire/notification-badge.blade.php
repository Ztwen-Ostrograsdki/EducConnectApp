<div class="dd">
    <button class="h-btn" onclick="toggleDD('notif-menu')">
        🔔
        @if ($unreadCount)
            <span class="h-notif-dot">
                {{ $unreadCount }}
            </span>
        @endif

    </button>
    <div class="dd-menu" id="notif-menu">
        <div class="dd-head">
            <div class="dd-title">Notifications</div>
            <div class="dd-sub">{{ $unreadCount }} non lues</div>
        </div>
        @foreach ($notifications as $notif)
            <div wire:key='notification-badge-{{ $notif['id'] }}' class="notif-item notif-unread @if ($notif['read_at']) opacity-50 @endif">
                <div class="notif-title">📝 {{ cutter($notif['title'], 50) }}
                    @if ($notif['read_at'])
                        <span class="text-xs text-amber-500 italic font-mono">(Déjà lue)</span>
                    @endif
                </div>
                <div class="text-sm text-gray-500">
                    {{ cutter($notif['message'], 100) }}
                </div>
                <div class="notif-time text-sky-500">{{ $notif['created_at'] }}</div>
            </div>
        @endforeach
        <div class="dd-item" style="justify-content:center;color:var(--accent);font-size:.73rem;">
            <a class="w-full text-center hover:underline py-1.5" href="{{ route('tenant.notifications.center') }}">
                Voir toutes →
            </a>
        </div>
    </div>
</div>

