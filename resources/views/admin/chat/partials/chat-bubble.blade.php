@foreach($messages as $msg)
    @if($msg->message !== '[SYSTEM_INIT]')
    <div class="{{ $msg->sender_type === 'user' ? 'flex justify-start' : 'flex justify-end' }}">
        <div class="max-w-[80%] rounded-2xl px-4 py-3 shadow-sm
            @if($msg->sender_type === 'user') 
                bg-gray-100 text-gray-800 rounded-bl-md
            @elseif($msg->sender_type === 'chatbot')
                bg-blue-100 text-gray-800 rounded-br-md border border-blue-200
            @else
                bg-blue-500 text-white rounded-br-md
            @endif">
            
            {{-- Sender label --}}
            <div class="flex items-center gap-1 mb-1">
                @if($msg->sender_type === 'user')
                    <span class="text-sm">👤</span>
                    <span class="text-xs font-semibold text-gray-600">Pelanggan</span>
                @elseif($msg->sender_type === 'chatbot')
                    <span class="text-sm">🤖</span>
                    <span class="text-xs font-semibold text-blue-600">Bot</span>
                @else
                    <span class="text-sm">👨‍💼</span>
                    <span class="text-xs font-semibold text-white/80">Admin</span>
                @endif
            </div>
            
            {{-- Message content --}}
            <p class="whitespace-pre-wrap text-sm">{{ $msg->message }}</p>
            
            {{-- Timestamp --}}
            <div class="flex items-center justify-end gap-1 mt-1">
                <span class="text-xs {{ $msg->sender_type === 'admin' ? 'text-white/70' : 'text-gray-400' }}">
                    {{ $msg->created_at->format('H:i') }}
                </span>
                @if($msg->sender_type !== 'user' && $msg->is_read)
                    <svg class="w-4 h-4 {{ $msg->sender_type === 'admin' ? 'text-white/70' : 'text-blue-400' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                @endif
            </div>
        </div>
    </div>
    @endif
@endforeach

@if($messages->isEmpty())
<div class="text-center py-8 text-gray-500">
    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
    </svg>
    <p>Belum ada pesan dalam percakapan ini</p>
</div>
@endif
