<?php

namespace App\Http\Controllers;

use App\Models\Konser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KonserController extends Controller
{
    public function index()
    {
        $konsers = Konser::with('tikets')->latest()->get();
        return view('konser.index', compact('konsers'));
    }

    public function create()
    {
        return view('konser.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_konser' => 'required|string|max:255',
            'artis'       => 'required|string|max:255',
            'genre'       => 'nullable|string|max:100',
            'venue'       => 'required|string|max:255',
            'tanggal'     => 'required|date',
            'jam'         => 'required',
            'status'      => 'required|in:aktif,selesai,batal',
            'poster'      => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'deskripsi'   => 'nullable|string',
            'maps_url'    => 'nullable|string',
        ]);

        if ($request->hasFile('poster')) {
            $validated['poster'] = $request->file('poster')->store('posters', 'public');
        }

        Konser::create($validated);

        return redirect()->route('konser.index')->with('success', 'Konser berhasil ditambahkan!');
    }

    public function show(Konser $konser)
    {
        $konser->load('tikets');
        return view('konser.show', compact('konser'));
    }

    public function edit(Konser $konser)
    {
        return view('konser.edit', compact('konser'));
    }

    public function update(Request $request, Konser $konser)
    {
        $validated = $request->validate([
            'nama_konser' => 'required|string|max:255',
            'artis'       => 'required|string|max:255',
            'genre'       => 'nullable|string|max:100',
            'venue'       => 'required|string|max:255',
            'tanggal'     => 'required|date',
            'jam'         => 'required',
            'status'      => 'required|in:aktif,selesai,batal',
            'poster'      => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'deskripsi'   => 'nullable|string',
            'maps_url'    => 'nullable|string',
        ]);

        if ($request->hasFile('poster')) {
            if ($konser->poster) {
                Storage::disk('public')->delete($konser->poster);
            }
            $validated['poster'] = $request->file('poster')->store('posters', 'public');
        }

        $konser->update($validated);

        return redirect()->route('konser.index')->with('success', 'Konser berhasil diupdate!');
    }

    public function destroy(Konser $konser)
    {
        if ($konser->poster) {
            Storage::disk('public')->delete($konser->poster);
        }
        $konser->delete();
        return redirect()->route('konser.index')->with('success', 'Konser berhasil dihapus!');
    }
}