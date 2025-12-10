<x-app-layout>
    <div class="pt-32 pb-12 bg-white min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex flex-col md:flex-row justify-between items-center mb-10 border-b border-gray-100 pb-6">
                <div>
                    <h1 class="font-serif text-3xl font-bold text-gray-900 tracking-wide">
                        NOTIFICATIONS
                    </h1>
                    <p class="text-sm text-gray-500 font-serif italic mt-1">Updates on your orders and requests.</p>
                </div>

                @if($notifications->count() > 0)
                    <form action="{{ route('notifications.markAllAsRead') }}" method="POST" class="mt-4 md:mt-0">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="text-xs font-bold uppercase tracking-widest text-gray-500 hover:text-black hover:underline transition">
                            Mark All as Read
                        </button>
                    </form>
                @endif
            </div>

            <div class="space-y-4">
                @forelse($notifications as $notification)
                    <div class="group relative flex items-start gap-4 p-6 rounded-lg transition-all duration-300 border border-gray-100 hover:shadow-lg
                        {{ $notification->is_read ? 'bg-gray-50 opacity-75' : 'bg-white border-l-4 border-l-black shadow-sm' }}">
                        
                        <div class="flex-shrink-0 mt-1">
                            @if($notification->type == 'order_info')
                                <div class="w-8 h-8 bg-black text-white flex items-center justify-center rounded-full">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                </div>
                            @else
                                <div class="w-8 h-8 bg-gray-200 text-gray-600 flex items-center justify-center rounded-full">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                </div>
                            @endif
                        </div>

                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <h3 class="text-sm font-bold uppercase tracking-wide text-gray-900">
                                    {{ $notification->title }}
                                </h3>
                                <span class="text-xs text-gray-400 font-serif italic">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <p class="text-gray-600 mt-1 text-sm leading-relaxed font-serif">
                                {{ $notification->message }}
                            </p>
                        </div>

                        <div class="flex items-center gap-3 opacity-0 group-hover:opacity-100 transition-opacity duration-200 absolute top-4 right-4 bg-white/90 p-1 rounded-md shadow-sm sm:static sm:bg-transparent sm:shadow-none">
                            
                            @if(!$notification->is_read)
                                <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" title="Mark as Read" class="text-gray-400 hover:text-green-600 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </button>
                                </form>
                            @endif

                            <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" onsubmit="return confirm('Delete this notification?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Delete" class="text-gray-400 hover:text-red-600 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-20 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                        <div class="inline-block p-4 rounded-full bg-white mb-4 shadow-sm">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 uppercase tracking-widest">All caught up!</h3>
                        <p class="text-gray-500 font-serif italic mt-2">You have no new notifications at the moment.</p>
                    </div>
                @endforelse

                <div class="mt-6">
                    {{ $notifications->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>