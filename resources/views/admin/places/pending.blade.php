<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengajuan Lokasi – Pending') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @forelse($places as $place)
                <div class="bg-white shadow rounded p-4 mb-4">
                    <h3 class="text-lg font-semibold">{{ $place->name }}</h3>
                    <p class="text-sm text-gray-600">Kategori: {{ $place->category }}</p>
                    <p class="text-sm text-gray-600">Deskripsi: {{ $place->description ?? '-' }}</p>
                    <p class="text-sm text-gray-600">Koordinat: {{ $place->latitude }}, {{ $place->longitude }}</p>
                    @if($place->image)
                        <img src="{{ asset('storage/'.$place->image) }}" class="h-32 mt-2 rounded object-cover" alt="Foto {{ $place->name }}">
                    @endif
                    <div class="mt-4 flex gap-2">
                        <form method="POST" action="{{ route('places.approve', $place) }}" class="inline">
                            @csrf
                            <button type="submit" class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white rounded text-sm font-medium transition">Setuju</button>
                        </form>
                        <form method="POST" action="{{ route('places.reject', $place) }}" class="inline">
                            @csrf
                            <button type="submit" class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded text-sm font-medium transition">Tolak</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="bg-white shadow rounded p-6 text-center text-gray-500">
                    Tidak ada pengajuan lokasi yang menunggu verifikasi.
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
