<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Riwayat Approval
        </h2>
    </x-slot>

    <div class="p-6 max-w-7xl mx-auto">

        <!-- FILTER -->
        <form method="GET" class="flex flex-wrap gap-2 mb-4">

            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..."
                class="px-3 py-2 border rounded-lg text-sm">

            <select name="status" class="px-3 py-2 border rounded-lg text-sm">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
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

        <!-- TABLE -->
        <div class="bg-white rounded-xl shadow-sm border overflow-hidden">

            <div class="overflow-x-auto">


                <table class="w-full text-sm">
                    <thead class="bg-blue-50 text-gray-700">
                        <tr>
                            <th class="p-3">No</th>
                            <th class="p-3 text-left">Booking</th>
                            <th class="p-3 text-left">Vehicle</th>
                            <th class="p-3 text-left">Driver</th>
                            <th class="p-3 text-left">Tanggal</th>
                            <th class="p-3 text-left">Level</th>
                            <th class="p-3 text-left">Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($approvals as $i => $a)
                            <tr class="border-t hover:bg-gray-50">

                                <td class="p-3">
                                    {{ $approvals->firstItem() + $i }}
                                </td>

                                <td class="p-3">
                                    #{{ $a->booking->id }}
                                </td>

                                <td class="p-3">
                                    {{ $a->booking->vehicle->plate_number ?? '-' }}
                                </td>

                                <td class="p-3">
                                    {{ $a->booking->driver->name ?? '-' }}
                                </td>

                                <td class="p-3">
                                    {{ $a->booking->start_date }}
                                </td>

                                <td class="p-3">
                                    L{{ $a->level }}
                                </td>

                                <td class="p-3">
                                    <span
                                        class="px-2 py-1 text-xs rounded-full
                                    @if ($a->status == 'pending') bg-gray-100 text-gray-700
                                    @elseif($a->status == 'approved') bg-green-100 text-green-700
                                    @elseif($a->status == 'rejected') bg-red-100 text-red-700 @endif">
                                        {{ ucfirst($a->status) }}
                                    </span>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-6 text-center text-gray-500">
                                    Belum ada riwayat approval
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- PAGINATION -->
        <div class="mt-4">
            {{ $approvals->links() }}
        </div>

    </div>
</x-app-layout>
