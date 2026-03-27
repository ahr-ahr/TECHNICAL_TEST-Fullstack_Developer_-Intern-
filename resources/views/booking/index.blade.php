<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Driver
        </h2>
    </x-slot>

    <div class="p-6 max-w-7xl mx-auto">

        <div class="overflow-x-auto">

            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Kelola Booking</h3>
                    <p class="text-sm text-gray-500">Manage data booking perusahaan</p>
                </div>

                <button onclick="openCreateModal()"
                    class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    <i class="fa-solid fa-plus"></i>
                    <span>Tambah</span>
                </button>
            </div>

            <form method="GET" class="flex flex-col sm:flex-row sm:flex-wrap gap-2 mb-4">

                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..."
                    class="px-3 py-2 border rounded-lg text-sm w-full sm:w-48">

                <select name="status" class="px-3 py-2 border rounded-lg text-sm w-full sm:w-auto">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                    <option value="in_use">In Use</option>
                    <option value="completed">Completed</option>
                </select>

                <input type="date" name="date" value="{{ request('date') }}"
                    class="px-3 py-2 border rounded-lg text-sm">

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
                                <th class="p-3 text-left">Driver</th>
                                <th class="p-3 text-left">Tanggal</th>
                                <th class="p-3 text-left">Tujuan</th>
                                <th class="p-3 text-left">Status</th>
                                <th class="p-3 text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($groupedBookings as $date => $items)
                                <tr class="bg-gray-50">
                                    <td colspan="6" class="p-3 text-sm font-semibold text-gray-500">
                                        {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
                                    </td>
                                </tr>
                                @foreach ($items as $b)
                                    <tr class="border-t hover:bg-gray-50">

                                        <td class="p-3">
                                            {{ $b->vehicle->plate_number ?? '-' }}
                                        </td>

                                        <td class="p-3">
                                            {{ $b->driver->name ?? '-' }}
                                        </td>

                                        <td class="p-3">
                                            {{ $b->start_date }} - {{ $b->end_date }}
                                        </td>

                                        <td class="p-3">
                                            {{ $b->purpose }}
                                        </td>

                                        <td class="p-3">
                                            <span
                                                class="px-2 py-1 text-xs rounded-full
                                        @if ($b->status == 'pending') bg-gray-100 text-gray-700
                                        @elseif($b->status == 'approved') bg-green-100 text-green-700
                                        @elseif($b->status == 'rejected') bg-red-100 text-red-700
                                        @elseif($b->status == 'in_use') bg-yellow-100 text-yellow-700
                                        @else bg-blue-100 text-blue-700 @endif">
                                                {{ $b->status }}
                                            </span>
                                        </td>

                                        <td class="p-3 text-center">


                                            <button onclick="openShowModal({{ $b->id }})"
                                                class="text-blue-600 hover:text-blue-800 text-sm flex items-center gap-1">
                                                <i class="fa-solid fa-eye"></i>
                                                <span>Detail</span>
                                            </button>

                                            @if ($b->status === 'pending')
                                                <button
                                                    onclick="openEditModal(
                {{ $b->id }},
                {{ json_encode($b->vehicle_id) }},
                {{ json_encode($b->driver_id) }},
                {{ json_encode($b->start_date) }},
                {{ json_encode($b->end_date) }},
                {{ json_encode($b->purpose) }}
            )"
                                                    class="text-blue-600 hover:text-blue-800 text-sm flex items-center gap-1">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                    <span>Edit</span>
                                                </button>
                                            @endif

                                            @if ($b->status === 'pending')
                                                <button onclick="openDeleteModal({{ $b->id }})"
                                                    class="text-red-600 hover:text-red-800 text-sm flex items-center gap-1">
                                                    <i class="fa-solid fa-trash"></i>
                                                    <span>Hapus</span>
                                                </button>
                                            @endif


                                        </td>

                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="6" class="p-6 text-center text-gray-500">
                                        Belum ada booking
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $bookings->links() }}
            </div>

        </div>

        @include('booking.modal.create')
        @include('booking.modal.edit')
        @include('booking.modal.delete')
        @include('booking.modal.show')

        @push('scripts')
            @vite('resources/js/booking.js')
        @endpush

</x-app-layout>
