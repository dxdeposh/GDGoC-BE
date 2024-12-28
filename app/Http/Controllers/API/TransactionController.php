<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
    // Tampilkan semua transaksi (Admin dan pengguna terkait)
    public function index()
    {
        $user = Auth::user();
        if ($user->role === 'admin') {
            $transactions = Transaction::all();
        } else if ($user->role === 'buyer') {
            $transactions = Transaction::whereHas('order', function ($q) use ($user) {
                $q->where('buyer_id', $user->user_id);
            })->get();
        } else if ($user->role === 'seller') {
            $transactions = Transaction::whereHas('order', function ($q) use ($user) {
                $q->where('seller_id', $user->user_id);
            })->get();
        } else {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($transactions, 200);
    }

    // Tampilkan detail transaksi
    public function show($id)
    {
        $transaction = Transaction::findOrFail($id);

        $this->authorize('view', $transaction);

        return response()->json($transaction, 200);
    }

    // Update status transaksi
    public function updateStatus(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        $this->authorize('update', $transaction);

        $request->validate([
            'status_transaksi' => ['required', Rule::in(['pending', 'sukses', 'gagal', 'refund'])],
        ]);

        $transaction->status_transaksi = $request->status_transaksi;
        $transaction->save();

        // Tambahkan log aktivitas, notifikasi, dll. sesuai kebutuhan

        return response()->json([
            'message' => 'Transaction status updated successfully',
            'transaction' => $transaction,
        ], 200);
    }

    // Hapus transaksi (Admin only)
    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);

        $this->authorize('delete', $transaction);

        $transaction->delete();

        return response()->json([
            'message' => 'Transaction deleted successfully',
        ], 200);
    }
}
