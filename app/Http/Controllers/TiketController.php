<?php

namespace App\Http\Controllers;

use App\Models\Konser;
use App\Models\Tiket;
use Illuminate\Http\Request;

class TiketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tikets = Tiket::with('konser')->latest()->get();
        return view('tiket.index', compact('tikets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $konsers = Konser::where('status', 'aktif')->get();
        return view('tiket.create', compact('konsers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'konser_id' => 'required|exists:konsers,id',
            'kategori'  => 'required|string|max:100',
            'harga'     => 'required|numeric|min:0',
            'kuota'     => 'required|integer|min:1',
        ]);

        Tiket::create($validated);

        return redirect()->route('tiket.index')->with('success', 'Tiket berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tiket $tiket)
    {
        $konsers = Konser::where('status', 'aktif')->get();
        return view('tiket.edit', compact('tiket', 'konsers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tiket $tiket)
    {
        $validated = $request->validate([
            'konser_id' => 'required|exists:konsers,id',
            'kategori'  => 'required|string|max:100',
            'harga'     => 'required|numeric|min:0',
            'kuota'     => 'required|integer|min:1',
            'terjual'   => 'required|integer|min:0',
        ]);

        $tiket->update($validated);

        return redirect()->route('tiket.index')->with('success', 'Tiket berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tiket $tiket)
    {
        $tiket->delete();

        return redirect()->route('tiket.index')->with('success', 'Tiket berhasil dihapus');
    }
}