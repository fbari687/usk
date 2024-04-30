<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\BorrowBook;
use Illuminate\Http\Request;

class LibrariansController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admins.librarians.index', [
            'admins' => Admin::orderBy('f_id', 'asc')->paginate(5)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admins.librarians.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'f_nama' => 'required',
            'f_username' => 'required|unique:t_admin,f_username',
            'f_password' => 'required|min:6',
            'f_level' => 'required|in:Admin,Pustakawan',
            'f_status' => 'required|in:Aktif,Tidak Aktif'
        ]);

        $request->validate([
            'f_confirmPassword' => 'required|same:f_password',
        ]);

        $validated['f_password'] = bcrypt($validated['f_password']);

        $admin = Admin::create($validated);

        if (!$admin) {
            return redirect('/dashboard/librarians')->with('notify', 'Gagal Registrasi Akun');
        }
        return redirect('/dashboard/librarians')->with('notify', 'Berhasil Registrasi Akun');
    }

    /**
     * Display the specified resource.
     */
    public function show(Admin $admin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin)
    {
        return view('admins.librarians.edit', [
            'librarian' => $admin
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Admin $admin)
    {
        $rules = [
            'f_nama' => 'required',
            'f_level' => 'required|in:Admin,Pustakawan',
            'f_status' => 'required|in:Aktif,Tidak Aktif'
        ];

        if ($request->f_username != $admin->f_username) {
            $rules['f_username'] = 'required|unique:t_admin,f_username';
        } else {
            $rules['f_username'] = 'required';
        }

        $validated = $request->validate($rules);

        $updatedAdmin = $admin->update($validated);

        if (!$updatedAdmin) {
            return redirect('/dashboard/librarians')->with('notify', 'Gagal Edit Akun');
        }
        return redirect('/dashboard/librarians')->with('notify', 'Berhasil Edit Akun');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        $borrow = BorrowBook::where('f_idadmin', $admin->f_id)->whereHas('detailPeminjaman', function ($q) {
            $q->where('f_tanggalkembali', null);
        })->get();
        if (count($borrow) != 0) {
            return redirect('/dashboard/librarians')->with('notify', 'Tidak Bisa menghapus Admin yang sedang menangani peminjaman buku');
        }

        $deletedAdmin = $admin->delete();
        if (!$deletedAdmin) {
            return redirect('/dashboard/librarians')->with('notify', 'Gagal Menghapus Admin/Pustakawan');
        }
        return redirect('/dashboard/librarians')->with('notify', 'Berhasil Menghapus Admin/Pustakawan ' . $admin->f_nama);
    }

    public function editPwView(Admin $admin)
    {
        return view('admins.librarians.editPassword', [
            'admin' => $admin
        ]);
    }

    public function editPw(Admin $admin, Request $request)
    {
        $validated = $request->validate([
            'f_password' => 'required|min:6',
            'f_confirmPassword' => 'required|same:f_password'
        ]);

        $newPassword = bcrypt($validated['f_password']);

        $newAdmin = [
            'f_nama' => $admin->f_nama,
            'f_username' => $admin->f_username,
            'f_password' => $newPassword,
            'f_level' => $admin->f_level,
            'f_status' => $admin->f_status
        ];

        $updatedAdmin = $admin->update($newAdmin);

        if (!$updatedAdmin) {
            return redirect('/dashboard/librarians')->with('notify', 'Gagal Mengubah Password');
        }
        return redirect('/dashboard/librarians')->with('notify', 'Berhasil Mengubah Password Admin/Pustakawan ' . $admin->f_nama);
    }
}
