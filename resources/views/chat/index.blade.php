@extends('layouts.app')
@section('title', 'Chat')
@section('page_title', 'Chat')

@section('content')
    <div class="card-white p-0 overflow-hidden">
        <div class="grid md:grid-cols-[280px_1fr] min-h-[70vh] md:min-h-[600px]">
            <aside class="border-r border-bordered dark:border-[#2A332C] bg-white dark:bg-[#1A1F1B] {{ $activeChat ? 'hidden md:block' : 'block' }}">
                <div class="px-4 py-3 border-b border-bordered dark:border-[#2A332C]">
                    <p class="text-sm font-semibold">Percakapan</p>
                </div>
                <div class="overflow-y-auto md:max-h-[560px]">
                    @forelse ($contacts as $c)
                        <a href="{{ route('chat.show', $c) }}"
                           class="flex items-center gap-3 px-4 py-3 hover:bg-primary-light dark:hover:bg-[#1A2E23] border-b border-bordered dark:border-[#2A332C]
                                  {{ ($activeChat && $activeChat->id === $c->id) ? 'bg-primary-light dark:bg-[#1A2E23]' : '' }}">
                            <div class="w-9 h-9 rounded-full bg-primary text-white text-xs flex items-center justify-center font-semibold shrink-0">
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

            <section class="flex flex-col {{ $activeChat ? 'flex' : 'hidden md:flex' }}">
                @if ($activeChat)
                    <div class="px-4 sm:px-5 py-3 border-b border-bordered dark:border-[#2A332C] flex items-center gap-3">
                        <a href="{{ route('chat.index') }}"
                           class="md:hidden p-1.5 -ml-1.5 rounded-lg hover:bg-primary-light dark:hover:bg-[#1A2E23] text-muted hover:text-ink"
                           aria-label="Kembali">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M15 18l-6-6 6-6"/>
                            </svg>
                        </a>
                        <div class="w-9 h-9 rounded-full bg-primary text-white text-xs flex items-center justify-center font-semibold shrink-0">
                            {{ $activeChat->initials }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold truncate">{{ $activeChat->name }}</p>
                            <p class="text-xs text-muted truncate">{{ ucfirst($activeChat->role) }}</p>
                        </div>
                    </div>
                    <div class="flex-1 overflow-y-auto p-4 sm:p-5 space-y-2 bg-bg dark:bg-[#0F1410] max-h-[55vh] md:max-h-[480px]">
                        @forelse ($messages as $m)
                            @if ($m->sender_id === auth()->id())
                                <div class="flex justify-end">
                                    <div class="max-w-[80%] md:max-w-[70%] px-4 py-2 rounded-2xl rounded-tr-sm bg-primary text-white text-sm break-words">
                                        {{ $m->message }}
                                        <div class="text-[10px] opacity-80 mt-1 text-right">{{ $m->created_at->format('H:i') }}</div>
                                    </div>
                                </div>
                            @else
                                <div class="flex">
                                    <div class="max-w-[80%] md:max-w-[70%] px-4 py-2 rounded-2xl rounded-tl-sm bg-surface dark:bg-[#2A332C] text-ink dark:text-[#E8EDE9] text-sm break-words">
                                        {{ $m->message }}
                                        <div class="text-[10px] text-muted mt-1">{{ $m->created_at->format('H:i') }}</div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <p class="text-center text-sm text-muted py-8">Mulai percakapan dengan mengirim pesan.</p>
                        @endforelse
                    </div>
                    <form method="POST" action="{{ route('chat.store', $activeChat) }}" class="border-t border-bordered dark:border-[#2A332C] p-3 flex gap-2">
                        @csrf
                        <input type="text" name="message" required maxlength="2000" autofocus
                               placeholder="Tulis pesan..." class="input flex-1 min-w-0">
                        <button class="btn-primary shrink-0">Kirim</button>
                    </form>
                @else
                    <div class="flex-1 flex items-center justify-center text-muted text-sm p-6 text-center">
                        Pilih percakapan untuk memulai.
                    </div>
                @endif
            </section>
        </div>
    </div>
@endsection
