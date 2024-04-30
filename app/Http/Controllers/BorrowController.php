<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookDetail;
use App\Models\BorrowBook;
use App\Models\BorrowBookDetail;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BorrowController extends Controller
{
    public function index()
    {
        return view('admins.borrows.index', [
            'books' => Book::with(['detail'])->orderBy('f_judul', 'asc')->get(),
            'members' => Member::orderBy('f_nama', 'asc')->get()
        ]);
    }

    public function borrow(Request $request)
    {
        $validated = $request->validate([
            'f_idanggota' => 'required|exists:t_anggota,f_id',
            'f_idbuku' => 'required|exists:t_buku,f_id'
        ]);

        $book = Book::where('f_id', $validated['f_idbuku'])->first();

        if (count($book->detail->where('f_status', 'Tersedia')) == 0) {
            return redirect('/dashboard/borrows')->with('notify', 'Gagal Melakukan Peminjaman (Buku Tidak Tersedia)');
        }
        $bookDetail = BookDetail::where('f_idbuku', $book->f_id)->where('f_status', 'Tersedia')->first();

        $member = Member::where("f_id", $validated['f_idanggota'])->first();

        $peminjaman = BorrowBook::where('f_idanggota', $validated['f_idanggota'])->whereHas('detailPeminjaman', function ($q) {
            $q->where('f_tanggalkembali', null);
        })->get();

        $bukuSama = BorrowBook::where('f_idanggota', $validated['f_idanggota'])->whereHas('detailPeminjaman', function ($q) use ($book) {
            $q->where('f_tanggalkembali', null)->whereHas('detailBook', function ($w) use ($book) {
                $w->where('f_idbuku', $book->f_id);
            });
        })->get();

        if ($bukuSama->isNotEmpty()) {
            return redirect('/dashboard/borrows')->with('notify', 'Gagal Melakukan Peminjaman (Tidak Bisa meminjam buku yang sama dengan yang sedang dipinjam)');
        }

        if (count($peminjaman) >= 3) {
            return redirect('/dashboard/borrows')->with('notify', 'Gagal Melakukan Peminjaman (' . $member->f_nama . ' Sedang Meminjam ' . $peminjaman . ' Buku)');
        }

        $validated['f_tanggalpeminjaman'] = Carbon::now()->toDateString();
        $validated['f_idadmin'] = Auth::guard('admin')->user()->f_id;

        $borrow = BorrowBook::create($validated);

        if (!$borrow) {
            return redirect('/dashboard/borrows')->with('notify', 'Gagal Melakukan Peminjaman');
        }

        $borrowDetail = BorrowBookDetail::create([
            'f_idpeminjaman' => $borrow->f_id,
            'f_iddetailbuku' => $bookDetail->f_id,
            'f_tanggalkembali' => null
        ]);

        if (!$borrowDetail) {
            return redirect('/dashboard/borrows')->with('notify', 'Gagal Melakukan Peminjaman');
        }

        $updatedBookDetail = $bookDetail->update([
            'f_idbuku' => $bookDetail->f_idbuku,
            'f_status' => 'Tidak Tersedia'
        ]);

        if ($updatedBookDetail) {
            $countBorrowBookByUser = BorrowBook::where('f_idanggota', $validated['f_idanggota'])->whereHas('detailPeminjaman', function ($q) {
                $q->where('f_tanggalkembali', null);
            })->count();
            if ($countBorrowBookByUser < 3) {
                return redirect('/dashboard/borrows')->with([
                    'notify' => 'Berhasil Melakukan Peminjaman',
                    'again' => $member->f_nama . ' ingin meminjam buku lain lagi? (masih bisa pinjam ' . 3 - $countBorrowBookByUser . ' lagi)',
                    'idmember' => $member->f_id
                ]);
            } else {
                return redirect('/dashboard/borrows')->with('notify', 'Berhasil Melakukan Peminjaman');
            }
        }
    }
}
