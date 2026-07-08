<?php
// app/Http/Controllers/AuthController.php
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if (Auth::attempt($request->only('email', 'password'), $request->remember)) {
            $user = Auth::user();
            
            // Redirect based on role
            if ($user->isAdmin()) {
                return redirect()->route('dashboard.admin');
            } elseif ($user->isPetugasSurvei()) {
                return redirect()->route('dashboard.petugas_survei');
            } elseif ($user->isStafPerencana()) {
                return redirect()->route('dashboard.staf_perencana');
            } elseif ($user->isKepalaBidang()) {
                return redirect()->route('dashboard.kepala');
            }
        }

        return redirect()->back()->with('error', 'Email atau password salah');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}