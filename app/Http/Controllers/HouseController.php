<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHouseRequest;
use App\Http\Requests\UpdateHouseRequest;
use App\Models\House;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $query = House::with(['currentResident']);

        // Apply role-based filtering
        if ($user->isSalesStaff()) {
            $query->available();
        }

        // Apply search filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('address', 'like', "%{$search}%")
                  ->orWhere('block_unit', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $houses = $query->latest()->paginate(12);

        return Inertia::render('houses/index', [
            'houses' => $houses,
            'filters' => $request->only(['search', 'status', 'type']),
            'canCreate' => $user->isAdministrator(),
            'canEdit' => $user->isAdministrator() || $user->isHousingManager(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = request()->user();
        if (!$user || !$user->isAdministrator()) {
            abort(403, 'Tidak memiliki akses untuk menambah rumah.');
        }

        return Inertia::render('houses/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHouseRequest $request)
    {
        $house = House::create($request->validated());

        return redirect()->route('houses.show', $house)
            ->with('success', 'Rumah berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(House $house)
    {
        $house->load(['currentResident', 'residents', 'payments.resident', 'complaints.resident']);

        $user = request()->user();

        // Check if resident can only view their own house
        if ($user->isResident()) {
            $resident = $user->resident;
            if (!$resident || $resident->house_id !== $house->id) {
                abort(403, 'Anda hanya dapat melihat data rumah sendiri.');
            }
        }

        return Inertia::render('houses/show', [
            'house' => $house,
            'canEdit' => $user->isAdministrator() || $user->isHousingManager(),
            'canUpdateStatus' => $user->isAdministrator() || $user->isSalesStaff(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(House $house)
    {
        $user = request()->user();
        if (!$user || (!$user->isAdministrator() && !$user->isHousingManager())) {
            abort(403, 'Tidak memiliki akses untuk mengedit rumah.');
        }

        return Inertia::render('houses/edit', [
            'house' => $house,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHouseRequest $request, House $house)
    {
        $house->update($request->validated());

        return redirect()->route('houses.show', $house)
            ->with('success', 'Data rumah berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(House $house)
    {
        $user = request()->user();
        if (!$user || !$user->isAdministrator()) {
            abort(403, 'Tidak memiliki akses untuk menghapus rumah.');
        }

        $house->delete();

        return redirect()->route('houses.index')
            ->with('success', 'Rumah berhasil dihapus.');
    }
}