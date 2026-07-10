<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Tiket;
use App\Models\ActivityLog;
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

        $tiket = Tiket::with('konser')->findOrFail($request->tiket_id);
        $sisa  = $tiket->kuota - $tiket->terjual;

        if ($sisa < $request->jumlah) {
            return back()->with('error', 'Tiket tidak cukup! Sisa: ' . $sisa);
        }

        $subtotal = $tiket->harga * $request->jumlah;

        // ALUR PROFESIONAL: Set status awal menjadi 'pending' (menunggu bayar)
        $order = Order::create([
            'user_id'       => auth()->id(),
            'kode_order'    => 'ORD-' . strtoupper(Str::random(8)),
            'nama_pemesan'  => $request->nama_pemesan,
            'email_pemesan' => $request->email_pemesan,
            'total_harga'   => $subtotal,
            'status'        => 'pending', 
        ]);

        OrderItem::create([
            'order_id'     => $order->id,
            'tiket_id'     => $tiket->id,
            'jumlah'       => $request->jumlah,
            'harga_satuan' => $tiket->harga,
            'subtotal'     => $subtotal,
        ]);

        // Kuota langsung dikunci/dikurangi sementara biar ga ditikung user lain
        $tiket->increment('terjual', $request->jumlah);

        ActivityLog::catat(
            auth()->id(), 
            "Membuat pesanan " . $request->jumlah . " tiket " . ($tiket->kategori ?? $tiket->nama_tiket) . " untuk konser: " . $tiket->konser->nama_konser
        );

        // FIX: Diarahkan ke nama route yang benar sesuai web.php (user.checkout.show)
        return redirect()->route('user.checkout.show', $order->id)
                         ->with('success', 'Pesanan berhasil dibuat! Silakan selesaikan pembayaran dalam 15 menit. 🎉');
    }

    public function show($id)
    {
        // Gunakan eagers loading 'items.tiket' karena data tiket dibungkus di dalam OrderItem
        $order = Order::with('items.tiket.konser')->findOrFail($id);

        // LOGIKA OTOMATIS EXPIRED 15 MENIT
        if ($order->status === 'pending') {
            $waktuOrder = \Carbon\Carbon::parse($order->created_at);
            $waktuHabis = $waktuOrder->addMinutes(15);

            if (now()->greaterThan($waktuHabis)) {
                // 1. Ubah status order jadi expired
                $order->update(['status' => 'expired']);

                // 2. Kembalikan kuota tiket lewat items order
                foreach ($order->items as $item) {
                    $item->tiket->decrement('terjual', $item->jumlah);
                }

                ActivityLog::catat(auth()->id(), "Pesanan " . $order->kode_order . " otomatis hangus karena melewati batas waktu.");
                session()->flash('error', 'Waktu pembayaran Anda telah habis! Tiket otomatis dibatalkan.');
            }
        }

        return view('user.checkout-show', compact('order'));
    }

    // Fitur simulasi tombol bayar manual (Khusus Demo Presentasi)
    public function bayarManual(Order $order)
    {
        if ($order->user_id !== auth()->id() || $order->status !== 'pending') {
            abort(403);
        }

        $order->update(['status' => 'paid']);

        ActivityLog::catat(auth()->id(), "Berhasil melunasi pembayaran tiket " . $order->kode_order);

        return redirect()->route('user.order.success', $order->id)
                         ->with('success', 'Pembayaran berhasil dikonfirmasi! 🎉');
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
        $namaKonser = $order->items->first()->tiket->konser->nama_konser ?? 'Konser';

        // Jika riwayat yang dihapus masih pending/belum dibayar, kembalikan kuota dulu
        if ($order->status === 'pending') {
            foreach ($order->items as $item) {
                $item->tiket->decrement('terjual', $item->jumlah);
            }
        }

        $order->items()->delete();
        $order->delete();

        ActivityLog::catat(auth()->id(), "Menghapus riwayat transaksi " . $order->kode_order . " (Konser: " . $namaKonser . ")");

        return back()->with('success', 'Riwayat tiket berhasil dihapus!');
    }

    public function download(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('items.tiket.konser');
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('user.tiket-pdf', compact('order'));
        return $pdf->download('tiket-' . $order->kode_order . '.pdf');
    }

   public function batalManual(Order $order)
    {
        // Validasi pemilik
        if ($order->user_id !== auth()->id() || $order->status !== 'pending') {
            abort(403);
        }

        $order->load('items.tiket');

        // 1. Kembalikan kuota tiket ke jumlah semula
        foreach ($order->items as $item) {
            $item->tiket->decrement('terjual', $item->jumlah);
        }

        // 2. Catat log aktivitas sebelum dihapus
        ActivityLog::catat(auth()->id(), "Membatalkan pesanan " . $order->kode_order . " (Tiket dihapus dari sistem).");

        // 3. Hapus item dan order secara bersih dari database (Bebas dari CHECK Constraint SQLite!)
        $order->items()->delete();
        $order->delete();

        return redirect()->route('user.home')
                         ->with('success', 'Pesanan Anda berhasil dibatalkan dan kuota tiket telah dilepas! ❌');
    }
}