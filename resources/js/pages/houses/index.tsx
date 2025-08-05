import React, { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';
import { Button } from '@/components/ui/button';

interface House {
    id: number;
    address: string;
    type: string;
    land_area: number;
    building_area: number;
    status: string;
    owner_name?: string;
    owner_phone?: string;
    handover_date?: string;
    price: number;
    bedrooms: number;
    bathrooms: number;
    block_unit: string;
    description?: string;
    current_resident?: {
        id: number;
        name: string;
        phone: string;
    };
}

interface Props {
    houses: {
        data: House[];
        links: Array<{
            url?: string;
            label: string;
            active: boolean;
        }>;
        meta: {
            total: number;
            last_page: number;
        };
    };
    filters: {
        search?: string;
        status?: string;
        type?: string;
    };
    canCreate: boolean;
    canEdit: boolean;
    [key: string]: unknown;
}

export default function HousesIndex({ houses, filters, canCreate }: Props) {
    const [searchTerm, setSearchTerm] = useState(filters.search || '');

    const formatCurrency = (amount: number) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
        }).format(amount);
    };

    const getStatusBadge = (status: string) => {
        const badges = {
            available: 'bg-green-100 text-green-800',
            occupied: 'bg-blue-100 text-blue-800',
            sold: 'bg-gray-100 text-gray-800',
        };
        return badges[status as keyof typeof badges] || 'bg-gray-100 text-gray-800';
    };

    const getStatusText = (status: string) => {
        const texts = {
            available: 'Tersedia',
            occupied: 'Dihuni',
            sold: 'Terjual',
        };
        return texts[status as keyof typeof texts] || status;
    };

    const handleSearch = (e: React.FormEvent) => {
        e.preventDefault();
        router.get('/houses', { 
            search: searchTerm,
            status: filters.status,
            type: filters.type 
        }, { 
            preserveState: true 
        });
    };

    const handleFilterChange = (key: string, value: string) => {
        router.get('/houses', { 
            ...filters,
            [key]: value 
        }, { 
            preserveState: true 
        });
    };

    return (
        <AppShell>
            <Head title="Manajemen Rumah" />
            
            <div className="space-y-6">
                {/* Header */}
                <div className="flex justify-between items-center">
                    <div>
                        <h1 className="text-2xl font-bold text-gray-900">
                            🏠 Manajemen Rumah
                        </h1>
                        <p className="text-gray-600">
                            Kelola data rumah dalam perumahan
                        </p>
                    </div>
                    {canCreate && (
                        <Link href="/houses/create">
                            <Button>
                                <span className="mr-2">➕</span>
                                Tambah Rumah
                            </Button>
                        </Link>
                    )}
                </div>

                {/* Search and Filters */}
                <div className="bg-white p-6 rounded-xl shadow-sm border">
                    <form onSubmit={handleSearch} className="flex flex-col md:flex-row gap-4">
                        <div className="flex-1">
                            <input
                                type="text"
                                placeholder="Cari berdasarkan alamat, blok/unit, atau tipe..."
                                value={searchTerm}
                                onChange={(e) => setSearchTerm(e.target.value)}
                                className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            />
                        </div>
                        <select
                            value={filters.status || ''}
                            onChange={(e) => handleFilterChange('status', e.target.value)}
                            className="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="">Semua Status</option>
                            <option value="available">Tersedia</option>
                            <option value="occupied">Dihuni</option>
                            <option value="sold">Terjual</option>
                        </select>
                        <select
                            value={filters.type || ''}
                            onChange={(e) => handleFilterChange('type', e.target.value)}
                            className="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="">Semua Tipe</option>
                            <option value="Type 36">Type 36</option>
                            <option value="Type 45">Type 45</option>
                            <option value="Type 54">Type 54</option>
                            <option value="Type 60">Type 60</option>
                            <option value="Type 70">Type 70</option>
                        </select>
                        <Button type="submit">
                            🔍 Cari
                        </Button>
                    </form>
                </div>

                {/* Statistics */}
                <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div className="bg-white p-4 rounded-lg shadow-sm border">
                        <div className="text-sm text-gray-600">Total Rumah</div>
                        <div className="text-2xl font-bold text-gray-900">{houses.meta.total}</div>
                    </div>
                    <div className="bg-white p-4 rounded-lg shadow-sm border">
                        <div className="text-sm text-gray-600">Tersedia</div>
                        <div className="text-2xl font-bold text-green-600">
                            {houses.data.filter(h => h.status === 'available').length}
                        </div>
                    </div>
                    <div className="bg-white p-4 rounded-lg shadow-sm border">
                        <div className="text-sm text-gray-600">Dihuni</div>
                        <div className="text-2xl font-bold text-blue-600">
                            {houses.data.filter(h => h.status === 'occupied').length}
                        </div>
                    </div>
                    <div className="bg-white p-4 rounded-lg shadow-sm border">
                        <div className="text-sm text-gray-600">Terjual</div>
                        <div className="text-2xl font-bold text-gray-600">
                            {houses.data.filter(h => h.status === 'sold').length}
                        </div>
                    </div>
                </div>

                {/* Houses Grid */}
                <div className="bg-white rounded-xl shadow-sm border">
                    {houses.data.length > 0 ? (
                        <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                            {houses.data.map((house) => (
                                <div key={house.id} className="border rounded-xl p-6 hover:shadow-lg transition-shadow">
                                    <div className="flex items-center justify-between mb-4">
                                        <h3 className="text-lg font-semibold text-gray-900">
                                            {house.block_unit}
                                        </h3>
                                        <span className={`px-3 py-1 text-xs rounded-full ${getStatusBadge(house.status)}`}>
                                            {getStatusText(house.status)}
                                        </span>
                                    </div>
                                    
                                    <div className="space-y-3 mb-4">
                                        <p className="text-sm text-gray-600">
                                            📍 {house.address}
                                        </p>
                                        <p className="text-sm text-gray-600">
                                            🏠 {house.type} • {house.bedrooms} KT • {house.bathrooms} KM
                                        </p>
                                        <p className="text-sm text-gray-600">
                                            📐 Tanah: {house.land_area}m² • Bangunan: {house.building_area}m²
                                        </p>
                                        {house.current_resident && (
                                            <p className="text-sm text-blue-600">
                                                👤 Penghuni: {house.current_resident.name}
                                            </p>
                                        )}
                                        {house.owner_name && (
                                            <p className="text-sm text-gray-600">
                                                👤 Pemilik: {house.owner_name}
                                            </p>
                                        )}
                                    </div>
                                    
                                    <div className="flex items-center justify-between">
                                        <div className="text-lg font-bold text-blue-600">
                                            {formatCurrency(house.price)}
                                        </div>
                                        <Link href={`/houses/${house.id}`}>
                                            <Button size="sm">
                                                Lihat Detail
                                            </Button>
                                        </Link>
                                    </div>
                                </div>
                            ))}
                        </div>
                    ) : (
                        <div className="text-center py-12">
                            <div className="text-6xl mb-4">🏠</div>
                            <h3 className="text-lg font-medium text-gray-900 mb-2">
                                Tidak ada rumah ditemukan
                            </h3>
                            <p className="text-gray-600 mb-4">
                                Coba ubah filter pencarian atau tambah rumah baru
                            </p>
                            {canCreate && (
                                <Link href="/houses/create">
                                    <Button>
                                        <span className="mr-2">➕</span>
                                        Tambah Rumah Pertama
                                    </Button>
                                </Link>
                            )}
                        </div>
                    )}
                </div>

                {/* Pagination */}
                {houses.meta.last_page > 1 && (
                    <div className="flex justify-center">
                        <div className="flex space-x-2">
                            {houses.links.map((link, index: number) => (
                                <button
                                    key={index}
                                    onClick={() => link.url && router.get(link.url)}
                                    disabled={!link.url}
                                    className={`px-3 py-2 text-sm border rounded ${
                                        link.active 
                                            ? 'bg-blue-600 text-white border-blue-600' 
                                            : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'
                                    } ${
                                        !link.url ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'
                                    }`}
                                    dangerouslySetInnerHTML={{ __html: link.label }}
                                />
                            ))}
                        </div>
                    </div>
                )}
            </div>
        </AppShell>
    );
}