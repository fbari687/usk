@extends('admins.layouts.index')

@section('content')
    <div class="w-full px-4 md:px-0 md:mt-8 mb-16 text-white leading-normal">
        <div class="container px-4 mx-auto">
            <h2 class="font-medium text-lg">Kategori</h2>
            <p class="text-sm text-gray-300">Kumpulan Kategori</p>
            <div class="w-full mt-1 flex items-center justify-end">
                <a href="/dashboard/categories/create"
                    class="bg-green-600 px-3 py-1 flex items-center justify-center rounded-md transition duration-150 hover:bg-green-700">
                    Tambah Kategori
                </a>
            </div>
            <div class="flex flex-col mt-3">
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 md:px-6 lg:px-8">
                        @if ($categories->isEmpty())
                            <div class="text-center text-lg">Belum ada kategori</div>
                        @else
                            <div class="border border-gray-700 md:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-700">
                                    <thead class="bg-gray-800">
                                        <tr>
                                            <th scope="col"
                                                class="py-3.5 px-4 text-sm font-normal text-left rtl:text-right text-gray-400">
                                                No
                                            </th>

                                            <th scope="col"
                                                class="py-3.5 px-4 text-sm font-normal text-left rtl:text-right text-gray-400">
                                                Kategori
                                            </th>

                                            <th scope="col"
                                                class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-400">
                                                Action</th>

                                            <th scope="col" class="relative py-3.5 px-4">
                                                <span class="sr-only">Edit</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-gray-900 divide-y divide-gray-700">
                                        @foreach ($categories as $category)
                                            <tr>
                                                <td class="px-3.5 py-4 text-sm font-medium whitespace-nowrap">
                                                    <div>
                                                        <h2 class="font-medium text-white">{{ $loop->iteration }}</h2>
                                                    </div>
                                                </td>
                                                <td class="px-3.5 py-4 text-sm font-medium whitespace-nowrap">
                                                    <div>
                                                        <h2 class="font-medium text-white">{{ $category->f_kategori }}</h2>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-4 text-sm font-medium whitespace-nowrap text-white">
                                                    <form action="/dashboard/categories/{{ $category->f_id }}"
                                                        method="POST" class="flex items-center justify-start gap-3">
                                                        @csrf
                                                        @method('DELETE')
                                                        <a href="/dashboard/categories/{{ $category->f_id }}/edit"
                                                            class="bg-blue-600 px-2 py-1 rounded-md flex items-center justify-center transition duration-150 hover:bg-blue-700">Edit</a>
                                                        <button type="submit"
                                                            class="bg-red-600 px-2 py-1 rounded-md flex items-center justify-center transition duration-150 hover:bg-red-700"
                                                            onclick="return confirm('Yakin ingin menghapus kategori {{ $category->f_kategori }}')">Delete</button>
                                                    </form>
                                                </td>
                                                <td scope="col" class="relative py-3.5 px-4">
                                                    <span class="sr-only">Edit</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="w-full mt-4 flex items-center justify-center gap-2">
                                {{ $categories->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @if (session()->has('notify'))
        <script>
            alert('{{ session('notify') }}');
        </script>
    @endif
@endsection
