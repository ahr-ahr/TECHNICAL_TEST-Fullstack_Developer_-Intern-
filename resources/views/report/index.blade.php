<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Laporan Booking Kendaraan
        </h2>
    </x-slot>

    <div class="p-6 max-w-7xl mx-auto space-y-6">

        <form method="GET" class="flex flex-wrap gap-2">

            <input type="date" name="start_date" value="{{ request('start_date') }}"
                class="border px-3 py-2 rounded-lg text-sm">

            <input type="date" name="end_date" value="{{ request('end_date') }}"
                class="border px-3 py-2 rounded-lg text-sm">

            <select name="status" class="border px-3 py-2 rounded-lg text-sm">
                <option value="">All Status</option>
                <option value="in_use">In Use</option>
                <option value="completed">Completed</option>
            </select>

            <select name="vehicle_id" class="border px-3 py-2 rounded-lg text-sm">
                <option value="">All Vehicle</option>
                @foreach ($vehicles as $v)
                    <option value="{{ $v->id }}" @selected(request('vehicle_id') == $v->id)>
                        {{ $v->plate_number }}
                    </option>
                @endforeach
            </select>

            <select name="driver_id" class="border px-3 py-2 rounded-lg text-sm">
                <option value="">All Driver</option>
                @foreach ($drivers as $d)
                    <option value="{{ $d->id }}" @selected(request('driver_id') == $d->id)>
                        {{ $d->name }}
                    </option>
                @endforeach
            </select>

            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">
                Generate
            </button>

            <a href="{{ route('report.excel', request()->query()) }}"
                class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm">
                Export Excel
            </a>

            <a href="{{ route('report.pdf', request()->query()) }}"
                class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm">
                Export PDF
            </a>

        </form>

        <div class="text-sm text-gray-700">
            Total Data: {{ $reports->total() }}
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden border">
            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-blue-50 text-gray-700">
                        <tr>
                            <th class="p-3 text-left">Vehicle</th>
                            <th class="p-3 text-left">Driver</th>
                            <th class="p-3 text-left">Tanggal</th>
                            <th class="p-3 text-left">Tujuan</th>
                            <th class="p-3 text-left">Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($reports as $r)
                            <tr class="border-t hover:bg-gray-50">

                                <td class="p-3 font-medium">
                                    {{ $r->vehicle->plate_number }}
                                </td>

                                <td class="p-3">
                                    {{ $r->driver->name }}
                                </td>

                                <td class="p-3">
                                    {{ $r->start_date }} - {{ $r->end_date }}
                                </td>

                                <td class="p-3">
                                    {{ $r->purpose }}
                                </td>

                                <td class="p-3">
                                    <span
                                        class="px-2 py-1 text-xs rounded-full
                                        @if ($r->status === 'approved') bg-green-100 text-green-700
                                        @elseif($r->status === 'rejected') bg-red-100 text-red-700
                                        @else bg-gray-100 text-gray-600 @endif">
                                        {{ $r->status }}
                                    </span>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-6 text-center text-gray-500">
                                    Tidak ada data
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>

            </div>
        </div>

        <!-- PAGINATION -->
        <div class="mt-4">
            {{ $reports->links() }}
        </div>

    </div>

</x-app-layout>
