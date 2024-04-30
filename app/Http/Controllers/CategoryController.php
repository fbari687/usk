<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BorrowBook;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admins.categories.index', [
            'categories' => Category::orderBy('f_kategori', 'asc')->paginate(5)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("admins.categories.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'f_kategori' => 'required|unique:t_kategori,f_kategori'
        ], [
            'f_kategori.unique' => 'Nama Kategori ini sudah digunakan'
        ]);

        $category = Category::create($validated);

        if (!$category) {
            return redirect('/dashboard/categories')->with('notify', 'Gagal Menambahkan Kategori');
        }
        return redirect("/dashboard/categories")->with('notify', "Berhasil Menambahkan Kategori");
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('admins.categories.edit', [
            'category' => $category
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        if ($request->f_kategori != $category->f_kategori) {
            $validated = $request->validate([
                'f_kategori' => 'required|unique:t_kategori,f_kategori'
            ], [
                'f_kategori.unique' => 'Nama Kategori ini sudah digunakan'
            ]);
        } else {
            $validated = $request->validate([
                'f_kategori' => 'required'
            ]);
        }
        $updatedCategory = $category->update($validated);

        if (!$updatedCategory) {
            return redirect('/dashboard/categories')->with('notify', 'Gagal Mengedit Kategori');
        }
        return redirect('/dashboard/categories')->with('notify', 'Berhasil Mengedit Kategori');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $book = Book::where("f_idkategori", $category->f_id)->count();
        if ($book != 0) {
            return redirect('/dashboard/categories')->with('notify', 'Gagal Menghapus Kategori');
        }

        $deletedCategory = $category->delete();

        $deleteB = BorrowBook::whereDoesntHave('detailPeminjaman')->orderBy('f_tanggalpeminjaman', 'desc')->delete();
        if ($deletedCategory) {
            return redirect('/dashboard/categories')->with('notify', 'Berhasil Menghapus Kategori ' . $category->f_kategori);
        } else {
            return redirect('/dashboard/categories')->with('notify', 'Gagal Menghapus Kategori');
        }
    }
}
