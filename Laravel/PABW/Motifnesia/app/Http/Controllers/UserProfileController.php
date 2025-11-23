<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\PurchaseHistoryController;
class UserProfileController extends Controller
{
    /**
     * Tampilkan halaman Profil Pengguna.
     */
    public function index()
    {
        // Data Dummy Profil Pengguna sesuai screenshot
        $userProfile = [
            'username' => 'hahahoho',
            'profile_pic' => 'placeholder_user.jpg', // Asumsi nama file gambar
            'full_name' => 'Muhammad Abyaz Zaydan',
            'birth_date' => '2025-10-16',
            'gender' => 'L',
            'email' => 'hahahoho@gamil.com',
            'phone_number' => '087775580899',
        ];

        $purchaseHistory = PurchaseHistoryController::getHistoryData(); 
        return view('userProfile', compact('userProfile', 'purchaseHistory'));
    }

    public function edit()
    {
        // Data yang sama akan dikirim ke formulir
        $userProfile = [
            'username' => 'hahahoho',
            'profile_pic' => 'placeholder_user.jpg',
            'full_name' => 'Muhammad Abyaz Zaydan',
            'birth_date' => '2025-10-16',
            'gender' => 'L',
            'email' => 'hahahoho@gamil.com',
            'phone_number' => '087775580899',
        ];

        // Me-load view editProfile.blade.php (yang akan kita buat)
        return view('editProfile', compact('userProfile'));
    }

    
}

