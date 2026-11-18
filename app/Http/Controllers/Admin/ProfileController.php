<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
class ProfileController extends Controller {
    public function edit(){ return view('admin.profile', ['user' => auth()->user()]); }
    public function update(Request $request){
        $user = auth()->user();
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required','email','max:255', Rule::unique('users','email')->ignore($user->id)],
            'bio' => 'nullable|string|max:500',
        ]);
        $user->update($data);
        return back()->with('success','Profil berhasil diperbarui.');
    }
    public function updatePassword(Request $request){
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);
        auth()->user()->update(['password' => $request->input('password')]); // di-hash otomatis oleh cast model
        return back()->with('success','Kata sandi berhasil diubah.');
    }
}
