<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode; // untuk QR Code
use Milon\Barcode\DNS1D; // untuk Barcode

class PetugasController extends Controller
{
    // Menampilkan data petugas
    public function index()
    {
        $petugas = User::all();
        return view('tb_petugas', compact('petugas'));
    }

    public function generateQRCode($hash)
    {
        try {
            $id = decrypt($hash);
            $petugas = User::findOrFail($id);
            
            // Generate QR Code (contoh menggunakan SimpleSoftwareIO/simple-qrcode)
            $qrCode = QrCode::size(300)->generate($hash);
            
            return view('qrcode', compact('petugas', 'qrCode', 'hash'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Invalid hash');
        }
    }

    public function generateBarcode($hash)
    {
        try {
            $id = decrypt($hash);
            $petugas = User::findOrFail($id);
            
            // Generate Barcode - Buat instance baru
            $dns1d = new DNS1D();
            $barcode = $dns1d->getBarcodeHTML(substr($hash,0,10), 'C128');
            
            return view('barcode', compact('petugas', 'barcode', 'hash'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Invalid hash');
        }
    }

    // Menyimpan data petugas baru
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:operator,admin',
        ]);

        // Simpan data petugas baru tanpa hashing password
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password, // Tidak aman, hanya untuk pengembangan
            'role' => $request->role,
        ]);

        return redirect()->route('petugas.index')->with('success', 'Petugas berhasil ditambahkan');
    }

    // Mengupdate data petugas
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:operator,admin',
        ]);

        $petugas = User::findOrFail($id);
        $petugas->name = $request->name;
        $petugas->email = $request->email;

        // Update password jika ada, tanpa hashing
        if ($request->password) {
            $petugas->password = $request->password; 
        }

        $petugas->role = $request->role;
        $petugas->save();

        return redirect()->route('petugas.index')->with('success', 'Petugas berhasil diupdate');
    }

    // Menghapus data petugas
    public function destroy($id)
    {
        $petugas = User::findOrFail($id);
        $petugas->delete();

        return redirect()->route('petugas.index')->with('success', 'Petugas berhasil dihapus');
    }
}
