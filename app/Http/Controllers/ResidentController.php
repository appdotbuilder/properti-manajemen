<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreResidentRequest;
use App\Http\Requests\UpdateResidentRequest;
use App\Models\House;
use App\Models\Resident;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ResidentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Residents can only see their own data
        if ($user->isResident()) {
            return redirect()->route('residents.show', $user->resident);
        }

        $query = Resident::with(['house', 'user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $residents = $query->latest()->paginate(15);

        return Inertia::render('residents/index', [
            'residents' => $residents,
            'filters' => $request->only(['search', 'status']),
            'canCreate' => $user->isAdministrator() || $user->isHousingManager(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $user = $request->user();
        if (!$user || (!$user->isAdministrator() && !$user->isHousingManager())) {
            abort(403, 'Tidak memiliki akses untuk menambah penghuni.');
        }

        $houses = House::whereIn('status', ['available', 'occupied'])
            ->orderBy('block_unit')
            ->get(['id', 'address', 'block_unit', 'status']);

        return Inertia::render('residents/create', [
            'houses' => $houses,
            'selectedHouseId' => $request->house_id,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreResidentRequest $request)
    {
        $resident = Resident::create($request->validated());

        // Update house status to occupied if it was available
        if ($resident->house->status === 'available') {
            $resident->house->update(['status' => 'occupied']);
        }

        return redirect()->route('residents.show', $resident)
            ->with('success', 'Data penghuni berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Resident $resident)
    {
        $user = request()->user();

        // Check if resident can only view their own data
        if ($user->isResident() && $user->resident->id !== $resident->id) {
            abort(403, 'Anda hanya dapat melihat data sendiri.');
        }

        $resident->load(['house', 'user', 'payments', 'complaints']);

        return Inertia::render('residents/show', [
            'resident' => $resident,
            'canEdit' => $user->isAdministrator() || $user->isHousingManager(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Resident $resident)
    {
        $user = request()->user();
        if (!$user || (!$user->isAdministrator() && !$user->isHousingManager())) {
            abort(403, 'Tidak memiliki akses untuk mengedit penghuni.');
        }

        $houses = House::whereIn('status', ['available', 'occupied'])
            ->orWhere('id', $resident->house_id)
            ->orderBy('block_unit')
            ->get(['id', 'address', 'block_unit', 'status']);

        return Inertia::render('residents/edit', [
            'resident' => $resident,
            'houses' => $houses,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateResidentRequest $request, Resident $resident)
    {
        $resident->update($request->validated());

        return redirect()->route('residents.show', $resident)
            ->with('success', 'Data penghuni berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Resident $resident)
    {
        $user = request()->user();
        if (!$user || (!$user->isAdministrator() && !$user->isHousingManager())) {
            abort(403, 'Tidak memiliki akses untuk menghapus penghuni.');
        }

        // Update house status to available if resident is deleted
        if ($resident->house->status === 'occupied') {
            $resident->house->update(['status' => 'available']);
        }

        $resident->delete();

        return redirect()->route('residents.index')
            ->with('success', 'Data penghuni berhasil dihapus.');
    }
}