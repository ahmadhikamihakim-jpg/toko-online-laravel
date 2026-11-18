<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
class AuthController extends Controller {
    public function showLogin() { return view('auth.login'); }
    public function login(Request $request) {
        $data = $request->validate(['email'=>'required|email','password'=>'required|string']);
        if (!Auth::attempt($data, $request->boolean('remember'))) {
            throw ValidationException::withMessages(['email'=>'Email atau kata sandi salah.']);
        }
        $request->session()->regenerate();
        return redirect()->intended(route('home'))->with('success','Selamat datang kembali, '.Auth::user()->name.'!');
    }
    public function showRegister() { return view('auth.register'); }
    public function register(Request $request) {
        $data = $request->validate(['name'=>'required|string|max:255','email'=>'required|email|max:255|unique:users,email','password'=>'required|string|min:8|confirmed']);
        $user = User::create($data); // password di-hash otomatis oleh cast model User
        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->route('home')->with('success','Akun berhasil dibuat. Selamat berbelanja!');
    }
    public function logout(Request $request) {
        Auth::logout(); $request->session()->invalidate(); $request->session()->regenerateToken();
        return redirect()->route('home')->with('success','Anda sudah keluar.');
    }
}
