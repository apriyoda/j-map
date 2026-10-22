<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class PlaceController extends Controller
{
    // Public landing page with Leaflet map
    public function welcome()
    {
        $places = Place::where('status', 'approved')->get();
        $categories = Place::where('status', 'approved')
            ->whereNotNull('category')
            ->select('category')
            ->distinct()
            ->pluck('category');

        return view('welcome', compact('places', 'categories'));
    }

    // Places management table (CRUD)
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk melihat daftar tempat.');
        }

        $places = Place::latest()->get();
        return view('places.index', compact('places'));
    }

    // Show form to create a new place
    public function create()
    {
        return view('places.create');
    }

    // Store new place (immediate approve for admin, pending for member)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'image' => 'nullable|image',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('places', 'public');
            $validated['image'] = $path;
        }

        $validated['user_id'] = Auth::id();

        if (Auth::user()->role === 'member') {
            $validated['status'] = 'pending';
            Place::create($validated);

            return redirect()->route('dashboard')->with('success', 'Lokasi Anda berhasil diajukan dan sedang ditinjau oleh Admin. Terima kasih atas partisipasi Anda!');
        }

        $validated['status'] = 'approved';
        Place::create($validated);

        return redirect()->route('places.index')->with('success', 'Tempat berhasil ditambahkan.');
    }

    // Show edit form
    public function edit(Place $place)
    {
        if (Auth::user()->role !== 'admin' && $place->user_id && $place->user_id !== Auth::id()) {
            abort(403);
        }

        return view('places.edit', compact('place'));
    }

    // Update place
    public function update(Request $request, Place $place)
    {
        if (Auth::user()->role !== 'admin' && $place->user_id && $place->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'image' => 'nullable|image',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('places', 'public');
            $validated['image'] = $path;
        }

        $place->update($validated);

        return redirect()->route('places.index')->with('success', 'Tempat berhasil diperbarui.');
    }

    // Delete place
    public function destroy(Place $place)
    {
        if (Auth::user()->role !== 'admin' && $place->user_id && $place->user_id !== Auth::id()) {
            abort(403);
        }

        $place->delete();

        return redirect()->route('places.index')->with('success', 'Tempat berhasil dihapus.');
    }

    // ---------- Suggestion flow (member) ----------
    public function createSuggestion()
    {
        return view('places.create');
    }

    public function storeSuggestion(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'image' => 'nullable|image',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('places', 'public');
            $validated['image'] = $path;
        }

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending';
        Place::create($validated);

        return redirect()->route('places.index')->with('status', 'Tempat diajukan untuk verifikasi admin.');
    }

    // ---------- Admin actions ----------
    public function adminIndex()
    {
        $places = Place::where('status', 'pending')->get();
        return view('admin.places.pending', compact('places'));
    }

    public function approve(Place $place)
    {
        $place->status = 'approved';
        $place->save();
        return Redirect::back()->with('status', 'Tempat disetujui.');
    }

    public function reject(Place $place)
    {
        $place->status = 'rejected';
        $place->save();
        return Redirect::back()->with('status', 'Tempat ditolak.');
    }
}
