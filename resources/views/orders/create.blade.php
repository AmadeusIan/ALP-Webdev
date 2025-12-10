<x-app-layout>
    <div class="pt-32 pb-12 bg-gray-50 min-h-screen">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-10 text-center">
                <h1 class="font-serif text-3xl font-bold text-gray-900 tracking-wide uppercase">Confirm Reservation</h1>
                <div class="w-12 h-0.5 bg-black mx-auto mt-4 mb-2"></div>
                <p class="text-gray-500 text-sm">Review your booking details below</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-32">
                        <div class="aspect-w-16 aspect-h-9 w-full overflow-hidden rounded-lg bg-gray-100 mb-6 relative group">
                             <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/10 to-purple-500/10"></div>
                             <svg class="w-full h-full text-gray-300 p-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>

                        <h3 class="font-serif text-xl font-bold text-gray-900 mb-1">{{ $fabric->name }}</h3>
                        <p class="text-xs font-bold text-indigo-600 uppercase tracking-wider mb-4">{{ $fabric->category->name }}</p>
                        
                        <div class="flex justify-between items-center border-t border-gray-100 pt-4">
                            <span class="text-sm text-gray-500">Price / Meter / Day</span>
                            <span class="font-bold text-gray-900">Rp {{ number_format($fabric->price_per_meter, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-sm text-gray-500">Stock Available</span>
                            <span class="font-bold text-green-600">{{ $fabric->stock_meter }} meters</span>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                        <div class="p-8">
                            <form action="{{ route('orders.store') }}" method="POST" id="bookingForm">
                                @csrf
                                <input type="hidden" name="fabric_id" value="{{ $fabric->id }}">
                                <input type="hidden" id="pricePerMeter" value="{{ $fabric->price_per_meter }}">

                                <div class="mb-8">
                                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Rental Period</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                                            <input type="date" name="start_date" id="start_date" required
                                                min="{{ date('Y-m-d') }}"
                                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-black focus:ring-black transition">
                                            @error('start_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                                            <input type="date" name="end_date" id="end_date" required
                                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-black focus:ring-black transition">
                                            @error('end_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-8">
                                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Fabric Details</h4>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Quantity Needed (Meters)</label>
                                        <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $fabric->stock_meter }}" required
                                            class="w-full md:w-1/3 rounded-lg border-gray-300 shadow-sm focus:border-black focus:ring-black transition font-bold text-lg">
                                        @error('quantity') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                <div class="mb-8">
                                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Additional Info</h4>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes (Optional)</label>
                                    <textarea name="note" rows="3" placeholder="Special request for packaging, delivery instructions, etc."
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-black focus:ring-black transition"></textarea>
                                </div>

                                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 mb-8">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-gray-600 text-sm">Rental Duration</span>
                                        <span class="font-bold text-gray-900" id="totalDays">1 Day</span>
                                    </div>
                                    <div class="flex justify-between items-center mb-4">
                                        <span class="text-gray-600 text-sm">Quantity</span>
                                        <span class="font-bold text-gray-900"><span id="displayQty">1</span> Meters</span>
                                    </div>
                                    <div class="border-t border-gray-200 pt-4 flex justify-between items-center">
                                        <span class="text-lg font-serif font-bold text-gray-900">Estimated Total</span>
                                        <span class="text-2xl font-bold text-indigo-600" id="grandTotal">Rp 0</span>
                                    </div>
                                </div>

                                <div class="flex gap-4">
                                    <a href="{{ route('fabrics.show', $fabric) }}" 
                                       class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-bold hover:bg-gray-50 transition text-center">
                                        Cancel
                                    </a>
                                    <button type="submit" 
                                        class="flex-1 bg-black text-white px-6 py-3 rounded-lg font-bold hover:bg-gray-800 transition shadow-lg flex justify-center items-center gap-2">
                                        <span>Confirm Booking</span>
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');
            const quantityInput = document.getElementById('quantity');
            const pricePerMeter = parseInt(document.getElementById('pricePerMeter').value);
            
            const totalDaysEl = document.getElementById('totalDays');
            const displayQtyEl = document.getElementById('displayQty');
            const grandTotalEl = document.getElementById('grandTotal');

            function calculateTotal() {
                const start = new Date(startDateInput.value);
                const end = new Date(endDateInput.value);
                const qty = parseInt(quantityInput.value) || 1;

                // Update Quantity Display
                displayQtyEl.innerText = qty;

                if (startDateInput.value && endDateInput.value && start <= end) {
                    // Hitung selisih hari
                    const diffTime = Math.abs(end - start);
                    let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
                    
                    // Jika hari sama (rental 1 hari), hitung 1 hari
                    if (diffDays === 0) diffDays = 1;

                    totalDaysEl.innerText = diffDays + (diffDays === 1 ? ' Day' : ' Days');

                    // Rumus: Harga x Meter x Hari
                    const total = pricePerMeter * qty * diffDays;
                    
                    // Format Rupiah
                    grandTotalEl.innerText = 'Rp ' + total.toLocaleString('id-ID');
                } else {
                    totalDaysEl.innerText = '-';
                    grandTotalEl.innerText = 'Rp 0';
                }
            }

            // Pasang event listener
            startDateInput.addEventListener('change', calculateTotal);
            endDateInput.addEventListener('change', calculateTotal);
            quantityInput.addEventListener('input', calculateTotal);
            
            // Set default date to today for UX
            const today = new Date().toISOString().split('T')[0];
            startDateInput.value = today;
            endDateInput.min = today; 
            
            // Trigger perhitungan awal
            calculateTotal();
        });
    </script>
</x-app-layout>