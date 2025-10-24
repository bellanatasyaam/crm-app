<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit() {
        $profile = auth()->user();   // ambil data user yg login
        return view('profile.edit', compact('profile')); // pakai view 'edit', bukan 'index'
    }

    public function update(Request $request) {
        $profile = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $profile->id,
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|max:2048',
        ]);

        $profile->name = $request->name;
        $profile->email = $request->email;
        $profile->phone = $request->phone;

        if ($request->hasFile('photo')) {
            $photoName = time() . '.' . $request->photo->extension();
            $request->photo->move(public_path('uploads/photos'), $photoName);
            $profile->photo_url = '/uploads/photos/' . $photoName;
        }

        $profile->save();

        return redirect()->route('profile.index')->with('success', 'Profile updated successfully.');
    }

    public function show()
    {
        $user = auth()->user();

        return view('profile.show', compact('user'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function editPassword()
    {
        return view('profile.edit-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully!');
    }

    public function showProfile()
    {
        $profile = auth()->user(); // ambil user yang login
        return view('profile.index', compact('profile'));
    }

}
