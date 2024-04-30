@extends('layouts.index')

@section('content')
    <div class="w-full px-4 md:px-0 md:mt-20 self-start mb-16 text-gray-800 leading-normal">
        <section class="container px-4 mx-auto">
            <h2 class="text-lg font-medium text-center text-white">Buku</h2>

            <p class="mt-1 text-sm text-white text-center">List Buku Yang Ada di Perpustakaan
            </p>
            <div class="w-full grid grid-cols-5 gap-4 mt-4">
                @foreach ($books as $book)
                    <a href="/books/{{ $book->f_id }}"
                        class="block max-w-64 bg-gray-300 rounded-md transition duration-150 hover:translate-y-1">
                        <img alt="" src="{{ asset('/storage/' . $book->f_gambar) }}"
                            class="object-cover w-full max-w-64 aspect-[6/9]" />

                        <h3 class="mt-4 text-lg font-bold text-gray-900 sm:text-xl px-2">{{ $book->f_judul }}</h3>

                        <p class="mt-2 max-w-sm text-gray-700 px-2 pb-2">
                            {{ Str::limit($book->f_deskripsi, 150, '...') }}
                        </p>
                    </a>
                @endforeach
            </div>
            {{-- <div class="w-full flex mt-2 items-center gap-2"> --}}
            {{-- <div class="flex items-center justify-start">
            <a href="/dashboard/reports/printtopdf"
                class="bg-red-600 text-white px-3 py-1 flex items-center justify-center rounded-md transition duration-150 hover:bg-red-700">Cetak
                Menjadi PDF</a>
        </div> --}}
            {{-- <div class="flex items-center justify-start">
            <a href="/dashboard/reports/printtocsv"
                class="bg-green-600 text-white px-3 py-1 flex items-center justify-center rounded-md transition duration-150 hover:bg-green-700">Cetak
                Menjadi Excel</a>
        </div> --}}
            {{-- </div> --}}


        </section>
    </div>
@endsection
