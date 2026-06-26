<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Tiket;
use App\Models\ActivityLog; // <-- 1. PASTIKAN MODEL LOG SUDAH DI-IMPORT DI SINI
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $tiket = Tiket::with('konser')->findOrFail($request->tiket_id);
        return view('user.checkout', compact('tiket'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tiket_id'      => 'required|exists:tikets,id',
            'jumlah'        => 'required|integer|min:1|max:10',
            'nama_pemesan'  => 'required|string|max:255',
            'email_pemesan' => 'required|email|max:255',
        ]);

        $tiket = Tiket::with('konser')->findOrFail($request->tiket_id); // Load relasi konser sekalian
        $sisa  = $tiket->kuota - $tiket->terjual;

        if ($sisa < $request->jumlah) {
            return back()->with('error', 'Tiket tidak cukup! Sisa: ' . $sisa);
        }

        $subtotal = $tiket->harga * $request->jumlah;

        $order = Order::create([
            'user_id'       => auth()->id(),
            'kode_order'    => 'ORD-' . strtoupper(Str::random(8)),
            'nama_pemesan'  => $request->nama_pemesan,
            'email_pemesan' => $request->email_pemesan,
            'total_harga'   => $subtotal,
            'status'        => 'paid',
        ]);

        OrderItem::create([
            'order_id'     => $order->id,
            'tiket_id'     => $tiket->id,
            'jumlah'       => $request->jumlah,
            'harga_satuan' => $tiket->harga,
            'subtotal'     => $subtotal,
        ]);

        $tiket->increment('terjual', $request->jumlah);

        // <-- 2. POSISI TERBAIK: Catat log aktivitas setelah transaksi selesai dibuat
        ActivityLog::catat(
            auth()->id(), 
            "Berhasil membeli " . $request->jumlah . " tiket " . $tiket->kategori . " untuk konser: " . $tiket->konser->nama_konser
        );

        return redirect()->route('user.order.success', $order)
                         ->with('success', 'Pemesanan berhasil! Selamat menikmati konsernya! 🎉');
    }

    public function success(Order $order)
    {
        $order->load('items.tiket.konser');
        return view('user.order-success', compact('order'));
    }

    public function myOrders()
    {
        $orders = Order::with('items.tiket.konser')
                       ->where('user_id', auth()->id())
                       ->latest()->get();
        return view('user.myorders', compact('orders'));
    }

    public function destroy(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('items.tiket.konser');
        // Ambil nama konser sebelum datanya dihapus untuk keperluan teks di log
        $namaKonser = $order->items->first()->tiket->konser->nama_konser ?? 'Konser';

        $order->items()->delete();
        $order->delete();

        // <-- 3. POSISI KEDUA: Catat log ketika user menghapus riwayat pesanan tiket mereka
        ActivityLog::catat(auth()->id(), "Menghapus riwayat transaksi " . $order->kode_order . " (Konser: " . $namaKonser . ")");

        return back()->with('success', 'Riwayat tiket berhasil dihapus!');
    }

    public function download(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('items.tiket.konser');
        
        // <-- OPTIONAL: Kalau kamu mau catat setiap kali user download PDF tiketnya, bisa selipin di sini:
        // ActivityLog::catat(auth()->id(), "Mengunduh file PDF e-tiket " . $order->kode_order);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('user.tiket-pdf', compact('order'));

        return $pdf->download('tiket-' . $order->kode_order . '.pdf');
    }
}