import React from 'react';
import { Head, Link } from '@inertiajs/react';
import { Button } from '@/components/ui/button';

interface Props {
    auth?: {
        user: {
            id: number;
            name: string;
            email: string;
            role: string;
        };
    };
    [key: string]: unknown;
}

export default function Welcome({ auth }: Props) {
    return (
        <>
            <Head title="Sistem Manajemen Perumahan" />
            
            <div className="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50">
                {/* Header */}
                <header className="bg-white/80 backdrop-blur-sm border-b border-gray-200 sticky top-0 z-50">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="flex justify-between items-center py-4">
                            <div className="flex items-center space-x-3">
                                <div className="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center">
                                    <span className="text-white font-bold text-lg">🏘️</span>
                                </div>
                                <div>
                                    <h1 className="text-xl font-bold text-gray-900">HousingMS</h1>
                                    <p className="text-sm text-gray-600">Housing Management System</p>
                                </div>
                            </div>
                            
                            <nav className="flex items-center space-x-4">
                                {auth?.user ? (
                                    <div className="flex items-center space-x-4">
                                        <span className="text-gray-700">
                                            Halo, {auth.user.name}
                                        </span>
                                        <Link href="/dashboard">
                                            <Button>Dashboard</Button>
                                        </Link>
                                    </div>
                                ) : (
                                    <div className="flex items-center space-x-3">
                                        <Link href="/login">
                                            <Button variant="ghost">Masuk</Button>
                                        </Link>
                                        <Link href="/register">
                                            <Button>Daftar</Button>
                                        </Link>
                                    </div>
                                )}
                            </nav>
                        </div>
                    </div>
                </header>

                {/* Hero Section */}
                <section className="relative py-20 px-4 sm:px-6 lg:px-8">
                    <div className="max-w-7xl mx-auto">
                        <div className="text-center mb-16">
                            <h1 className="text-5xl font-bold text-gray-900 mb-6">
                                🏘️ Sistem Manajemen Perumahan
                            </h1>
                            <p className="text-xl text-gray-600 max-w-3xl mx-auto mb-8">
                                Solusi lengkap untuk mengelola data rumah, penghuni, pembayaran, dan keluhan 
                                dengan sistem pengguna multi-level dan menu dinamis yang mudah digunakan.
                            </p>
                            
                            {!auth?.user && (
                                <div className="flex justify-center space-x-4">
                                    <Link href="/register">
                                        <Button size="lg" className="px-8 py-3">
                                            🚀 Mulai Sekarang
                                        </Button>
                                    </Link>
                                    <Link href="/login">
                                        <Button variant="outline" size="lg" className="px-8 py-3">
                                            Masuk ke Akun
                                        </Button>
                                    </Link>
                                </div>
                            )}
                        </div>
                    </div>
                </section>

                {/* Features Section */}
                <section className="py-20 bg-white/50">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="text-center mb-16">
                            <h2 className="text-3xl font-bold text-gray-900 mb-4">
                                ✨ Fitur Unggulan
                            </h2>
                            <p className="text-lg text-gray-600">
                                Semua yang Anda butuhkan untuk mengelola perumahan dengan efisien
                            </p>
                        </div>

                        <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                            {/* House Management */}
                            <div className="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-shadow">
                                <div className="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-4">
                                    <span className="text-2xl">🏠</span>
                                </div>
                                <h3 className="text-xl font-semibold text-gray-900 mb-3">
                                    Manajemen Rumah
                                </h3>
                                <p className="text-gray-600 mb-4">
                                    Kelola data lengkap rumah: alamat, tipe, luas, harga, status, dan detail lainnya
                                </p>
                                <ul className="text-sm text-gray-500 space-y-1">
                                    <li>• Data properti lengkap</li>
                                    <li>• Status tersedia/terjual/dihuni</li>
                                    <li>• Pencarian & filter</li>
                                </ul>
                            </div>

                            {/* Resident Management */}
                            <div className="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-shadow">
                                <div className="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mb-4">
                                    <span className="text-2xl">👥</span>
                                </div>
                                <h3 className="text-xl font-semibold text-gray-900 mb-3">
                                    Data Penghuni
                                </h3>
                                <p className="text-gray-600 mb-4">
                                    Catat dan kelola informasi penghuni serta hubungannya dengan rumah
                                </p>
                                <ul className="text-sm text-gray-500 space-y-1">
                                    <li>• Profil penghuni lengkap</li>
                                    <li>• Riwayat tinggal</li>
                                    <li>• Kontak & komunikasi</li>
                                </ul>
                            </div>

                            {/* Payment Management */}
                            <div className="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-shadow">
                                <div className="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center mb-4">
                                    <span className="text-2xl">💰</span>
                                </div>
                                <h3 className="text-xl font-semibold text-gray-900 mb-3">
                                    Pembayaran
                                </h3>
                                <p className="text-gray-600 mb-4">
                                    Pantau pembayaran iuran, utilitas, dan jatuh tempo dengan mudah
                                </p>
                                <ul className="text-sm text-gray-500 space-y-1">
                                    <li>• Riwayat pembayaran</li>
                                    <li>• Status & jatuh tempo</li>
                                    <li>• Laporan keuangan</li>
                                </ul>
                            </div>

                            {/* Complaint Management */}
                            <div className="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-shadow">
                                <div className="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center mb-4">
                                    <span className="text-2xl">🛠️</span>
                                </div>
                                <h3 className="text-xl font-semibold text-gray-900 mb-3">
                                    Keluhan & Perbaikan
                                </h3>
                                <p className="text-gray-600 mb-4">
                                    Sistem ticketing untuk keluhan dan permintaan perbaikan
                                </p>
                                <ul className="text-sm text-gray-500 space-y-1">
                                    <li>• Pengajuan keluhan</li>
                                    <li>• Tracking status</li>
                                    <li>• Assignment ke staf</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </section>

                {/* User Roles Section */}
                <section className="py-20">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="text-center mb-16">
                            <h2 className="text-3xl font-bold text-gray-900 mb-4">
                                👤 Sistem Multi-Level
                            </h2>
                            <p className="text-lg text-gray-600">
                                Menu dan akses disesuaikan dengan peran pengguna
                            </p>
                        </div>

                        <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div className="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-2xl border">
                                <div className="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center mb-4">
                                    <span className="text-white font-bold">👑</span>
                                </div>
                                <h3 className="font-semibold text-gray-900 mb-2">Administrator</h3>
                                <p className="text-sm text-gray-600">
                                    Akses penuh: kelola semua data, pengguna, dan pengaturan sistem
                                </p>
                            </div>

                            <div className="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-2xl border">
                                <div className="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center mb-4">
                                    <span className="text-white font-bold">👨‍💼</span>
                                </div>
                                <h3 className="font-semibold text-gray-900 mb-2">Manajer Perumahan</h3>
                                <p className="text-sm text-gray-600">
                                    Lihat semua data, setujui keluhan, buat laporan
                                </p>
                            </div>

                            <div className="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-2xl border">
                                <div className="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center mb-4">
                                    <span className="text-white font-bold">🏢</span>
                                </div>
                                <h3 className="font-semibold text-gray-900 mb-2">Staf Penjualan</h3>
                                <p className="text-sm text-gray-600">
                                    Akses rumah tersedia, catat pembeli, update status penjualan
                                </p>
                            </div>

                            <div className="bg-gradient-to-br from-orange-50 to-orange-100 p-6 rounded-2xl border">
                                <div className="w-10 h-10 bg-orange-600 rounded-lg flex items-center justify-center mb-4">
                                    <span className="text-white font-bold">🏠</span>
                                </div>
                                <h3 className="font-semibold text-gray-900 mb-2">Penghuni</h3>
                                <p className="text-sm text-gray-600">
                                    Lihat data rumah sendiri, ajukan keluhan, pantau pembayaran
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                {/* Screenshot/Preview Section */}
                <section className="py-20 bg-gray-50">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="text-center mb-16">
                            <h2 className="text-3xl font-bold text-gray-900 mb-4">
                                📊 Interface yang Intuitif
                            </h2>
                            <p className="text-lg text-gray-600">
                                Dashboard modern dengan visualisasi data yang jelas
                            </p>
                        </div>

                        <div className="grid md:grid-cols-3 gap-8">
                            {/* Dashboard Preview */}
                            <div className="bg-white rounded-2xl p-6 shadow-lg">
                                <div className="h-40 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg mb-4 flex items-center justify-center">
                                    <div className="text-center">
                                        <div className="text-3xl mb-2">📈</div>
                                        <div className="text-sm text-gray-600">Dashboard Overview</div>
                                    </div>
                                </div>
                                <h3 className="font-semibold mb-2">Dashboard Interaktif</h3>
                                <p className="text-sm text-gray-600">
                                    Statistik realtime, grafik, dan ringkasan data penting
                                </p>
                            </div>

                            {/* House Management Preview */}
                            <div className="bg-white rounded-2xl p-6 shadow-lg">
                                <div className="h-40 bg-gradient-to-br from-green-100 to-green-200 rounded-lg mb-4 flex items-center justify-center">
                                    <div className="text-center">
                                        <div className="text-3xl mb-2">🏡</div>
                                        <div className="text-sm text-gray-600">Property Management</div>
                                    </div>
                                </div>
                                <h3 className="font-semibold mb-2">Kelola Properti</h3>
                                <p className="text-sm text-gray-600">
                                    Interface mudah untuk menambah, edit, dan pantau rumah
                                </p>
                            </div>

                            {/* Reports Preview */}
                            <div className="bg-white rounded-2xl p-6 shadow-lg">
                                <div className="h-40 bg-gradient-to-br from-purple-100 to-purple-200 rounded-lg mb-4 flex items-center justify-center">
                                    <div className="text-center">
                                        <div className="text-3xl mb-2">📋</div>
                                        <div className="text-sm text-gray-600">Reports & Analytics</div>
                                    </div>
                                </div>
                                <h3 className="font-semibold mb-2">Laporan Lengkap</h3>
                                <p className="text-sm text-gray-600">
                                    Export data, analisis trend, dan laporan keuangan
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                {/* CTA Section */}
                {!auth?.user && (
                    <section className="py-20 bg-gradient-to-r from-blue-600 to-green-600">
                        <div className="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
                            <h2 className="text-3xl font-bold text-white mb-4">
                                🚀 Siap Mengoptimalkan Manajemen Perumahan Anda?
                            </h2>
                            <p className="text-xl text-blue-100 mb-8">
                                Bergabunglah dengan sistem yang sudah dipercaya oleh banyak pengelola perumahan
                            </p>
                            <div className="flex justify-center space-x-4">
                                <Link href="/register">
                                    <Button size="lg" className="bg-white text-blue-600 hover:bg-gray-100 px-8 py-3">
                                        Daftar Gratis
                                    </Button>
                                </Link>
                                <Link href="/login">
                                    <Button variant="outline" size="lg" className="border-white text-white hover:bg-white hover:text-blue-600 px-8 py-3">
                                        Masuk Sekarang
                                    </Button>
                                </Link>
                            </div>
                        </div>
                    </section>
                )}

                {/* Footer */}
                <footer className="bg-gray-900 text-white py-12">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="text-center">
                            <div className="flex justify-center items-center space-x-3 mb-4">
                                <div className="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                                    <span className="text-white font-bold">🏘️</span>
                                </div>
                                <span className="text-xl font-bold">HousingMS</span>
                            </div>
                            <p className="text-gray-400 mb-4">
                                Sistem Manajemen Perumahan Modern & Terintegrasi
                            </p>
                            <p className="text-sm text-gray-500">
                                © 2024 Housing Management System. Semua hak dilindungi.
                            </p>
                        </div>
                    </div>
                </footer>
            </div>
        </>
    );
}