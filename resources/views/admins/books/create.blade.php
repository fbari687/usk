@extends('admins.layouts.index')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/selectize.default.min.css') }}">
    <style>
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
@endsection

@section('content')
    <div class="w-full px-4 md:px-0 md:mt-8 mb-16 text-gray-800 leading-normal">
        <section class="container px-4 mx-auto">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Tambah Buku</h2>
            <form action="/dashboard/books" method="POST" class="w-full flex flex-col mt-5 gap-4 max-w-6xl mx-auto"
                enctype="multipart/form-data">
                @csrf
                <div class="flex flex-col gap-2 text-white">
                    <label for="f_judul" class="font-medium text-lg">Judul</label>
                    <input type="text" name="f_judul" id="f_judul" class="bg-gray-950 py-2 px-3 rounded-md"
                        placeholder="Judul" value="{{ old('f_judul') }}">
                    @error('f_judul')
                        <p class="text-red-500 px-3 text-xs">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex flex-col gap-2 text-white">
                    <label for="f_pengarang" class="font-medium text-lg">Pengarang</label>
                    <input type="text" name="f_pengarang" id="f_pengarang" class="bg-gray-950 py-2 px-3 rounded-md"
                        placeholder="Pengarang" value="{{ old('f_pengarang') }}">
                    @error('f_pengarang')
                        <p class="text-red-500 px-3 text-xs">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex flex-col gap-2 text-white">
                    <label for="f_penerbit" class="font-medium text-lg">Penerbit</label>
                    <input type="text" name="f_penerbit" id="f_penerbit" class="bg-gray-950 py-2 px-3 rounded-md"
                        placeholder="Penerbit" value="{{ old('f_penerbit') }}">
                    @error('f_penerbit')
                        <p class="text-red-500 px-3 text-xs">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex flex-col gap-2 text-white">
                    <label for="f_deskripsi" class="font-medium text-lg">Deskripsi</label>
                    <textarea name="f_deskripsi" id="f_deskripsi" class="h-60 px-3 py-2 bg-gray-950 rounded-md" placeholder="Deskripsi">{{ old('f_deskripsi') }}</textarea>
                    @error('f_deskripsi')
                        <p class="text-red-500 px-3 text-xs">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex flex-col gap-2 text-white">
                    <label for="f_idkategori" class="font-medium text-lg">Kategori</label>
                    <select name="f_idkategori" id="f_idkategori" class="bg-gray-950 py-2 px-3 rounded-md">
                        <option value="0" selected disabled>Ketik Nama Kategori...</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->f_id }}"
                                {{ $category->f_id == old('f_idkategori') ? 'selected' : '' }}>{{ $category->f_kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('f_idkategori')
                        <p class="text-red-500 px-3 text-xs">{{ $message }}</p>
                    @enderror
                </div>
                {{-- <div class="flex flex-col gap-2 text-white">
                    <label for="f_status" class="font-medium text-lg">Status</label>
                    <select name="f_status" id="f_status" class="bg-gray-950 py-2 px-3 rounded-md">
                        <option value="Tersedia" {{ 'Tersedia' == old('f_status') ? 'selected' : '' }}>Tersedia</option>
                        <option value="Tidak Tersedia" {{ 'Tidak Tersedia' == old('f_status') ? 'selected' : '' }}>Tidak
                            Tersedia</option>
                    </select>
                    @error('f_status')
                        <p class="text-red-500 px-3 text-xs">{{ $message }}</p>
                    @enderror
                </div> --}}
                <div class="flex flex-col gap-2 text-white">
                    <label for="stock" class="font-medium text-lg">Stok</label>
                    <input type="number" name="stock" id="stock" class="bg-gray-950 py-2 px-3 rounded-md">
                    @error('stock')
                        <p class="text-red-500 px-3 text-xs">{{ $message }}</p>
                    @enderror
                    <p class="text-xs px-3">*Isi dengan angka</p>
                    <p class="text-xs px-3">*Stok Tidak Boleh Dibawah 1</p>
                </div>
                <div class="flex flex-col gap-2 text-white">
                    <h3 class="font-medium text-lg">Cover Buku</h3>
                    <div class="flex gap-2 items-start">
                        <label for="f_gambar"
                            class="bg-blue-600 w-fit px-2 py-2 cursor-pointer transition duration-150 hover:bg-blue-700 rounded-md">Upload
                            Foto</label>
                        <img src="{{ asset('img/No Cover.jpg') }}" alt="" id="preview_img" class="w-32">
                        <button type="button"
                            class="bg-red-600 w-fit px-2 py-2 cursor-pointer transition duration-150 hover:bg-red-700 rounded-md"
                            onclick="removeImage(this)">Hapus
                            Gambar</button>
                        <input type="file" name="f_gambar" id="f_gambar" onchange="loadFile(event)"
                            class="bg-gray-950 py-2 px-3 rounded-md hidden">
                    </div>
                    <p class="text-sm font-bold">*Ukuran maks 2 mb</p>
                    <p class="text-sm font-bold">*Tidak Wajib</p>
                </div>
                <x-button text="Tambah" />
            </form>

        </section>
    </div>
@endsection

@section('script')
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="{{ asset('js/selectize.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#f_idkategori').selectize({
                searchField: ['text']
            });
        });
        let loadFile = function(event) {

            let input = event.target;
            let file = input.files[0];
            let type = file.type;

            let output = document.getElementById('preview_img');

            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src) // free memory
            }
        };

        let removeImage = function(e) {
            let img = e.previousElementSibling;
            let input = e.nextElementSibling;
            img.src = "{{ asset('img/No Cover.jpg') }}"
            input.value = '';
        };
    </script>
@endsection
