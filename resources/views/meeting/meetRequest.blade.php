<x-app-layout>
    <x-slot name="header">
<div class="max-w-md mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Request Meeting</h2>

    <!-- Success message -->
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Error messages -->
    @if($errors->any())
        <div class="bg-red-100 text-red-800 p-3 mb-4 rounded">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('meeting-request.store') }}">
        @csrf

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-1">Title</label>
            <input type="text" name="title" placeholder="Meeting Title" 
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                   required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-1">Start Date & Time</label>
            <input type="datetime-local" name="start" 
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                   required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-1">End Date & Time</label>
            <input type="datetime-local" name="end" 
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                   required>
        </div>

        <button type="submit" 
                class="w-full bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 transition">
            Submit
        </button>
    </form>
</div>
</x-slot>
</x-app-layout>
