<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Customer\PurchaseHistoryController;
use App\Models\User;

class UserProfileController extends \App\Http\Controllers\Controller
{
    /**
     * Tampilkan halaman Profil Pengguna.
     */
    public function index()
    {
        $user = Auth::user();

        $userProfile = [
            'username' => $user->name ?? 'guest',
            'profile_pic' => $user->profile_pic ?? 'placeholder_user.jpg',
            'full_name' => $user->full_name ?? $user->name ?? 'Guest User',
            'birth_date' => $user->birth_date ?? null,
            'gender' => $user->gender ?? null,
            'email' => $user->email ?? null,
            'phone_number' => $user->phone_number ?? null,
            'address_line' => $user->address_line ?? null,
            'city' => $user->city ?? null,
            'province' => $user->province ?? null,
            'postal_code' => $user->postal_code ?? null,
        ];

        $purchaseHistory = PurchaseHistoryController::getHistoryData();
        return view('customer.pages.userProfile', compact('userProfile', 'purchaseHistory'));
    }

    public function edit()
    {
        $sessionUser = session('user');

        $userProfile = [
            'username' => 'guest',
            'profile_pic' => 'placeholder_user.jpg',
            'full_name' => 'Guest User',
            'birth_date' => null,
            'gender' => null,
            'email' => null,
            'phone_number' => null,
        ];

        if ($sessionUser) {
            $user = User::where('name', $sessionUser['username'])->orWhere('email', $sessionUser['username'])->first();
            if ($user) {
                 $userProfile['username'] = $user->name;
                 $userProfile['full_name'] = $user->full_name ?? $user->name;
                $userProfile['email'] = $user->email;
                $userProfile['profile_pic'] = $user->profile_pic ?? $userProfile['profile_pic'];
                $userProfile['phone_number'] = $user->phone_number ?? $userProfile['phone_number'];
                $userProfile['birth_date'] = $user->birth_date ?? $userProfile['birth_date'];
                $userProfile['gender'] = $user->gender ?? $userProfile['gender'];
                $userProfile['address_line'] = $user->address_line ?? null;
                $userProfile['city'] = $user->city ?? null;
                $userProfile['province'] = $user->province ?? null;
                $userProfile['postal_code'] = $user->postal_code ?? null;
            } else {
                $userProfile['username'] = $sessionUser['username'] ?? $sessionUser['nama'] ?? 'user';
                $userProfile['full_name'] = $sessionUser['nama'] ?? $userProfile['username'];
                $userProfile['address_line'] = $sessionUser['address_line'] ?? null;
                $userProfile['city'] = $sessionUser['city'] ?? null;
                $userProfile['province'] = $sessionUser['province'] ?? null;
                $userProfile['postal_code'] = $sessionUser['postal_code'] ?? null;
            }
        }

        return view('customer.pages.editProfile', compact('userProfile'));
    }

    /**
     * Update profile data (including optional profile photo upload)
     */
    public function update(Request $request)
    {
        $sessionUser = session('user');
        if (! $sessionUser) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Cari user di DB
        $user = User::where('name', $sessionUser['username'])->orWhere('email', $sessionUser['username'])->first();

        if (! $user) {
            return back()->with('error', 'User tidak ditemukan di database.');
        }

        $data = $request->validate([
            'username' => 'required|string|max:255',
            'full_name' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:50',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|string|max:1',
            'profile_photo' => 'nullable|image|max:10240',
            // address fields
            'address_line' => 'nullable|string|max:1000',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
        ]);

        // Update basic fields
        // 'name' remains the username; 'full_name' stores the human-readable full name
        $user->name = $data['username'];
        if (isset($data['full_name'])) {
            $user->full_name = $data['full_name'];
        }
        $user->phone_number = $data['phone_number'] ?? $user->phone_number;
        $user->birth_date = $data['birth_date'] ?? $user->birth_date;
        $user->gender = $data['gender'] ?? $user->gender;

        // address
        $user->address_line = $data['address_line'] ?? $user->address_line;
        $user->city = $data['city'] ?? $user->city;
        $user->province = $data['province'] ?? $user->province;
        $user->postal_code = $data['postal_code'] ?? $user->postal_code;

        // Handle profile photo upload if ada
        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            // Simpan langsung ke public/images agar bisa diakses via asset('images/...')
            $file->move(public_path('images'), $filename);
            $user->profile_pic = $filename;
        }

        $user->save();

        // Update session user values
        session(['user' => [
            'username' => $user->name,
            'nama' => $user->full_name ?? $user->name,
            'email' => $user->email,
            'profile_pic' => $user->profile_pic ?? null,
            'role' => 'user'
        ]]);

        return redirect()->route('customer.profile.index')->with('success', 'Profil berhasil diperbarui.');
    }

    
}

