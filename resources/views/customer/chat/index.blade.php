@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8" x-data="chatApp()" x-init="init()">
    <div class="py-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">💬 Chat dengan N-Kitchen</h1>
                <p class="text-sm text-gray-600">Tanya apa saja, kami siap membantu!</p>
            </div>
            <button @click="clearChat()" 
                    class="text-sm text-red-600 hover:text-red-800 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Hapus Chat
            </button>
        </div>
    </div>

    <!-- Chat Container -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden flex flex-col" style="height: calc(100vh - 200px); min-height: 500px;">
        
        <!-- Chat Header -->
        <div class="px-6 py-4 flex items-center space-x-3 shadow-lg" style="background: linear-gradient(to right, #ea580c, #dc2626);">
            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-md">
                <span class="text-2xl">🍽️</span>
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-lg" style="color: white;">N-Kitchen Support</h3>
                <div class="flex items-center space-x-2">
                    <span class="w-2.5 h-2.5 rounded-full animate-pulse" style="background-color: #4ade80;"></span>
                    <span class="text-sm font-medium" style="color: white;">Online</span>
                </div>
            </div>
        </div>

        <!-- Chat Messages Area -->
        <div id="chat-messages" 
             class="flex-1 overflow-y-auto p-4 space-y-4 bg-gradient-to-b from-gray-50 to-white"
             x-ref="chatMessages">
            
            <!-- Welcome message if no messages -->
            <template x-if="messages.length === 0">
                <div class="text-center py-8">
                    <div class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-4xl">👋</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Selamat Datang!</h3>
                    <p class="text-gray-600 mb-4">Ada yang bisa kami bantu hari ini?</p>
                </div>
            </template>

            <!-- Messages -->
            <template x-for="msg in messages" :key="msg.id">
                <div :class="msg.sender_type === 'user' ? 'flex justify-end' : 'flex justify-start'">
                    <div :class="[
                        'max-w-[80%] rounded-2xl px-4 py-3 shadow-sm',
                        msg.sender_type === 'user' 
                            ? 'bg-orange-500 text-white rounded-br-md' 
                            : msg.sender_type === 'chatbot'
                                ? 'bg-blue-100 text-gray-800 rounded-bl-md border border-blue-200'
                                : 'bg-white text-gray-800 rounded-bl-md border border-gray-200'
                    ]">
                        <!-- Sender label for non-user messages -->
                        <template x-if="msg.sender_type !== 'user'">
                            <div class="flex items-center gap-1 mb-1">
                                <span x-text="msg.sender_type === 'chatbot' ? '🤖' : '👨‍💼'" class="text-sm"></span>
                                <span class="text-xs font-semibold" 
                                      :class="msg.sender_type === 'chatbot' ? 'text-blue-600' : 'text-orange-600'"
                                      x-text="msg.sender_type === 'chatbot' ? 'Bot' : 'Admin'"></span>
                            </div>
                        </template>
                        
                        <!-- Message content -->
                        <p class="whitespace-pre-wrap text-sm" x-text="msg.message"></p>
                        
                        <!-- Timestamp -->
                        <div class="flex items-center justify-end gap-1 mt-1">
                            <span class="text-xs opacity-70" x-text="msg.created_at"></span>
                            <template x-if="msg.sender_type === 'user'">
                                <svg x-show="msg.is_read" class="w-4 h-4 text-blue-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </template>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Typing indicator -->
            <div x-show="isTyping" class="flex justify-start">
                <div class="bg-gray-200 rounded-2xl px-4 py-3 rounded-bl-md">
                    <div class="flex space-x-1">
                        <div class="w-2 h-2 bg-gray-500 rounded-full animate-bounce" style="animation-delay: 0ms"></div>
                        <div class="w-2 h-2 bg-gray-500 rounded-full animate-bounce" style="animation-delay: 150ms"></div>
                        <div class="w-2 h-2 bg-gray-500 rounded-full animate-bounce" style="animation-delay: 300ms"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Replies - Always visible -->
        <div class="px-4 py-3 bg-gray-100 border-t border-gray-200">
            <p class="text-xs text-gray-500 mb-2">💡 Pertanyaan Cepat:</p>
            <div class="flex flex-wrap gap-2">
                @foreach($quickReplies as $reply)
                <button @click="sendMessage('{{ $reply }}')"
                        class="px-3 py-1.5 bg-white border border-orange-300 text-orange-700 rounded-full text-sm font-medium hover:bg-orange-100 hover:border-orange-400 transition-colors shadow-sm">
                    {{ $reply }}
                </button>
                @endforeach
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-4 bg-white border-t border-gray-200">
            <form @submit.prevent="sendMessage()" class="flex items-center space-x-3">
                <div class="flex-1 relative">
                    <input type="text" 
                           x-model="newMessage"
                           @keydown.enter="sendMessage()"
                           placeholder="Ketik pesan..." 
                           class="w-full px-4 py-3 bg-gray-100 text-gray-900 rounded-full focus:outline-none focus:ring-2 focus:ring-orange-500 focus:bg-white transition-all placeholder-gray-500"
                           :disabled="isSending">
                </div>
                <button type="submit" 
                        :disabled="!newMessage.trim() || isSending"
                        class="w-12 h-12 bg-orange-500 hover:bg-orange-600 disabled:bg-gray-300 text-white rounded-full flex items-center justify-center transition-colors shadow-md">
                    <template x-if="!isSending">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </template>
                    <template x-if="isSending">
                        <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </template>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function chatApp() {
    return {
        messages: @json($messages),
        newMessage: '',
        isSending: false,
        isTyping: false,
        lastId: {{ $lastMessageId }},
        pollingInterval: null,

        init() {
            this.scrollToBottom();
            this.startPolling();
        },

        scrollToBottom() {
            this.$nextTick(() => {
                const container = this.$refs.chatMessages;
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            });
        },

        async sendMessage(quickMessage = null) {
            const message = quickMessage || this.newMessage.trim();
            if (!message || this.isSending) return;

            this.isSending = true;
            this.newMessage = '';

            // Optimistic update - add user message immediately
            const tempId = Date.now();
            this.messages.push({
                id: tempId,
                message: message,
                sender_type: 'user',
                created_at: new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }),
                is_read: false 
            });
            this.scrollToBottom();

            // Show typing indicator
            this.isTyping = true;

            try {
                const response = await fetch('{{ route("customer.chat.send") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ message: message })
                });

                const data = await response.json();

                if (data.status === 'success') {
                    // Update temp message with real ID
                    const tempIndex = this.messages.findIndex(m => m.id === tempId);
                    if (tempIndex !== -1 && data.user_message) {
                        this.messages[tempIndex] = {
                            id: data.user_message.id,
                            message: data.user_message.message,
                            sender_type: 'user',
                            created_at: new Date(data.user_message.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }),
                            is_read: false
                        };
                        this.lastId = Math.max(this.lastId, data.user_message.id);
                    }

                    // Add bot response
                    if (data.bot_message) {
                        this.messages.push({
                            id: data.bot_message.id,
                            message: data.bot_message.message,
                            sender_type: data.bot_message.sender_type,
                            created_at: new Date(data.bot_message.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }),
                            is_read: false
                        });
                        this.lastId = Math.max(this.lastId, data.bot_message.id);
                    }
                }
            } catch (error) {
                console.error('Send error:', error);
                // Remove temp message on error
                this.messages = this.messages.filter(m => m.id !== tempId);
            } finally {
                this.isSending = false;
                this.isTyping = false;
                this.scrollToBottom();
            }
        },

        startPolling() {
            this.pollingInterval = setInterval(() => this.fetchNewMessages(), 3000);
        },

        async fetchNewMessages() {
            try {
                const response = await fetch(`{{ route("customer.chat.fetch") }}?last_id=${this.lastId}`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.messages && data.messages.length > 0) {
                    // Filter out messages we already have
                    const newMessages = data.messages.filter(msg => !this.messages.find(m => m.id === msg.id));
                    
                    if (newMessages.length > 0) {
                        this.messages.push(...newMessages);
                        this.lastId = data.last_id;
                        this.scrollToBottom();
                    }
                }
            } catch (error) {
                console.error('Fetch error:', error);
            }
        },

        async clearChat() {
            if (!confirm('Hapus semua riwayat chat?')) return;

            try {
                const response = await fetch('{{ route("customer.chat.clear") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                if (data.status === 'success') {
                    this.messages = [];
                    this.lastId = 0;
                }
            } catch (error) {
                console.error('Clear error:', error);
            }
        },

        destroy() {
            if (this.pollingInterval) {
                clearInterval(this.pollingInterval);
            }
        }
    }
}
</script>
@endsection
