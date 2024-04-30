@extends('layouts.index')

@section('content')
    <div class="w-full relative h-screen text-white overflow-hidden">
        <div class="absolute inset-0">
            <img src="{{ asset('img/bghome.jpg') }}" alt="Background" class="object-cover object-center h-full w-full">
            <div class="absolute inset-0 bg-black opacity-50"></div>
        </div>

        <div class="relative z-10 flex flex-col items-center justify-center h-full text-center">
            <h2 class="text-3xl lg:text-5xl font-bold leading-tight mb-4">Selamat Datang di Perpustakaan 65</h2>
            <h4 class="text-base lg:text-lg text-gray-300 mb-8">Temukan Buku-buku yang Menarik Disini!</h4>
        </div>
    </div>
@endsection
