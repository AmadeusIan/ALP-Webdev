<x-app-layout>
    @push('styles')
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=cinzel:400,700|lato:400,700" rel="stylesheet" />
    @endpush

    <div class="min-h-screen bg-stone-50 pb-20">
        
        <div class="bg-black text-white pt-32 pb-24 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>
            
            <div class="max-w-7xl mx-auto relative z-10">
                <div class="flex flex-col md:flex-row justify-between items-end gap-6">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-[0.3em] mb-2">
                            Inventory Management
                        </p>
                        <h2 class="font-serif text-3xl md:text-4xl font-bold text-white uppercase tracking-widest leading-none">
                            Stock Movement
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 relative z-20">
            <div class="bg-white shadow-[0_20px_50px_rgba(0,0,0,0.05)] border border-stone-100">
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-stone-50 border-b border-stone-200">
                            <tr>
                                <th class="py-5 px-6 font-sans text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em]">Timestamp</th>
                                <th class="py-5 px-6 font-sans text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em]">Fabric Item</th>
                                <th class="py-5 px-6 font-sans text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em]">Admin</th>
                                <th class="py-5 px-6 font-sans text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em] text-center">Type</th>
                                <th class="py-5 px-6 font-sans text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em] text-center">Change</th>
                                <th class="py-5 px-6 font-sans text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em]">Notes</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            @forelse($logs as $log)
                                <tr class="group hover:bg-stone-50/50 transition duration-300">
                                    
                                    <td class="py-6 px-6 align-top">
                                        <div class="font-serif text-sm text-gray-900 font-bold">
                                            {{ $log->created_at?->format('d M Y') }}
                                        </div>
                                        <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-1">
                                            {{ $log->created_at?->format('H:i') }}
                                        </div>
                                    </td>

                                    <td class="py-6 px-6 align-top">
                                        @if($log->fabric)
                                            <a href="{{ route('fabrics.show', $log->fabric) }}" class="font-serif text-sm text-gray-900 hover:text-stone-600 border-b border-transparent hover:border-stone-600 transition pb-px">
                                                {{ $log->fabric->name }}
                                            </a>
                                        @else
                                            <span class="text-xs text-red-400 italic">Deleted Fabric</span>
                                        @endif
                                    </td>

                                    <td class="py-6 px-6 align-top">
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-none bg-black text-white flex items-center justify-center text-[10px] font-bold uppercase">
                                                {{ substr($log->user->name ?? '?', 0, 1) }}
                                            </div>
                                            <span class="text-xs text-gray-600 font-medium">{{ $log->user->name ?? 'Unknown' }}</span>
                                        </div>
                                    </td>

                                    <td class="py-6 px-6 align-top text-center">
                                        @php
                                            $badgeStyle = match($log->change_type) {
                                                'restock'    => 'border-green-200 text-green-700 bg-green-50',
                                                'initial'    => 'border-blue-200 text-blue-700 bg-blue-50',
                                                'sold'       => 'border-stone-200 text-stone-600 bg-stone-50',
                                                'damage'     => 'border-red-200 text-red-700 bg-red-50',
                                                'adjustment' => 'border-yellow-200 text-yellow-700 bg-yellow-50',
                                                default      => 'border-gray-200 text-gray-600 bg-gray-50'
                                            };
                                        @endphp
                                        <span class="inline-block px-3 py-1 border {{ $badgeStyle }} text-[10px] font-bold uppercase tracking-wider">
                                            {{ $log->change_type }}
                                        </span>
                                    </td>

                                    <td class="py-6 px-6 align-top text-center">
                                        @if($log->change_amount > 0)
                                            <span class="font-serif text-green-700 font-bold text-sm">+{{ $log->change_amount }} m</span>
                                        @elseif($log->change_amount < 0)
                                            <span class="font-serif text-red-700 font-bold text-sm">{{ $log->change_amount }} m</span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>

                                    <td class="py-6 px-6 align-top">
                                        <p class="text-xs text-gray-500 italic max-w-xs leading-relaxed">
                                            {{ Str::limit($log->note, 50) ?? '-' }}
                                        </p>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-24 text-center">
                                        <div class="flex flex-col items-center justify-center opacity-60">
                                            <div class="w-16 h-16 border border-stone-300 flex items-center justify-center mb-4">
                                                <svg class="w-6 h-6 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            </div>
                                            <p class="text-sm font-bold text-gray-900 uppercase tracking-widest">No Activity Logged</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($logs->hasPages())
                    <div class="px-6 py-6 border-t border-stone-100 bg-stone-50">
                        {{ $logs->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>

    <style>
        .font-serif { font-family: 'Cinzel', serif; }
        .font-sans { font-family: 'Lato', sans-serif; }
    </style>
</x-app-layout>