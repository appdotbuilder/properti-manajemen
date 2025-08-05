<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\House;
use App\Models\Payment;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $stats = [];

        if ($user->isAdministrator()) {
            $stats = [
                'total_houses' => House::count(),
                'available_houses' => House::available()->count(),
                'occupied_houses' => House::occupied()->count(),
                'sold_houses' => House::sold()->count(),
                'total_residents' => Resident::active()->count(),
                'total_users' => User::active()->count(),
                'pending_payments' => Payment::pending()->count(),
                'overdue_payments' => Payment::overdue()->count(),
                'new_complaints' => Complaint::where('status', 'new')->count(),
                'open_complaints' => Complaint::open()->count(),
            ];

            $recentComplaints = Complaint::with(['house', 'resident'])
                ->latest()
                ->limit(5)
                ->get();

            $overduePayments = Payment::with(['house', 'resident'])
                ->overdue()
                ->latest()
                ->limit(5)
                ->get();
        } elseif ($user->isHousingManager()) {
            $stats = [
                'total_houses' => House::count(),
                'available_houses' => House::available()->count(),
                'occupied_houses' => House::occupied()->count(),
                'sold_houses' => House::sold()->count(),
                'total_residents' => Resident::active()->count(),
                'pending_payments' => Payment::pending()->count(),
                'overdue_payments' => Payment::overdue()->count(),
                'new_complaints' => Complaint::where('status', 'new')->count(),
                'assigned_complaints' => Complaint::where('assigned_to', $user->id)->open()->count(),
            ];

            $recentComplaints = Complaint::with(['house', 'resident'])
                ->latest()
                ->limit(5)
                ->get();

            $assignedComplaints = Complaint::with(['house', 'resident'])
                ->where('assigned_to', $user->id)
                ->open()
                ->latest()
                ->limit(5)
                ->get();
        } elseif ($user->isSalesStaff()) {
            $stats = [
                'available_houses' => House::available()->count(),
                'sold_houses' => House::sold()->count(),
                'total_sales_value' => House::sold()->sum('price'),
            ];

            $availableHouses = House::available()
                ->latest()
                ->limit(8)
                ->get();
        } elseif ($user->isResident()) {
            $resident = $user->resident;
            if ($resident) {
                $stats = [
                    'my_payments_pending' => Payment::where('resident_id', $resident->id)->pending()->count(),
                    'my_payments_overdue' => Payment::where('resident_id', $resident->id)->overdue()->count(),
                    'my_complaints_open' => Complaint::where('resident_id', $resident->id)->open()->count(),
                ];

                $myPayments = Payment::where('resident_id', $resident->id)
                    ->latest()
                    ->limit(5)
                    ->get();

                $myComplaints = Complaint::with(['assignedUser'])
                    ->where('resident_id', $resident->id)
                    ->latest()
                    ->limit(5)
                    ->get();
            }
        }

        return Inertia::render('dashboard', [
            'stats' => $stats,
            'recentComplaints' => $recentComplaints ?? [],
            'overduePayments' => $overduePayments ?? [],
            'assignedComplaints' => $assignedComplaints ?? [],
            'availableHouses' => $availableHouses ?? [],
            'myPayments' => $myPayments ?? [],
            'myComplaints' => $myComplaints ?? [],
            'userRole' => $user->role,
        ]);
    }
}