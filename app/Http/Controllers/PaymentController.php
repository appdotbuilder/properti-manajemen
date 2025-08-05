<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\House;
use App\Models\Payment;
use App\Models\Resident;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Payment::with(['house', 'resident']);

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
                $q->where('type', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('house', function ($hq) use ($search) {
                      $hq->where('block_unit', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $payments = $query->latest()->paginate(15);

        return Inertia::render('payments/index', [
            'payments' => $payments,
            'filters' => $request->only(['search', 'status', 'type']),
            'canCreate' => !$user->isResident(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $user = $request->user();
        if (!$user || $user->isResident()) {
            abort(403, 'Tidak memiliki akses untuk menambah pembayaran.');
        }

        $houses = House::with('currentResident')
            ->where('status', 'occupied')
            ->orderBy('block_unit')
            ->get();

        return Inertia::render('payments/create', [
            'houses' => $houses,
            'selectedHouseId' => $request->house_id,
            'selectedResidentId' => $request->resident_id,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaymentRequest $request)
    {
        $payment = Payment::create($request->validated());

        return redirect()->route('payments.show', $payment)
            ->with('success', 'Data pembayaran berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        $user = request()->user();

        // Check if resident can only view their own payments
        if ($user->isResident()) {
            if (!$payment->resident || $payment->resident->user_id !== $user->id) {
                abort(403, 'Anda hanya dapat melihat riwayat pembayaran sendiri.');
            }
        }

        $payment->load(['house', 'resident']);

        return Inertia::render('payments/show', [
            'payment' => $payment,
            'canEdit' => !$user->isResident(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        $user = request()->user();
        if (!$user || $user->isResident()) {
            abort(403, 'Tidak memiliki akses untuk mengedit pembayaran.');
        }

        $houses = House::with('currentResident')
            ->where('status', 'occupied')
            ->orWhere('id', $payment->house_id)
            ->orderBy('block_unit')
            ->get();

        return Inertia::render('payments/edit', [
            'payment' => $payment,
            'houses' => $houses,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        $payment->update($request->validated());

        return redirect()->route('payments.show', $payment)
            ->with('success', 'Data pembayaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        $user = request()->user();
        if (!$user || $user->isResident()) {
            abort(403, 'Tidak memiliki akses untuk menghapus pembayaran.');
        }

        $payment->delete();

        return redirect()->route('payments.index')
            ->with('success', 'Data pembayaran berhasil dihapus.');
    }
}