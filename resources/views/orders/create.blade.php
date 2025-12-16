<x-app-layout>

    @if ($errors->any())
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
            <strong class="font-bold">Whoops! Ada kesalahan input:</strong>
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="pt-32 pb-20 bg-gray-50 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-10 text-center">
                <h1 class="font-serif text-3xl font-bold text-gray-900 uppercase tracking-wide">Create Booking</h1>
                <div class="w-12 h-0.5 bg-black mx-auto mt-4 mb-2"></div>
                <p class="text-gray-500 text-sm">Select venue and configure your decoration needs</p>
            </div>

            <form action="{{ route('orders.store') }}" method="POST" id="bookingForm">
                @csrf

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 mb-8">
                    <h3 class="font-serif text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <span
                            class="w-6 h-6 rounded-full bg-black text-white flex items-center justify-center text-xs">1</span>
                        Time & Location
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Start
                                Date</label>
                            <input type="date" name="start_date" id="start_date" required min="{{ date('Y-m-d') }}"
                                class="w-full rounded-lg border-gray-300 focus:border-black focus:ring-black transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">End
                                Date</label>
                            <input type="date" name="end_date" id="end_date" required min="{{ date('Y-m-d') }}"
                                class="w-full rounded-lg border-gray-300 focus:border-black focus:ring-black transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Select
                                Venue</label>
                            <select id="venueSelect"
                                class="w-full rounded-lg border-gray-300 focus:border-black focus:ring-black transition font-bold text-gray-900">
                                <option value="" selected disabled>-- Choose Hotel/Place --</option>
                                @foreach ($venues as $venue)
                                    <option value="{{ $venue->id }}">{{ $venue->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div id="areaSection"
                    class="hidden bg-white rounded-xl shadow-sm border border-gray-100 p-8 mb-8 transition-all duration-500">
                    <h3 class="font-serif text-lg font-bold text-gray-900 mb-2 flex items-center gap-2">
                        <span
                            class="w-6 h-6 rounded-full bg-black text-white flex items-center justify-center text-xs">2</span>
                        Configure Areas
                    </h3>
                    <p class="text-sm text-gray-500 mb-6 ml-8">Check the areas you want to decorate, then select the
                        specific room and fabric.</p>

                    <div id="dynamicAreaList" class="space-y-6"></div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 mb-8">
                    <h3 class="font-serif text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <span
                            class="w-6 h-6 rounded-full bg-black text-white flex items-center justify-center text-xs">3</span>
                        Additional Info
                    </h3>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Add-on Details
                            (Optional)</label>
                        <textarea name="add_on_detail" rows="3"
                            placeholder="E.g., Tambahan bunga di meja penerima tamu, lighting tambahan..."
                            class="w-full rounded-lg border-gray-300 focus:border-black focus:ring-black transition"></textarea>
                    </div>

                    <div class="mt-4">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">General Note
                            (Optional)</label>
                        <textarea name="note" rows="2" placeholder="Special instructions for the team..."
                            class="w-full rounded-lg border-gray-300 focus:border-black focus:ring-black transition"></textarea>
                    </div>
                </div>

                <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4 md:px-8 shadow-lg z-50">
                    <div class="max-w-5xl mx-auto flex justify-between items-center">
                        <div class="hidden md:block">
                            <p class="text-xs text-gray-400 uppercase">Booking Status</p>
                            <p class="font-bold text-gray-900">Drafting...</p>
                        </div>
                        <div class="flex gap-4 w-full md:w-auto">
                            <a href="{{ route('orders.index') }}"
                                class="px-6 py-3 border border-gray-300 rounded-lg font-bold text-gray-600 hover:bg-gray-50 w-full md:w-auto text-center">
                                Cancel
                            </a>
                            <button type="submit"
                                class="px-8 py-3 bg-black text-white rounded-lg font-bold hover:bg-gray-800 transition shadow-lg w-full md:w-auto flex items-center justify-center gap-2">
                                <span>Create Booking</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="h-20"></div>

            </form>
        </div>
    </div>

    <script>
        // 1. Terima Data dari Controller (Venues & Fabrics)
        const venuesData = @json($venues);
        const fabricsData = @json($fabrics);
        const selectedVenueId = "{{ $selectedVenueId ?? '' }}";
        const venueSelect = document.getElementById('venueSelect');
        const areaSection = document.getElementById('areaSection');
        const dynamicAreaList = document.getElementById('dynamicAreaList');

        if (selectedVenueId) {
            // 1. Isi Dropdown secara otomatis
            venueSelect.value = selectedVenueId;

            // 3. Trigger event change secara otomatis
            // Kita menggunakan setTimeout sedikit agar DOM punya waktu untuk render
            setTimeout(() => {
                venueSelect.dispatchEvent(new Event('change'));
            }, 50);
        }

        // 2. Event Listener saat Venue dipilih
        venueSelect.addEventListener('change', function() {
            const selectedVenueId = this.value;
            const venue = venuesData.find(v => v.id == selectedVenueId);

            // Reset List
            dynamicAreaList.innerHTML = '';

            if (venue && venue.areas.length > 0) {
                areaSection.classList.remove('hidden');

                // Loop setiap Area (Ballroom, Meeting Room, dll)
                venue.areas.forEach((area, index) => {
                    const areaCard = createAreaCard(area, index);
                    dynamicAreaList.appendChild(areaCard);
                });
            } else {
                areaSection.classList.add('hidden');
                alert('No areas configuration found for this venue.');
            }
        });

        // 3. Fungsi Membuat HTML per Area (Checkbox + Hidden Inputs)
        function createAreaCard(area, index) {
            const wrapper = document.createElement('div');
            wrapper.className = "border border-gray-200 rounded-lg p-4 hover:border-black transition bg-gray-50/50";

            // Header: Checkbox & Nama Area
            const header = document.createElement('div');
            header.className = "flex items-center gap-3 mb-2 cursor-pointer";

            const checkbox = document.createElement('input');
            checkbox.type = "checkbox";
            checkbox.id = `area_check_${area.id}`;
            checkbox.className = "w-5 h-5 text-black border-gray-300 rounded focus:ring-black";

            const label = document.createElement('label');
            label.htmlFor = `area_check_${area.id}`;
            label.className = "font-bold text-gray-900 cursor-pointer select-none";
            label.innerText = area.name; // e.g., "Grand Ballroom"

            header.appendChild(checkbox);
            header.appendChild(label);
            wrapper.appendChild(header);

            // Content: Dropdown Room & Fabric (Hidden by default)
            const content = document.createElement('div');
            content.id = `content_area_${area.id}`;
            content.className = "hidden mt-4 pl-8 grid grid-cols-1 md:grid-cols-3 gap-4 border-t border-gray-200 pt-4";

            // A. Dropdown Specific Room (e.g., Ballroom 1, Ballroom 2)
            const roomDiv = document.createElement('div');
            roomDiv.innerHTML =
                `<label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Select Room</label>`;
            const roomSelect = document.createElement('select');
            roomSelect.name = `items[${index}][venue_room_id]`; // Name Array Index
            roomSelect.className = "w-full text-sm rounded-md border-gray-300 focus:border-black focus:ring-black";
            roomSelect.disabled = true; // Disabled biar gak ke-submit kalau gak dicentang

            // Isi opsi Room
            area.rooms.forEach(room => {
                const opt = document.createElement('option');
                opt.value = room.id;
                opt.innerText = room.name;
                roomSelect.appendChild(opt);
            });
            roomDiv.appendChild(roomSelect);

            // B. Dropdown Fabric (Kain)
            const fabricDiv = document.createElement('div');
            fabricDiv.innerHTML =
                `<label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Select Fabric</label>`;
            const fabricSelect = document.createElement('select');
            fabricSelect.name = `items[${index}][fabric_id]`;
            fabricSelect.className = "w-full text-sm rounded-md border-gray-300 focus:border-black focus:ring-black";
            fabricSelect.disabled = true;

            fabricsData.forEach(fabric => {
                const opt = document.createElement('option');
                opt.value = fabric.id;
                // Format: Nama Kain - Warna (Sisa Stok)
                opt.innerText = `${fabric.name} - ${fabric.color} (Stock: ${fabric.stock_meter}m)`;
                fabricSelect.appendChild(opt);
            });
            fabricDiv.appendChild(fabricSelect);

            // C. Input Quantity
            const qtyDiv = document.createElement('div');
            qtyDiv.innerHTML =
                `<label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Quantity (Meters)</label>`;
            const qtyInput = document.createElement('input');
            qtyInput.type = "number";
            qtyInput.name = `items[${index}][quantity]`;
            qtyInput.value = 1;
            qtyInput.min = 1;
            qtyInput.className = "w-full text-sm rounded-md border-gray-300 focus:border-black focus:ring-black";
            qtyInput.disabled = true;
            qtyDiv.appendChild(qtyInput);

            // Gabungkan Input ke Content
            content.appendChild(roomDiv);
            content.appendChild(fabricDiv);
            content.appendChild(qtyDiv);
            wrapper.appendChild(content);

            // 4. Logika Toggle Checkbox
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    content.classList.remove('hidden');
                    wrapper.classList.add('border-black', 'bg-white', 'shadow-md');
                    wrapper.classList.remove('border-gray-200', 'bg-gray-50/50');

                    // Aktifkan Input agar terkirim ke server
                    roomSelect.disabled = false;
                    fabricSelect.disabled = false;
                    qtyInput.disabled = false;
                } else {
                    content.classList.add('hidden');
                    wrapper.classList.remove('border-black', 'bg-white', 'shadow-md');
                    wrapper.classList.add('border-gray-200', 'bg-gray-50/50');

                    // Matikan Input agar TIDAK terkirim
                    roomSelect.disabled = true;
                    fabricSelect.disabled = true;
                    qtyInput.disabled = true;
                }
            });

            return wrapper;
        }
    </script>
</x-app-layout>
