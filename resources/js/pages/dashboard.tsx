import React from 'react';
import { Head, Link } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';
import { Button } from '@/components/ui/button';

interface Props {
    stats: Record<string, number>;
    recentComplaints: Array<{
        id: number;
        title: string;
        status: string;
        priority: string;
        created_at: string;
        house: { block_unit: string };
        resident: { name: string };
    }>;
    overduePayments: Array<{
        id: number;
        type: string;
        amount: number;
        due_date: string;
        house: { block_unit: string };
        resident?: { name: string };
    }>;
    assignedComplaints: Array<{
        id: number;
        title: string;
        status: string;
        priority: string;
        created_at: string;
        house: { block_unit: string };
        resident: { name: string };
    }>;
    availableHouses: Array<{
        id: number;
        address: string;
        type: string;
        bedrooms: number;
        bathrooms: number;
        price: number;
        block_unit: string;
    }>;
    myPayments: Array<{
        id: number;
        type: string;
        amount: number;
        status: string;
        due_date: string;
    }>;
    myComplaints: Array<{
        id: number;
        title: string;
        status: string;
        priority: string;
        created_at: string;
        assigned_user?: { name: string };
    }>;
    userRole: string;
    [key: string]: unknown;
}

export default function Dashboard({ 
    stats, 
    recentComplaints, 
    overduePayments,
    availableHouses,
    myPayments,
    myComplaints,
    userRole 
}: Props) {
    const formatCurrency = (amount: number) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
        }).format(amount);
    };

    const formatDate = (date: string) => {
        return new Date(date).toLocaleDateString('id-ID');
    };

    const getStatusBadge = (status: string) => {
        const badges = {
            available: 'bg-green-100 text-green-800',
            occupied: 'bg-blue-100 text-blue-800',
            sold: 'bg-gray-100 text-gray-800',
            pending: 'bg-yellow-100 text-yellow-800',
            paid: 'bg-green-100 text-green-800',
            overdue: 'bg-red-100 text-red-800',
            new: 'bg-blue-100 text-blue-800',
            in_progress: 'bg-yellow-100 text-yellow-800',
            completed: 'bg-green-100 text-green-800',
            rejected: 'bg-red-100 text-red-800',
        };
        return badges[status as keyof typeof badges] || 'bg-gray-100 text-gray-800';
    };

    const getPriorityBadge = (priority: string) => {
        const badges = {
            low: 'bg-gray-100 text-gray-800',
            medium: 'bg-yellow-100 text-yellow-800',
            high: 'bg-orange-100 text-orange-800',
            urgent: 'bg-red-100 text-red-800',
        };
        return badges[priority as keyof typeof badges] || 'bg-gray-100 text-gray-800';
    };

    return (
        <AppShell>
            <Head title="Dashboard" />
            
            <div className="space-y-6">
                {/* Welcome Header */}
                <div>
                    <h1 className="text-2xl font-bold text-gray-900">
                        🏘️ Dashboard Manajemen Perumahan
                    </h1>
                    <p className="text-gray-600">
                        {userRole === 'administrator' && 'Kelola semua aspek perumahan dengan akses penuh'}
                        {userRole === 'housing_manager' && 'Pantau operasional dan setujui permintaan'}
                        {userRole === 'sales_staff' && 'Kelola penjualan dan rumah tersedia'}
                        {userRole === 'resident' && 'Akses informasi rumah dan layanan Anda'}
                    </p>
                </div>

                {/* Statistics Cards */}
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    {userRole === 'administrator' && (
                        <>
                            <div className="bg-white p-6 rounded-xl shadow-sm border">
                                <div className="flex items-center">
                                    <div className="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <span className="text-2xl">🏠</span>
                                    </div>
                                    <div className="ml-4">
                                        <p className="text-sm font-medium text-gray-600">Total Rumah</p>
                                        <p className="text-2xl font-bold text-gray-900">{stats.total_houses}</p>
                                    </div>
                                </div>
                            </div>
                            <div className="bg-white p-6 rounded-xl shadow-sm border">
                                <div className="flex items-center">
                                    <div className="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                        <span className="text-2xl">✅</span>
                                    </div>
                                    <div className="ml-4">
                                        <p className="text-sm font-medium text-gray-600">Tersedia</p>
                                        <p className="text-2xl font-bold text-green-600">{stats.available_houses}</p>
                                    </div>
                                </div>
                            </div>
                            <div className="bg-white p-6 rounded-xl shadow-sm border">
                                <div className="flex items-center">
                                    <div className="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                        <span className="text-2xl">👥</span>
                                    </div>
                                    <div className="ml-4">
                                        <p className="text-sm font-medium text-gray-600">Penghuni Aktif</p>
                                        <p className="text-2xl font-bold text-blue-600">{stats.total_residents}</p>
                                    </div>
                                </div>
                            </div>
                            <div className="bg-white p-6 rounded-xl shadow-sm border">
                                <div className="flex items-center">
                                    <div className="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                                        <span className="text-2xl">🛠️</span>
                                    </div>
                                    <div className="ml-4">
                                        <p className="text-sm font-medium text-gray-600">Keluhan Baru</p>
                                        <p className="text-2xl font-bold text-red-600">{stats.new_complaints}</p>
                                    </div>
                                </div>
                            </div>
                        </>
                    )}

                    {userRole === 'housing_manager' && (
                        <>
                            <div className="bg-white p-6 rounded-xl shadow-sm border">
                                <div className="flex items-center">
                                    <div className="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <span className="text-2xl">🏠</span>
                                    </div>
                                    <div className="ml-4">
                                        <p className="text-sm font-medium text-gray-600">Total Rumah</p>
                                        <p className="text-2xl font-bold text-gray-900">{stats.total_houses}</p>
                                    </div>
                                </div>
                            </div>
                            <div className="bg-white p-6 rounded-xl shadow-sm border">
                                <div className="flex items-center">
                                    <div className="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                        <span className="text-2xl">💰</span>
                                    </div>
                                    <div className="ml-4">
                                        <p className="text-sm font-medium text-gray-600">Pembayaran Tertunda</p>
                                        <p className="text-2xl font-bold text-yellow-600">{stats.pending_payments}</p>
                                    </div>
                                </div>
                            </div>
                            <div className="bg-white p-6 rounded-xl shadow-sm border">
                                <div className="flex items-center">
                                    <div className="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                                        <span className="text-2xl">⚠️</span>
                                    </div>
                                    <div className="ml-4">
                                        <p className="text-sm font-medium text-gray-600">Pembayaran Terlambat</p>
                                        <p className="text-2xl font-bold text-red-600">{stats.overdue_payments}</p>
                                    </div>
                                </div>
                            </div>
                            <div className="bg-white p-6 rounded-xl shadow-sm border">
                                <div className="flex items-center">
                                    <div className="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <span className="text-2xl">📋</span>
                                    </div>
                                    <div className="ml-4">
                                        <p className="text-sm font-medium text-gray-600">Keluhan Ditugaskan</p>
                                        <p className="text-2xl font-bold text-purple-600">{stats.assigned_complaints}</p>
                                    </div>
                                </div>
                            </div>
                        </>
                    )}

                    {userRole === 'sales_staff' && (
                        <>
                            <div className="bg-white p-6 rounded-xl shadow-sm border">
                                <div className="flex items-center">
                                    <div className="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                        <span className="text-2xl">🏠</span>
                                    </div>
                                    <div className="ml-4">
                                        <p className="text-sm font-medium text-gray-600">Rumah Tersedia</p>
                                        <p className="text-2xl font-bold text-green-600">{stats.available_houses}</p>
                                    </div>
                                </div>
                            </div>
                            <div className="bg-white p-6 rounded-xl shadow-sm border">
                                <div className="flex items-center">
                                    <div className="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <span className="text-2xl">✅</span>
                                    </div>
                                    <div className="ml-4">
                                        <p className="text-sm font-medium text-gray-600">Rumah Terjual</p>
                                        <p className="text-2xl font-bold text-blue-600">{stats.sold_houses}</p>
                                    </div>
                                </div>
                            </div>
                            <div className="bg-white p-6 rounded-xl shadow-sm border col-span-2">
                                <div className="flex items-center">
                                    <div className="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                        <span className="text-2xl">💰</span>
                                    </div>
                                    <div className="ml-4">
                                        <p className="text-sm font-medium text-gray-600">Total Nilai Penjualan</p>
                                        <p className="text-2xl font-bold text-yellow-600">
                                            {formatCurrency(stats.total_sales_value || 0)}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </>
                    )}

                    {userRole === 'resident' && (
                        <>
                            <div className="bg-white p-6 rounded-xl shadow-sm border">
                                <div className="flex items-center">
                                    <div className="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                        <span className="text-2xl">💰</span>
                                    </div>
                                    <div className="ml-4">
                                        <p className="text-sm font-medium text-gray-600">Pembayaran Tertunda</p>
                                        <p className="text-2xl font-bold text-yellow-600">{stats.my_payments_pending}</p>
                                    </div>
                                </div>
                            </div>
                            <div className="bg-white p-6 rounded-xl shadow-sm border">
                                <div className="flex items-center">
                                    <div className="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                                        <span className="text-2xl">⚠️</span>
                                    </div>
                                    <div className="ml-4">
                                        <p className="text-sm font-medium text-gray-600">Pembayaran Terlambat</p>
                                        <p className="text-2xl font-bold text-red-600">{stats.my_payments_overdue}</p>
                                    </div>
                                </div>
                            </div>
                            <div className="bg-white p-6 rounded-xl shadow-sm border">
                                <div className="flex items-center">
                                    <div className="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <span className="text-2xl">🛠️</span>
                                    </div>
                                    <div className="ml-4">
                                        <p className="text-sm font-medium text-gray-600">Keluhan Aktif</p>
                                        <p className="text-2xl font-bold text-blue-600">{stats.my_complaints_open}</p>
                                    </div>
                                </div>
                            </div>
                            <div className="bg-white p-6 rounded-xl shadow-sm border">
                                <div className="flex items-center justify-center h-full">
                                    <Link href="/complaints/create">
                                        <Button className="w-full">
                                            <span className="mr-2">➕</span>
                                            Ajukan Keluhan Baru
                                        </Button>
                                    </Link>
                                </div>
                            </div>
                        </>
                    )}
                </div>

                {/* Content Sections Based on Role */}
                <div className="grid lg:grid-cols-2 gap-6">
                    {(userRole === 'administrator' || userRole === 'housing_manager') && (
                        <>
                            {/* Recent Complaints */}
                            <div className="bg-white rounded-xl shadow-sm border">
                                <div className="p-6 border-b border-gray-200">
                                    <div className="flex items-center justify-between">
                                        <h3 className="text-lg font-semibold text-gray-900">
                                            🛠️ Keluhan Terbaru
                                        </h3>
                                        <Link href="/complaints">
                                            <Button variant="outline" size="sm">Lihat Semua</Button>
                                        </Link>
                                    </div>
                                </div>
                                <div className="p-6">
                                    {recentComplaints.length > 0 ? (
                                        <div className="space-y-4">
                                            {recentComplaints.map((complaint) => (
                                                <div key={complaint.id} className="flex items-start space-x-3 p-3 hover:bg-gray-50 rounded-lg">
                                                    <div className="flex-1">
                                                        <div className="flex items-center justify-between">
                                                            <h4 className="text-sm font-medium text-gray-900">
                                                                {complaint.title}
                                                            </h4>
                                                            <div className="flex space-x-2">
                                                                <span className={`px-2 py-1 text-xs rounded-full ${getStatusBadge(complaint.status)}`}>
                                                                    {complaint.status}
                                                                </span>
                                                                <span className={`px-2 py-1 text-xs rounded-full ${getPriorityBadge(complaint.priority)}`}>
                                                                    {complaint.priority}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <p className="text-sm text-gray-600 mt-1">
                                                            {complaint.house.block_unit} - {complaint.resident.name}
                                                        </p>
                                                        <p className="text-xs text-gray-500 mt-1">
                                                            {formatDate(complaint.created_at)}
                                                        </p>
                                                    </div>
                                                </div>
                                            ))}
                                        </div>
                                    ) : (
                                        <p className="text-gray-500 text-center py-4">Tidak ada keluhan terbaru</p>
                                    )}
                                </div>
                            </div>

                            {/* Overdue Payments */}
                            <div className="bg-white rounded-xl shadow-sm border">
                                <div className="p-6 border-b border-gray-200">
                                    <div className="flex items-center justify-between">
                                        <h3 className="text-lg font-semibold text-gray-900">
                                            ⚠️ Pembayaran Terlambat
                                        </h3>
                                        <Link href="/payments?status=overdue">
                                            <Button variant="outline" size="sm">Lihat Semua</Button>
                                        </Link>
                                    </div>
                                </div>
                                <div className="p-6">
                                    {overduePayments.length > 0 ? (
                                        <div className="space-y-4">
                                            {overduePayments.map((payment) => (
                                                <div key={payment.id} className="flex items-start space-x-3 p-3 hover:bg-gray-50 rounded-lg">
                                                    <div className="flex-1">
                                                        <div className="flex items-center justify-between">
                                                            <h4 className="text-sm font-medium text-gray-900">
                                                                {payment.type}
                                                            </h4>
                                                            <span className="text-sm font-semibold text-red-600">
                                                                {formatCurrency(payment.amount)}
                                                            </span>
                                                        </div>
                                                        <p className="text-sm text-gray-600 mt-1">
                                                            {payment.house.block_unit} - {payment.resident?.name}
                                                        </p>
                                                        <p className="text-xs text-red-500 mt-1">
                                                            Jatuh tempo: {formatDate(payment.due_date)}
                                                        </p>
                                                    </div>
                                                </div>
                                            ))}
                                        </div>
                                    ) : (
                                        <p className="text-gray-500 text-center py-4">Tidak ada pembayaran terlambat</p>
                                    )}
                                </div>
                            </div>
                        </>
                    )}

                    {userRole === 'sales_staff' && (
                        <div className="lg:col-span-2">
                            <div className="bg-white rounded-xl shadow-sm border">
                                <div className="p-6 border-b border-gray-200">
                                    <div className="flex items-center justify-between">
                                        <h3 className="text-lg font-semibold text-gray-900">
                                            🏠 Rumah Tersedia
                                        </h3>
                                        <Link href="/houses?status=available">
                                            <Button variant="outline" size="sm">Lihat Semua</Button>
                                        </Link>
                                    </div>
                                </div>
                                <div className="p-6">
                                    {availableHouses.length > 0 ? (
                                        <div className="grid md:grid-cols-2 gap-4">
                                            {availableHouses.map((house) => (
                                                <div key={house.id} className="border rounded-lg p-4 hover:shadow-md transition-shadow">
                                                    <div className="flex items-center justify-between mb-2">
                                                        <h4 className="font-medium text-gray-900">{house.block_unit}</h4>
                                                        <span className="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                                            Tersedia
                                                        </span>
                                                    </div>
                                                    <p className="text-sm text-gray-600 mb-2">{house.address}</p>
                                                    <p className="text-sm text-gray-600 mb-2">
                                                        {house.type} • {house.bedrooms} KT • {house.bathrooms} KM
                                                    </p>
                                                    <p className="text-lg font-semibold text-blue-600 mb-3">
                                                        {formatCurrency(house.price)}
                                                    </p>
                                                    <Link href={`/houses/${house.id}`}>
                                                        <Button size="sm" className="w-full">
                                                            Lihat Detail
                                                        </Button>
                                                    </Link>
                                                </div>
                                            ))}
                                        </div>
                                    ) : (
                                        <p className="text-gray-500 text-center py-4">Tidak ada rumah tersedia</p>
                                    )}
                                </div>
                            </div>
                        </div>
                    )}

                    {userRole === 'resident' && (
                        <>
                            {/* My Payments */}
                            <div className="bg-white rounded-xl shadow-sm border">
                                <div className="p-6 border-b border-gray-200">
                                    <div className="flex items-center justify-between">
                                        <h3 className="text-lg font-semibold text-gray-900">
                                            💰 Riwayat Pembayaran
                                        </h3>
                                        <Link href="/payments">
                                            <Button variant="outline" size="sm">Lihat Semua</Button>
                                        </Link>
                                    </div>
                                </div>
                                <div className="p-6">
                                    {myPayments.length > 0 ? (
                                        <div className="space-y-4">
                                            {myPayments.map((payment) => (
                                                <div key={payment.id} className="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg">
                                                    <div>
                                                        <h4 className="text-sm font-medium text-gray-900">
                                                            {payment.type}
                                                        </h4>
                                                        <p className="text-xs text-gray-500">
                                                            Jatuh tempo: {formatDate(payment.due_date)}
                                                        </p>
                                                    </div>
                                                    <div className="text-right">
                                                        <p className="text-sm font-semibold text-gray-900">
                                                            {formatCurrency(payment.amount)}
                                                        </p>
                                                        <span className={`px-2 py-1 text-xs rounded-full ${getStatusBadge(payment.status)}`}>
                                                            {payment.status}
                                                        </span>
                                                    </div>
                                                </div>
                                            ))}
                                        </div>
                                    ) : (
                                        <p className="text-gray-500 text-center py-4">Tidak ada riwayat pembayaran</p>
                                    )}
                                </div>
                            </div>

                            {/* My Complaints */}
                            <div className="bg-white rounded-xl shadow-sm border">
                                <div className="p-6 border-b border-gray-200">
                                    <div className="flex items-center justify-between">
                                        <h3 className="text-lg font-semibold text-gray-900">
                                            🛠️ Keluhan Saya
                                        </h3>
                                        <Link href="/complaints">
                                            <Button variant="outline" size="sm">Lihat Semua</Button>
                                        </Link>
                                    </div>
                                </div>
                                <div className="p-6">
                                    {myComplaints.length > 0 ? (
                                        <div className="space-y-4">
                                            {myComplaints.map((complaint) => (
                                                <div key={complaint.id} className="p-3 hover:bg-gray-50 rounded-lg">
                                                    <div className="flex items-center justify-between mb-2">
                                                        <h4 className="text-sm font-medium text-gray-900">
                                                            {complaint.title}
                                                        </h4>
                                                        <div className="flex space-x-2">
                                                            <span className={`px-2 py-1 text-xs rounded-full ${getStatusBadge(complaint.status)}`}>
                                                                {complaint.status}
                                                            </span>
                                                            <span className={`px-2 py-1 text-xs rounded-full ${getPriorityBadge(complaint.priority)}`}>
                                                                {complaint.priority}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <p className="text-xs text-gray-500">
                                                        {formatDate(complaint.created_at)}
                                                        {complaint.assigned_user && ` • Ditangani: ${complaint.assigned_user.name}`}
                                                    </p>
                                                </div>
                                            ))}
                                        </div>
                                    ) : (
                                        <p className="text-gray-500 text-center py-4">Belum ada keluhan</p>
                                    )}
                                </div>
                            </div>
                        </>
                    )}
                </div>

                {/* Quick Actions */}
                <div className="bg-white rounded-xl shadow-sm border p-6">
                    <h3 className="text-lg font-semibold text-gray-900 mb-4">
                        ⚡ Aksi Cepat
                    </h3>
                    <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                        {userRole === 'administrator' && (
                            <>
                                <Link href="/houses/create">
                                    <Button variant="outline" className="w-full">
                                        <span className="mr-2">🏠</span>
                                        Tambah Rumah
                                    </Button>
                                </Link>
                                <Link href="/residents/create">
                                    <Button variant="outline" className="w-full">
                                        <span className="mr-2">👥</span>
                                        Tambah Penghuni
                                    </Button>
                                </Link>
                                <Link href="/payments/create">
                                    <Button variant="outline" className="w-full">
                                        <span className="mr-2">💰</span>
                                        Tambah Pembayaran
                                    </Button>
                                </Link>
                                <Link href="/complaints">
                                    <Button variant="outline" className="w-full">
                                        <span className="mr-2">🛠️</span>
                                        Kelola Keluhan
                                    </Button>
                                </Link>
                            </>
                        )}
                        
                        {userRole === 'housing_manager' && (
                            <>
                                <Link href="/houses">
                                    <Button variant="outline" className="w-full">
                                        <span className="mr-2">🏠</span>
                                        Lihat Rumah
                                    </Button>
                                </Link>
                                <Link href="/residents">
                                    <Button variant="outline" className="w-full">
                                        <span className="mr-2">👥</span>
                                        Data Penghuni
                                    </Button>
                                </Link>
                                <Link href="/payments">
                                    <Button variant="outline" className="w-full">
                                        <span className="mr-2">💰</span>
                                        Pembayaran
                                    </Button>
                                </Link>
                                <Link href="/complaints">
                                    <Button variant="outline" className="w-full">
                                        <span className="mr-2">🛠️</span>
                                        Keluhan
                                    </Button>
                                </Link>
                            </>
                        )}
                        
                        {userRole === 'sales_staff' && (
                            <>
                                <Link href="/houses?status=available">
                                    <Button variant="outline" className="w-full">
                                        <span className="mr-2">🏠</span>
                                        Rumah Tersedia
                                    </Button>
                                </Link>
                                <Link href="/houses?status=sold">
                                    <Button variant="outline" className="w-full">
                                        <span className="mr-2">✅</span>
                                        Rumah Terjual
                                    </Button>
                                </Link>
                            </>
                        )}
                        
                        {userRole === 'resident' && (
                            <>
                                <Link href="/complaints/create">
                                    <Button variant="outline" className="w-full">
                                        <span className="mr-2">🛠️</span>
                                        Ajukan Keluhan
                                    </Button>
                                </Link>
                                <Link href="/payments">
                                    <Button variant="outline" className="w-full">
                                        <span className="mr-2">💰</span>
                                        Riwayat Pembayaran
                                    </Button>
                                </Link>
                            </>
                        )}
                    </div>
                </div>
            </div>
        </AppShell>
    );
}