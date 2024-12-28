<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    // Tampilkan semua order (Admin dan pengguna terkait)
    public function index()
    {
        $user = Auth::user();
        if ($user->role === 'admin') {
            $orders = Order::all();
        } else if ($user->role === 'buyer') {
            $orders = Order::where('buyer_id', $user->user_id)->get();
        } else if ($user->role === 'seller') {
            $orders = Order::where('seller_id', $user->user_id)->get();
        } else {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($orders, 200);
    }

    // Tampilkan detail order
    public function show($id)
    {
        $order = Order::findOrFail($id);

        $this->authorize('view', $order);

        return response()->json($order, 200);
    }

    // Buat order baru
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|uuid|exists:products,product_id',
            'jumlah' => 'required|integer|min:1',
            'alamat_pengiriman' => 'required|string',
            'metode_pengiriman' => 'required|string',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->stok < $request->jumlah) {
            return response()->json(['message' => 'Stok tidak mencukupi'], 400);
        }

        DB::beginTransaction();
        try {
            $order = Order::create([
                'buyer_id' => Auth::user()->user_id,
                'seller_id' => $product->user_id,
                'product_id' => $product->product_id,
                'jumlah' => $request->jumlah,
                'total_harga' => $product->harga * $request->jumlah,
                'status_order' => 'pending',
                'alamat_pengiriman' => $request->alamat_pengiriman,
                'metode_pengiriman' => $request->metode_pengiriman,
            ]);

            // Kurangi stok
            $product->stok -= $request->jumlah;
            $product->save();

            // Buat transaksi
            $transaction = Transaction::create([
                'order_id' => $order->order_id,
                'metode_pembayaran' => $request->metode_pembayaran ?? 'cash_on_delivery',
                'status_transaksi' => 'pending',
                'amount' => $order->total_harga,
                'currency' => 'IDR',
                // 'payment_gateway_id' => null, // Atur jika menggunakan payment gateway
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Order created successfully',
                'order' => $order,
                'transaction' => $transaction,
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Order creation failed', 'error' => $e->getMessage()], 500);
        }
    }

    // Update status order (Seller and Admin)
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $this->authorize('updateStatus', $order);

        $request->validate([
            'status_order' => ['required', Rule::in(['pending', 'diproses', 'dikirim', 'selesai', 'dibatalkan', 'retur'])],
        ]);

        $order->status_order = $request->status_order;
        $order->save();

        // Tambahkan log aktivitas, notifikasi, dll. sesuai kebutuhan

        return response()->json([
            'message' => 'Order status updated successfully',
            'order' => $order,
        ], 200);
    }

    // Hapus order (Admin only)
    public function destroy($id)
    {
        $order = Order::findOrFail($id);

        $this->authorize('delete', $order);

        $order->delete();

        return response()->json([
            'message' => 'Order deleted successfully',
        ], 200);
    }
}
