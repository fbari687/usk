<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Book;
use App\Models\BorrowBook;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    public function home()
    {
        return view('home');
    }

    public function login()
    {
        return view('login');
    }

    public function dashboard()
    {
        return view('admins.dashboard', [
            'bookCount' => Book::count(),
            'memberCount' => Member::count(),
            'librarianCount' => Admin::count(),
            'borrowCount' => BorrowBook::whereHas('detailPeminjaman', function ($q) {
                $q->where('f_tanggalkembali', '!=', null);
            })->count()
        ]);
    }

    public function books()
    {
        return view('books', [
            'books' => Book::all()
        ]);
    }

    public function book(Book $book)
    {
        return view('book', [
            'book' => $book
        ]);
    }

    public function history()
    {
        return view('history', [
            'reports' => BorrowBook::where('f_idanggota', Auth::guard('member')->user()->f_id)->get()
        ]);
    }
}
