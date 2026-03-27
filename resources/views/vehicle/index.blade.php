<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Vehicle
        </h2>
    </x-slot>

    <div class="p-6 max-w-7xl mx-auto">

        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Kelola Kendaraan</h3>
                <p class="text-sm text-gray-500">Manage data kendaraan perusahaan</p>
            </div>

            <button onclick="openCreateModal()"
                class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                <i class="fa-solid fa-plus"></i>
                <span>Tambah</span>
            </button>
        </div>

        <form method="GET" class="flex flex-col sm:flex-row sm:flex-wrap gap-2 mb-4">

            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search plate..."
                class="px-3 py-2 border rounded-lg text-sm w-full sm:w-48">

            <select name="status" class="px-3 py-2 border rounded-lg text-sm">
                <option value="">All Status</option>
                <option value="available">Available</option>
                <option value="in_use">In Use</option>
                <option value="maintenance">Maintenance</option>
            </select>

            <select name="type" class="px-3 py-2 border rounded-lg text-sm">
                <option value="">All Type</option>
                <option value="people">People</option>
                <option value="goods">Goods</option>
            </select>

            <select name="sort" class="px-3 py-2 border rounded-lg text-sm">
                <option value="latest">Latest</option>
                <option value="oldest">Oldest</option>
            </select>

            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">
                Filter
            </button>

        </form>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden border">

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-blue-50 text-gray-700">
                        <tr>
                            <th class="p-3 text-left">Plat</th>
                            <th class="p-3 text-left">Tipe</th>
                            <th class="p-3 text-left">Ownership</th>
                            <th class="p-3 text-left">Status</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($groupedVehicles as $status => $items)
                            <!-- HEADER GROUP -->
                            <tr class="bg-gray-50">
                                <td colspan="5" class="p-3 text-sm font-semibold text-gray-500 uppercase">
                                    {{ str_replace('_', ' ', $status) }}
                                </td>
                            </tr>

                            @foreach ($items as $v)
                                <tr class="border-t hover:bg-gray-50 transition">

                                    <td class="p-3 font-medium text-gray-800">
                                        {{ $v->plate_number }}
                                    </td>

                                    <td class="p-3 capitalize">
                                        {{ $v->type }}
                                    </td>

                                    <td class="p-3 capitalize">
                                        {{ $v->ownership }}
                                    </td>

                                    <td class="p-3">
                                        <span
                                            class="px-2 py-1 text-xs rounded-full
                                    @if ($v->status == 'available') bg-green-100 text-green-700
                                    @elseif($v->status == 'in_use') bg-yellow-100 text-yellow-700
                                    @else bg-red-100 text-red-700 @endif">
                                            {{ $v->status }}
                                        </span>
                                    </td>

                                    <td class="p-3">
                                        <div class="flex justify-center gap-3">

                                            <div class="flex justify-center gap-4">

                                                <button
                                                    onclick="openEditModal(
            {{ $v->id }},
            {{ json_encode($v->plate_number) }},
            {{ json_encode($v->type) }},
            {{ json_encode($v->ownership) }},
            {{ json_encode($v->status) }}
        )"
                                                    class="flex items-center gap-1 text-blue-600 hover:text-blue-800 text-sm transition">

                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                    <span>Edit</span>
                                                </button>

                                                <button onclick="openDeleteModal({{ $v->id }})"
                                                    class="flex items-center gap-1 text-red-600 hover:text-red-800 text-sm transition">

                                                    <i class="fa-solid fa-trash"></i>
                                                    <span>Hapus</span>
                                                </button>

                                            </div>

                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="5" class="p-6 text-center text-gray-500">
                                    Belum ada data kendaraan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $vehicles->links() }}
        </div>

    </div>

    @include('vehicle.modal.create')
    @include('vehicle.modal.edit')
    @include('vehicle.modal.delete')

    @push('scripts')
        @vite('resources/js/vehicle.js')
    @endpush

</x-app-layout>
