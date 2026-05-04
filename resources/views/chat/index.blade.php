@extends('layouts.app')
@section('title', 'Chat')
@section('page_title', 'Chat')

@section('content')
    <div class="card-white p-0 overflow-hidden">
        <div class="grid md:grid-cols-[280px_1fr] min-h-[600px]">
            <aside class="border-r border-bordered bg-white">
                <div class="px-4 py-3 border-b border-bordered">
                    <p class="text-sm font-semibold">Percakapan</p>
                </div>
                <div class="overflow-y-auto max-h-[560px]">
                    @forelse ($contacts as $c)
                        <a href="{{ route('chat.show', $c) }}"
                           class="flex items-center gap-3 px-4 py-3 hover:bg-primary-light border-b border-bordered
                                  {{ ($activeChat && $activeChat->id === $c->id) ? 'bg-primary-light' : '' }}">
                            <div class="w-9 h-9 rounded-full bg-primary text-white text-xs flex items-center justify-center font-semibold">
                                {{ $c->initials }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium truncate">{{ $c->name }}</p>
                                <p class="text-xs text-muted truncate">{{ ucfirst($c->role) }}</p>
                            </div>
                            @if ($c->unread_count ?? 0)
                                <span class="px-1.5 py-0.5 rounded-full bg-accent text-white text-[10px] font-bold">{{ $c->unread_count }}</span>
                            @endif
                        </a>
                    @empty
                        <p class="text-sm text-muted text-center py-8 px-4">Belum ada kontak.</p>
                    @endforelse
                </div>
            </aside>

            <section class="flex flex-col">
                @if ($activeChat)
                    <div class="px-5 py-3 border-b border-bordered flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-primary text-white text-xs flex items-center justify-center font-semibold">
                            {{ $activeChat->initials }}
                        </div>
                        <div>
                            <p class="text-sm font-semibold">{{ $activeChat->name }}</p>
                            <p class="text-xs text-muted">{{ ucfirst($activeChat->role) }}</p>
                        </div>
                    </div>
                    <div class="flex-1 overflow-y-auto p-5 space-y-2 bg-bg" style="max-height: 480px;">
                        @forelse ($messages as $m)
                            @if ($m->sender_id === auth()->id())
                                <div class="flex justify-end">
                                    <div class="max-w-[70%] px-4 py-2 rounded-2xl rounded-tr-sm bg-primary text-white text-sm">
                                        {{ $m->message }}
                                        <div class="text-[10px] opacity-80 mt-1 text-right">{{ $m->created_at->format('H:i') }}</div>
                                    </div>
                                </div>
                            @else
                                <div class="flex">
                                    <div class="max-w-[70%] px-4 py-2 rounded-2xl rounded-tl-sm bg-surface text-ink text-sm">
                                        {{ $m->message }}
                                        <div class="text-[10px] text-muted mt-1">{{ $m->created_at->format('H:i') }}</div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <p class="text-center text-sm text-muted py-8">Mulai percakapan dengan mengirim pesan.</p>
                        @endforelse
                    </div>
                    <form method="POST" action="{{ route('chat.store', $activeChat) }}" class="border-t border-bordered p-3 flex gap-2">
                        @csrf
                        <input type="text" name="message" required maxlength="2000" autofocus
                               placeholder="Tulis pesan..." class="input flex-1">
                        <button class="btn-primary">Kirim</button>
                    </form>
                @else
                    <div class="flex-1 flex items-center justify-center text-muted text-sm">
                        Pilih percakapan untuk memulai.
                    </div>
                @endif
            </section>
        </div>
    </div>
@endsection
