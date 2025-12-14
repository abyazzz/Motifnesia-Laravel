<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductReturn;

class ReturnController extends Controller
{
    /**
     * Menampilkan halaman kelola retur.
     */
    public function index(Request $request)
    {
        $filter = $request->get('status', 'all');

        $query = ProductReturn::with(['user', 'order', 'orderItem', 'produk']);

        if ($filter !== 'all') {
            $query->where('status', $filter);
        }

        $returns = $query->orderBy('created_at', 'desc')->paginate(15);

        // Count by status
        $counts = [
            'all' => ProductReturn::count(),
            'Pending' => ProductReturn::where('status', 'Pending')->count(),
            'Disetujui' => ProductReturn::where('status', 'Disetujui')->count(),
            'Ditolak' => ProductReturn::where('status', 'Ditolak')->count(),
            'Diproses' => ProductReturn::where('status', 'Diproses')->count(),
            'Selesai' => ProductReturn::where('status', 'Selesai')->count(),
        ];

        return view('admin.pages.returnManagement', [
            'returns' => $returns,
            'counts' => $counts,
            'currentFilter' => $filter,
            'activePage' => 'returns'
        ]);
    }

    /**
     * Update status retur
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,Disetujui,Ditolak,Diproses,Selesai',
            'admin_note' => 'nullable|string|max:500',
        ]);

        $return = ProductReturn::findOrFail($id);
        $return->status = $request->status;
        
        if ($request->admin_note) {
            $return->admin_note = $request->admin_note;
        }

        // Update refund status jika status Disetujui atau Selesai
        if ($request->status === 'Disetujui') {
            $return->refund_status = 'Diproses';
        } elseif ($request->status === 'Selesai') {
            $return->refund_status = 'Selesai';
        }

        $return->save();

        return response()->json([
            'success' => true,
            'message' => 'Status retur berhasil diupdate.'
        ]);
    }

    /**
     * Delete retur
     */
    public function destroy($id)
    {
        $return = ProductReturn::findOrFail($id);
        $return->delete();

        return redirect()->back()->with('success', 'Data retur berhasil dihapus.');
    }
}