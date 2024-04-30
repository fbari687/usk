@extends('admins.layouts.index')

@section('content')
    <div class="w-full px-4 md:px-0 md:mt-8 mb-16 text-white leading-normal">
        <div class="w-full flex flex-col">
            <div class="w-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-gray-900 border border-gray-800 rounded shadow p-2">
                    <div class="flex flex-row items-center">
                        <div class="flex-shrink pr-4">
                            <div class="rounded p-3 bg-green-600"><i class="fa-solid fa-book text-white fa-xl"></i>
                            </div>
                        </div>
                        <div class="flex-1 text-right md:text-center">
                            <h5 class="font-bold uppercase text-gray-400">Total Judul Buku</h5>
                            <h3 class="font-bold text-3xl text-gray-600">{{ $bookCount }}</h3>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-900 border border-gray-800 rounded shadow p-2">
                    <div class="flex flex-row items-center">
                        <div class="flex-shrink pr-4">
                            <div class="rounded p-3 bg-yellow-600"><i class="fa-solid fa-users text-white fa-xl"></i>
                            </div>
                        </div>
                        <div class="flex-1 text-right md:text-center">
                            <h5 class="font-bold uppercase text-gray-400">Total Anggota</h5>
                            <h3 class="font-bold text-3xl text-gray-600">{{ $memberCount }}</h3>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-900 border border-gray-800 rounded shadow p-2">
                    <div class="flex flex-row items-center">
                        <div class="flex-shrink pr-4">
                            <div class="rounded p-3 bg-orange-600"><i class="fa-solid fa-user-plus text-white fa-xl"></i>
                            </div>
                        </div>
                        <div class="flex-1 text-right md:text-center">
                            <h5 class="font-bold uppercase text-gray-400">Total Admin & Pustakawan</h5>
                            <h3 class="font-bold text-3xl text-gray-600">{{ $librarianCount }}</h3>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-900 border border-gray-800 rounded shadow p-2">
                    <div class="flex flex-row items-center">
                        <div class="flex-shrink pr-4">
                            <div class="rounded p-3 bg-violet-600"><i class="fa-solid fa-hand-holding text-white fa-xl"></i>
                            </div>
                        </div>
                        <div class="flex-1 text-right md:text-center">
                            <h5 class="font-bold uppercase text-gray-400">Total Peminjaman</h5>
                            <h3 class="font-bold text-3xl text-gray-600">{{ $borrowCount }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
