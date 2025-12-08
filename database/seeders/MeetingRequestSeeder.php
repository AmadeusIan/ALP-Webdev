@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-100 min-h-screen">

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                Meeting Requests
            </h2>
            <!-- <a href="{{ route('meetings.create') }}"  -->
               class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 transition">
                + New Meeting
            </a>
        </div>

        {{-- Grid card --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($requests as $req)
                <div class="bg-white shadow-lg rounded-lg p-6 flex flex-col justify-between hover:shadow-xl transition">
                    
                    {{-- Info Meeting --}}
                    <div>
                        <h3 class="font-bold text-lg mb-2 text-gray-800">{{ $req->title }}</h3>
                        <p class="text-gray-600 text-sm mb-1">
                            Diajukan oleh: <span class="font-semibold">{{ $req->user->name ?? 'User Deleted' }}</span>
                        </p>
                        <p class="text-gray-500 text-xs mb-1">
                            Start: {{ $req->start ? $req->start->format('d M Y H:i') : '-' }}
                        </p>
                        <p class="text-gray-500 text-xs mb-2">
                            End: {{ $req->end ? $req->end->format('d M Y H:i') : '-' }}
                        </p>

                        {{-- Status badge --}}
                        @php
                            if ($req->status == 'pending') {
                                $statusClass = 'bg-yellow-100 text-yellow-800';
                            } elseif ($req->status == 'approved') {
                                $statusClass = 'bg-green-100 text-green-800';
                            } elseif ($req->status == 'rejected') {
                                $statusClass = 'bg-red-100 text-red-800';
                            } else {
                                $statusClass = 'bg-gray-100 text-gray-800';
                            }
                        @endphp
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                            {{ ucfirst($req->status) }}
                        </span>
                    </div>

                    {{-- Tombol Accept / Reject --}}
                    <div class="mt-6 flex gap-2">
                        <form method="POST" action="{{ route('admin.meetings.approve', $req->id) }}" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full bg-green-600 text-white px-3 py-2 rounded shadow hover:bg-green-700 transition">
                                Accept
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.meetings.reject', $req->id) }}" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full bg-red-600 text-white px-3 py-2 rounded shadow hover:bg-red-700 transition">
                                Reject
                            </button>
                        </form>
                    </div>
                </div>

            @empty
                {{-- Placeholder jika kosong --}}
                <div class="bg-white shadow-lg rounded-lg p-6 col-span-full text-center text-gray-500">
                    <div class="h-40 bg-gray-100 flex items-center justify-center rounded mb-4">
                        <span class="text-gray-400 text-lg">No meeting requests yet</span>
                    </div>
                    <!-- <a href="{{ route('meetings.create') }}"  -->
                       class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 transition">
                        Create your first meeting
                    </a>
                </div>
            @endforelse
        </div>

    </div>
</div>
@endsection
