@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="adminChatApp()" x-init="init()">
    <div class="py-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">💬 Chat Pelanggan</h1>
                <p class="text-sm text-gray-600">Kelola percakapan dengan pelanggan</p>
            </div>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-500">Total: {{ $conversations->count() }} percakapan</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Conversation List -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="px-4 py-3" style="background: linear-gradient(to right, #ea580c, #dc2626);">
                    <h3 class="font-semibold" style="color: white;">Daftar Percakapan</h3>
                </div>
                
                <div class="divide-y divide-gray-100 max-h-[600px] overflow-y-auto">
                    @forelse($conversations as $conversation)
                    <div @click="selectConversation({{ $conversation->user_id }}, '{{ addslashes($conversation->user->name) }}')"
                         :class="{ 'bg-orange-50 border-l-4 border-orange-500': selectedUserId === {{ $conversation->user_id }} }"
                         class="p-4 hover:bg-gray-50 cursor-pointer transition-colors">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold" style="background: linear-gradient(to bottom right, #fb923c, #f87171);">
                                {{ strtoupper(substr($conversation->user->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <h4 class="font-semibold text-gray-900 truncate">{{ $conversation->user->name }}</h4>
                                    @if($conversation->unread_count > 0)
                                    <span id="badge-{{ $conversation->user_id }}" class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full unread-badge">
                                        {{ $conversation->unread_count }}
                                    </span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-500 truncate">
                                    @if($conversation->lastMessage)
                                        {{ Str::limit($conversation->lastMessage->message, 30) }}
                                    @else
                                        Belum ada pesan
                                    @endif
                                </p>
                                <p class="text-xs text-gray-400">
                                    @if($conversation->last_message_at)
                                        {{ $conversation->last_message_at->diffForHumans() }}
                                    @else
                                        {{ $conversation->created_at->diffForHumans() }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-8 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <p>Belum ada percakapan</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Chat Area -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden flex flex-col" style="height: 650px;">
                <!-- Chat Header -->
                <div class="px-6 py-4 flex items-center justify-between shadow-lg" style="background: linear-gradient(to right, #2563eb, #4f46e5);">
                    <div class="flex items-center space-x-3">
                        <template x-if="selectedUserName">
                            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center font-bold text-lg shadow-md" style="color: #2563eb;" x-text="selectedUserName.charAt(0).toUpperCase()"></div>
                        </template>
                        <template x-if="!selectedUserName">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background-color: rgba(255,255,255,0.3);">
                                <svg class="w-6 h-6" style="color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                            </div>
                        </template>
                        <div>
                            <h3 class="font-bold text-lg" style="color: white;" x-text="selectedUserName || 'Pilih Percakapan'"></h3>
                            <div class="flex items-center space-x-2" x-show="selectedUserId">
                                <span class="w-2.5 h-2.5 rounded-full animate-pulse" style="background-color: #4ade80;"></span>
                                <span class="text-sm font-medium" style="color: white;">Online</span>
                            </div>
                        </div>
                    </div>
                    <button x-show="selectedUserId" 
                            @click="clearChat()"
                            class="text-sm" style="color: rgba(255,255,255,0.7);">
                        🗑️ Hapus Chat
                    </button>
                </div>

                <!-- Messages Area -->
                <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-gradient-to-b from-gray-50 to-white" x-ref="chatMessages">
                    <!-- Loading -->
                    <template x-if="isLoading">
                        <div class="flex justify-center py-8">
                            <svg class="animate-spin h-8 w-8 text-orange-500" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </template>

                    <!-- No conversation selected -->
                    <template x-if="!selectedUserId && !isLoading">
                        <div class="text-center py-16">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900">Pilih Percakapan</h3>
                            <p class="text-gray-500">Klik pada percakapan di sebelah kiri untuk melihat pesan</p>
                        </div>
                    </template>

                    <!-- Chat messages will be rendered here -->
                    <div x-html="chatHtml"></div>
                </div>

                <!-- Input Area -->
                <div class="p-4 bg-gray-100 border-t border-gray-200" x-show="selectedUserId">
                    <form @submit.prevent="sendReply()" class="flex items-center space-x-3">
                        <input type="text" 
                               x-model="replyMessage"
                               placeholder="Ketik balasan..." 
                               class="flex-1 px-4 py-3 bg-white text-gray-900 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all placeholder-gray-500"
                               :disabled="isSending">
                        <button type="submit" 
                                :disabled="!replyMessage.trim() || isSending"
                                class="w-12 h-12 bg-blue-500 hover:bg-blue-600 disabled:bg-gray-300 text-white rounded-full flex items-center justify-center transition-colors shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function adminChatApp() {
    return {
        selectedUserId: null,
        selectedUserName: null,
        chatHtml: '',
        replyMessage: '',
        isLoading: false,
        isSending: false,
        lastId: 0,
        pollingInterval: null,
        userIsScrolling: false,
        scrollTimeout: null,

        init() {
            // Start polling when a conversation is selected
        },

        async selectConversation(userId, userName) {
            this.selectedUserId = userId;
            this.selectedUserName = userName;
            this.isLoading = true;
            this.chatHtml = '';
            this.userIsScrolling = false;
            
            // Clear previous polling
            if (this.pollingInterval) {
                clearInterval(this.pollingInterval);
            }

            // Hide the unread badge immediately
            const badge = document.getElementById(`badge-${userId}`);
            if (badge) {
                badge.style.display = 'none';
            }

            try {
                const response = await fetch(`/admin/message/chat/${userId}`);
                const data = await response.json();
                this.chatHtml = data.html;
                this.scrollToBottom();
                
                // Start polling for this conversation
                this.startPolling();
                
                // Add scroll listener after a short delay
                this.$nextTick(() => {
                    this.addScrollListener();
                });
            } catch (error) {
                console.error('Error loading chat:', error);
                this.chatHtml = '<div class="text-center text-red-500 py-8">Gagal memuat percakapan</div>';
            } finally {
                this.isLoading = false;
            }
        },

        addScrollListener() {
            const container = this.$refs.chatMessages;
            if (!container) return;
            
            container.addEventListener('scroll', () => {
                // Check if user is actively scrolling up
                const isAtBottom = container.scrollHeight - container.scrollTop - container.clientHeight < 100;
                
                if (!isAtBottom) {
                    this.userIsScrolling = true;
                    
                    // Reset the flag after user stops scrolling for 2 seconds
                    if (this.scrollTimeout) {
                        clearTimeout(this.scrollTimeout);
                    }
                    this.scrollTimeout = setTimeout(() => {
                        // Only reset if still not at bottom
                        const stillNotAtBottom = container.scrollHeight - container.scrollTop - container.clientHeight < 100;
                        if (stillNotAtBottom) {
                            this.userIsScrolling = false;
                        }
                    }, 5000);
                } else {
                    this.userIsScrolling = false;
                }
            });
        },

        scrollToBottom() {
            this.$nextTick(() => {
                const container = this.$refs.chatMessages;
                if (container && !this.userIsScrolling) {
                    container.scrollTop = container.scrollHeight;
                }
            });
        },

        async sendReply() {
            if (!this.replyMessage.trim() || !this.selectedUserId || this.isSending) return;

            this.isSending = true;
            const message = this.replyMessage;
            this.replyMessage = '';

            try {
                const response = await fetch('/admin/message/reply', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        user_id: this.selectedUserId,
                        message: message
                    })
                });

                const data = await response.json();
                if (data.status === 'success') {
                    // Reload chat to show new message and scroll to bottom
                    this.userIsScrolling = false;
                    await this.reloadChat();
                }
            } catch (error) {
                console.error('Error sending reply:', error);
                this.replyMessage = message; // Restore message on error
            } finally {
                this.isSending = false;
            }
        },

        async reloadChat() {
            if (!this.selectedUserId) return;
            
            try {
                const response = await fetch(`/admin/message/chat/${this.selectedUserId}`);
                const data = await response.json();
                this.chatHtml = data.html;
                this.scrollToBottom();
            } catch (error) {
                console.error('Error reloading chat:', error);
            }
        },

        startPolling() {
            this.pollingInterval = setInterval(() => {
                if (this.selectedUserId) {
                    this.fetchNewMessages();
                }
            }, 3000);
        },

        async fetchNewMessages() {
            if (!this.selectedUserId) return;

            try {
                const response = await fetch(`/admin/message/fetch/${this.selectedUserId}?last_id=${this.lastId}`);
                const data = await response.json();
                
                if (data.messages && data.messages.length > 0) {
                    // Only reload if there are truly new messages
                    // And don't scroll if user is reading old messages
                    const wasScrolling = this.userIsScrolling;
                    await this.reloadChat();
                    if (wasScrolling) {
                        this.userIsScrolling = true; // Restore the flag
                    }
                }
            } catch (error) {
                console.error('Polling error:', error);
            }
        },

        async clearChat() {
            if (!this.selectedUserId) return;
            if (!confirm('Hapus semua riwayat chat dengan pelanggan ini?')) return;

            try {
                const response = await fetch(`/admin/message/clear/${this.selectedUserId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                if (data.status === 'success') {
                    this.chatHtml = '<div class="text-center text-gray-500 py-8">Chat telah dihapus</div>';
                    this.selectedUserId = null;
                    this.selectedUserName = null;
                    // Reload page to refresh conversation list
                    location.reload();
                }
            } catch (error) {
                console.error('Error clearing chat:', error);
            }
        }
    }
}
</script>
@endsection
