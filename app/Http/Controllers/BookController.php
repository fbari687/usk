<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookDetail;
use App\Models\BorrowBook;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admins.books.index', [
            'books' => Book::with(['category', 'detail'])->paginate(5)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admins.books.create', [
            'categories' => Category::orderBy('f_kategori')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedBook = $request->validate([
            'f_idkategori' => 'required|exists:t_kategori,f_id',
            'f_judul' => 'required|unique:t_buku,f_judul',
            'f_pengarang' => 'required',
            'f_penerbit' => 'required',
            'f_deskripsi' => 'required',
            'f_gambar' => 'file|image|max:2048'
        ], [
            'f_judul.unique' => 'Judul Sudah digunakan'
        ]);

        $request->validate([
            'stock' => 'required|numeric'
        ]);

        if ((int)$request->stock <= 0) {
            return back()->withErrors([
                'stock' => 'Stock Tidak Bisa diisi kurang dari 1'
            ]);
        }

        if ($request->f_gambar) {
            $validatedBook['f_gambar'] = $request->file('f_gambar')->store('book-image');
        }

        $book = Book::create($validatedBook);

        if (!$book) {
            return redirect('/dashboard/books')->with('notify', 'Gagal Menambahkan Buku');
        }

        for ($i = 0; $i < (int)$request->stock; $i++) {
            BookDetail::create([
                'f_idbuku' => $book->f_id,
                'f_status' => 'Tersedia'
            ]);
        }

        return redirect('/dashboard/books')->with('notify', 'Berhasil Menambahkan Buku');
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        return view('admins.books.edit', [
            'book' => $book->with(['category', 'detail'])->first(),
            'categories' => Category::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        $rules = [
            'f_idkategori' => 'required|exists:t_kategori,f_id',
            'f_pengarang' => 'required',
            'f_penerbit' => 'required',
            'f_deskripsi' => 'required',
        ];

        if ($book->f_judul == $request->f_judul) {
            $rules['f_judul'] = 'required';
        } else {
            $rules['f_judul'] = 'required|unique:t_buku,f_judul';
        }

        $validated = $request->validate($rules);

        if ($request->remove) {
            if ($book->f_gambar != 'book-image/default.jpg') {
                Storage::delete($request->oldImage);
                $validated['f_gambar'] = 'book-image/default.jpg';
            }
        }

        if ($request->f_gambar) {
            $request->validate([
                'f_gambar' => 'file|image|max:2048'
            ]);
            if ($request->oldImage == 'book-image/default.jpg') {
                $validated['f_gambar'] = $request->file('f_gambar')->store('book-image');
            } else {
                Storage::delete($request->oldImage);
                $validated['f_gambar'] = $request->file('f_gambar')->store('book-image');
            }
        }

        $updatedBook = $book->update($validated);

        if (!$updatedBook) {
            return redirect('/dashboard/books')->with('notify', 'Gagal Mengedit Buku');
        }

        if ($request->stock) {
            $request->validate([
                'stock' => 'numeric'
            ]);

            if (count($book->detail) != count($book->detail->where('f_status', 'Tersedia'))) {
                return redirect("/dashboard/books")->with('notify', 'Tidak Bisa Mengedit Stok Buku yang Tersedianya Tidak Lengkap');
            }
            $newStock = (int)$request->stock - count($book->detail);
            if ($newStock < 0) {
                $detailBook = $book->detail->take(abs($newStock));
                $detailBook->each->delete();
            } else if ($newStock > 0) {
                for ($i = 0; $i < abs($newStock); $i++) {
                    BookDetail::create([
                        'f_idbuku' => $book->f_id,
                        'f_status' => 'Tersedia'
                    ]);
                }
            }
        } else if ((int)$request->stock <= 0) {
            return back()->withErrors([
                'stock' => 'Stock Tidak Bisa diisi kurang dari 1'
            ]);
        }
        return redirect('/dashboard/books')->with('notify', 'Berhasil Mengedit Buku');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        if (count($book->detail) != count($book->detail->where('f_status', 'Tersedia'))) {
            return redirect("/dashboard/books")->with('notify', 'Tidak Bisa Menghapus Buku Yang Stok Tersedianya Tidak Lengkap');
        }

        $deletedBook = $book->delete();
        $deleteB = BorrowBook::whereDoesntHave('detailPeminjaman')->orderBy('f_tanggalpeminjaman', 'desc')->delete();
        if ($deletedBook) {
            return redirect('/dashboard/books')->with('notify', 'Berhasil Menghapus Buku ' . $book->f_judul);
        } else {
            return redirect('/dashboard/books')->with('notify', 'Gagal Menghapus Buku');
        }
    }
}
