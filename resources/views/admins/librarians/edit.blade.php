@extends('admins.layouts.index')

@section('content')
    <div class="w-full px-4 md:px-0 md:mt-8 mb-16 text-gray-800 leading-normal">
        <section class="container px-4 mx-auto">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Edit Akun Admin / Pustakawan</h2>
            <form action="/dashboard/librarians" method="POST" class="w-full flex flex-col mt-5 gap-4 max-w-6xl mx-auto">
                @csrf
                <div class="flex flex-col gap-2 text-white">
                    <label for="f_nama" class="font-medium text-lg">Nama</label>
                    <input type="text" name="f_nama" id="f_nama" class="bg-gray-950 py-2 px-3 rounded-md"
                        placeholder="Nama" value="{{ old('f_nama', $librarian->f_nama) }}">
                    @error('f_nama')
                        <p class="text-red-500 px-3 text-xs">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex flex-col gap-2 text-white">
                    <label for="f_username" class="font-medium text-lg">Username</label>
                    <input type="text" name="f_username" id="f_username" class="bg-gray-950 py-2 px-3 rounded-md"
                        placeholder="Username" value="{{ old('f_username', $librarian->f_username) }}">
                    @error('f_username')
                        <p class="text-red-500 px-3 text-xs">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex flex-col gap-2 text-white">
                    <label for="f_level" class="font-medium text-lg">Level</label>
                    <select name="f_level" id="f_level" class="bg-gray-950 py-2 px-3 rounded-md">
                        <option value="0" disabled selected>-- Pilih Level</option>
                        <option value="Admin" {{ $librarian->f_level == 'Admin' ? 'selected' : '' }}>Admin</option>
                        <option value="Pustakawan" {{ $librarian->f_level == 'Pustakawan' ? 'selected' : '' }}>Pustakawan
                        </option>
                    </select>
                    @error('f_level')
                        <p class="text-red-500 px-3 text-xs">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex flex-col gap-2 text-white">
                    <label for="f_status" class="font-medium text-lg">Status</label>
                    <select name="f_status" id="f_status" class="bg-gray-950 py-2 px-3 rounded-md">
                        <option value="0" disabled selected>-- Pilih Status</option>
                        <option value="Aktif" {{ $librarian->f_status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Tidak Aktif" {{ $librarian->f_status == 'Tidak Aktif' ? 'selected' : '' }}>Tidak
                            Aktif</option>
                    </select>
                    @error('f_status')
                        <p class="text-red-500 px-3 text-xs">{{ $message }}</p>
                    @enderror
                </div>
                <x-button text="Edit" />
            </form>

        </section>
    </div>
@endsection

@section('script')
    <script>
        let showHiddenBtns = document.querySelectorAll('.showhidden');
        showHiddenBtns.forEach((showHiddenBtn) => {
            showHiddenBtn.addEventListener('click', function() {
                let input = this.previousElementSibling;
                if (input.type == "text") {
                    input.type = "password";
                    this.innerHTML = "show";
                } else {
                    input.type = "text";
                    this.innerHTML = "hide";
                }
            });
        });
    </script>
@endsection
