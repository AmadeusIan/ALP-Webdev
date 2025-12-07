<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Inventory Logs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <h3 class="text-lg font-bold mb-4">Stock Movement History</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead>
                                <tr class="bg-gray-100 text-gray-600 uppercase text-xs leading-normal">
                                    <th class="py-3 px-6 text-left">Date & Time</th>
                                    <th class="py-3 px-6 text-left">Fabric Name</th>
                                    <th class="py-3 px-6 text-left">Admin / User</th>
                                    <th class="py-3 px-6 text-center">Type</th>
                                    <th class="py-3 px-6 text-center">Amount</th>
                                    <th class="py-3 px-6 text-left">Note</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 text-sm font-light">
                                @forelse($logs as $log)
                                <tr class="border-b border-gray-200 hover:bg-gray-50">
                                    
                                    <td class="py-3 px-6 text-left whitespace-nowrap">
                                        {{ $log->created_at?->format('d M Y, H:i') ?? '-' }}
                                        <span class="block text-xs text-gray-400">{{ $log->created_at?->diffForHumans() ?? '' }}</span>
                                    </td>

                                    <td class="py-3 px-6 text-left font-medium">
                                        @if($log->fabric)
                                            <a href="{{ route('fabrics.show', $log->fabric) }}" class="text-blue-600 hover:underline">
                                                {{ $log->fabric->name }}
                                            </a>
                                        @else
                                            <span class="text-red-400 italic">Deleted Fabric</span>
                                        @endif
                                    </td>

                                    <td class="py-3 px-6 text-left">
                                        <div class="flex items-center">
                                            <div class="mr-2">
                                                <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold">
                                                    {{ substr($log->user->name ?? '?', 0, 1) }}
                                                </div>
                                            </div>
                                            <span>{{ $log->user->name ?? 'Unknown' }}</span>
                                        </div>
                                    </td>

                                    <td class="py-3 px-6 text-center">
                                        @php
                                            $badgeColor = match($log->change_type) {
                                                'restock' => 'bg-green-100 text-green-800',
                                                'initial' => 'bg-blue-100 text-blue-800',
                                                'sold' => 'bg-purple-100 text-purple-800',
                                                'damage' => 'bg-red-100 text-red-800',
                                                'adjustment' => 'bg-yellow-100 text-yellow-800',
                                                default => 'bg-gray-100 text-gray-800'
                                            };
                                        @endphp
                                        <span class="{{ $badgeColor }} py-1 px-3 rounded-full text-xs font-bold uppercase">
                                            {{ $log->change_type }}
                                        </span>
                                    </td>

                                    <td class="py-3 px-6 text-center font-bold">
                                        @if($log->change_amount > 0)
                                            <span class="text-green-600">+{{ $log->change_amount }} m</span>
                                        @elseif($log->change_amount < 0)
                                            <span class="text-red-600">{{ $log->change_amount }} m</span>
                                        @else
                                            <span class="text-gray-400">0</span>
                                        @endif
                                    </td>

                                    <td class="py-3 px-6 text-left italic text-gray-500">
                                        {{Str::limit($log->note, 30) ?? '-' }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-10 text-gray-400">
                                        No logs available yet.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $logs->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>