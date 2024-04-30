<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required',
            'password' => 'required',
            'role' => 'required'
        ]);

        $credentials = [
            'f_username' => $validated['username'],
            'password' => $validated['password']
        ];

        $role = $validated['role'];

        switch ($role) {
            case 'admin':
                $adminAcc = Admin::where("f_username", $validated['username'])->first();
                if (!$adminAcc) {
                    return back()->withErrors([
                        'invalid' => 'Username atau Password Salah'
                    ]);
                } else if ($adminAcc->f_level != "Admin") {
                    return back()->withErrors([
                        'invalid' => 'Username atau Password Salah'
                    ]);
                } else if ($adminAcc->f_status != "Aktif") {
                    return back()->withErrors([
                        'invalid' => 'Akun Ini Tidak Aktif'
                    ]);
                }
                if (Auth::guard('admin')->attempt($credentials)) {
                    $request->session()->regenerate();
                    return redirect()->intended('/dashboard');
                }
                break;
            case 'pustakawan':
                $librarianAcc = Admin::where("f_username", $validated['username'])->first();
                if (!$librarianAcc) {
                    return back()->withErrors([
                        'invalid' => 'Username atau Password Salah'
                    ]);
                } else if ($librarianAcc->f_level != "Pustakawan") {
                    return back()->withErrors([
                        'invalid' => 'Username atau Password Salah'
                    ]);
                } else if ($librarianAcc->f_status != "Aktif") {
                    return back()->withErrors([
                        'invalid' => 'Akun Ini Tidak Aktif'
                    ]);
                }
                if (Auth::guard('admin')->attempt($credentials)) {
                    $request->session()->regenerate();
                    return redirect()->intended('/dashboard');
                }
                break;
            case 'member':
                $memberAcc = Member::where('f_username', $validated['username'])->first();
                if (!$memberAcc) {
                    return back()->withErrors([
                        'invalid' => "Username atau Password Salah"
                    ]);
                }
                if (Auth::guard('member')->attempt($credentials)) {
                    $request->session()->regenerate();
                    return redirect()->intended('/');
                }
                break;
        }
        return back()->withErrors([
            'invalid' => 'Username atau Password Salah'
        ]);
    }

    public function logout(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        } else if (Auth::guard('member')->check()) {
            Auth::guard('member')->logout();
        }

        $request->session()->invalidate();

        $request->session()->regenerateToken();
        return redirect('/');
    }
}
