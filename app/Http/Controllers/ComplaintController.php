<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreComplaintRequest;
use App\Http\Requests\UpdateComplaintRequest;
use App\Models\Complaint;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Complaint::with(['house', 'resident', 'assignedUser']);

        // Apply role-based filtering
        if ($user->isResident()) {
            $query->whereHas('resident', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $complaints = $query->latest()->paginate(15);

        return Inertia::render('complaints/index', [
            'complaints' => $complaints,
            'filters' => $request->only(['search', 'status', 'priority']),
            'canCreate' => $user->isResident(),
            'canAssign' => $user->isAdministrator() || $user->isHousingManager(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = request()->user();
        if (!$user || !$user->isResident()) {
            abort(403, 'Hanya penghuni yang dapat mengajukan keluhan.');
        }

        $user = request()->user();
        $resident = $user->resident;

        if (!$resident) {
            return redirect()->route('complaints.index')
                ->with('error', 'Anda harus terdaftar sebagai penghuni untuk mengajukan keluhan.');
        }

        return Inertia::render('complaints/create', [
            'resident' => $resident->load('house'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreComplaintRequest $request)
    {
        $user = $request->user();
        $resident = $user->resident;

        $complaint = Complaint::create(array_merge($request->validated(), [
            'house_id' => $resident->house_id,
            'resident_id' => $resident->id,
        ]));

        return redirect()->route('complaints.show', $complaint)
            ->with('success', 'Keluhan berhasil diajukan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Complaint $complaint)
    {
        $user = request()->user();

        // Check if resident can only view their own complaints
        if ($user->isResident()) {
            if ($complaint->resident->user_id !== $user->id) {
                abort(403, 'Anda hanya dapat melihat keluhan sendiri.');
            }
        }

        $complaint->load(['house', 'resident', 'assignedUser']);

        $staffUsers = [];
        if ($user->isAdministrator() || $user->isHousingManager()) {
            $staffUsers = User::whereIn('role', ['administrator', 'housing_manager'])
                ->where('status', 'active')
                ->get(['id', 'name', 'role']);
        }

        return Inertia::render('complaints/show', [
            'complaint' => $complaint,
            'staffUsers' => $staffUsers,
            'canEdit' => $user->isResident() && $complaint->resident->user_id === $user->id,
            'canAssign' => $user->isAdministrator() || $user->isHousingManager(),
            'canRespond' => !$user->isResident(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Complaint $complaint)
    {
        $user = request()->user();
        if (!$user) {
            abort(403, 'Akses ditolak.');
        }
        
        // Residents can only edit their own complaints if they're still 'new'
        if ($user->isResident()) {
            if ($complaint->resident->user_id !== $user->id || $complaint->status !== 'new') {
                abort(403, 'Anda hanya dapat mengedit keluhan sendiri yang masih baru.');
            }
        }

        return Inertia::render('complaints/edit', [
            'complaint' => $complaint->load(['house', 'resident']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateComplaintRequest $request, Complaint $complaint)
    {
        $complaint->update($request->validated());

        return redirect()->route('complaints.show', $complaint)
            ->with('success', 'Keluhan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Complaint $complaint)
    {
        $user = request()->user();
        if (!$user || (!$user->isAdministrator() && !$user->isHousingManager())) {
            abort(403, 'Tidak memiliki akses untuk menghapus keluhan.');
        }

        $complaint->delete();

        return redirect()->route('complaints.index')
            ->with('success', 'Keluhan berhasil dihapus.');
    }
}