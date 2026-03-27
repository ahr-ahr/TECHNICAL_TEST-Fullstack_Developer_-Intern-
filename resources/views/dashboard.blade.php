<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    Selamat datang, {{ $user->name }} 👋
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6 p-6">

                <div class="bg-white p-4 rounded-xl shadow">
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <i class="fa-solid fa-car text-blue-500"></i>
                        Pemakaian Hari Ini
                    </div>

                    <div class="text-2xl font-bold text-blue-600">
                        {{ $today }}
                    </div>

                    <div class="text-xs mt-1 {{ $growth >= 0 ? 'text-green-500' : 'text-red-500' }}">
                        {{ $growth >= 0 ? '+' : '' }}{{ $growth }}% dari kemarin
                    </div>

                    <div class="text-xs text-gray-400">
                        Booking aktif & selesai hari ini
                    </div>
                </div>

                <div class="bg-white p-4 rounded-xl shadow">
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <i class="fa-solid fa-calendar-week text-green-500"></i>
                        Minggu Ini
                    </div>

                    <div class="text-2xl font-bold text-green-600">
                        {{ $weekly }}
                    </div>

                    <div class="text-xs text-gray-400">
                        Total penggunaan kendaraan minggu ini
                    </div>
                </div>

                <div class="bg-white p-4 rounded-xl shadow">
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <i class="fa-solid fa-calendar-days text-yellow-500"></i>
                        Bulan Ini
                    </div>

                    <div class="text-2xl font-bold text-yellow-600">
                        {{ $monthly }}
                    </div>

                    <div class="text-xs text-gray-400">
                        Aktivitas kendaraan bulan berjalan
                    </div>
                </div>

                <div class="bg-white p-4 rounded-xl shadow">
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <i class="fa-solid fa-chart-line text-red-500"></i>
                        Tahun Ini
                    </div>

                    <div class="text-2xl font-bold text-red-500">
                        {{ $yearly }}
                    </div>

                    <div class="text-xs text-gray-400">
                        Total penggunaan kendaraan tahun ini
                    </div>
                </div>

            </div>

            <div class="mb-4">
                <h3 class="text-lg font-semibold text-gray-700">
                    Analisis Pemakaian Kendaraan
                </h3>
                <p class="text-sm text-gray-400">
                    Visualisasi penggunaan kendaraan, driver, dan approval
                </p>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div class="bg-white p-5 rounded-2xl shadow flex flex-col h-[380px]">
                        <h3 class="font-semibold text-gray-700 mb-3">Pemakaian Harian</h3>
                        <div class="flex-1">
                            <canvas id="usageChart"></canvas>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-2xl shadow flex flex-col h-[380px]">
                        <h3 class="font-semibold text-gray-700 mb-3">Per Kendaraan</h3>
                        <div class="flex-1">
                            <canvas id="vehicleChart"></canvas>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-2xl shadow flex flex-col h-[380px] md:col-span-2">
                        <h3 class="font-semibold text-gray-700 mb-3">Status Booking</h3>
                        <div class="flex-1 flex justify-center items-center">
                            <canvas id="statusChart" class="max-w-[300px]"></canvas>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-2xl shadow h-[380px]">
                        <h3 class="mb-3 font-semibold">Top Driver</h3>
                        <canvas id="driverChart"></canvas>
                    </div>

                    <div class="bg-white p-5 rounded-2xl shadow h-[380px]">
                        <h3 class="mb-3 font-semibold">Approval Level</h3>
                        <canvas id="approvalChart"></canvas>
                    </div>

                    <div class="bg-white p-5 rounded-2xl shadow">
                        <h3 class="font-semibold mb-4">Utilization Kendaraan</h3>

                        @foreach ($utilization as $u)
                            <div class="mb-3">
                                <div class="flex justify-between text-sm">
                                    <span>Kendaraan {{ $u->vehicle_id }}</span>
                                    <span>{{ $u->percentage }}%</span>
                                </div>

                                <div class="w-full bg-gray-200 h-2 rounded">
                                    <div class="bg-blue-500 h-2 rounded" style="width: {{ $u->percentage }}%">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="bg-white p-5 rounded-2xl shadow">
                        <h3 class="font-semibold mb-4">Top Kendaraan (KM)</h3>

                        @foreach ($topVehicle as $index => $v)
                            <div class="flex justify-between mb-2">
                                <span>
                                    #{{ $index + 1 }} {{ $v->plate_number }}
                                </span>
                                <span class="font-semibold">
                                    {{ number_format($v->total_km) }} km
                                </span>
                            </div>
                        @endforeach
                    </div>

                    <div class="bg-white p-5 rounded-2xl shadow">
                        <h3 class="font-semibold mb-4">Maintenance Kendaraan</h3>

                        @foreach ($maintenance as $m)
                            <div class="flex justify-between items-center mb-3">
                                <div>
                                    <div class="font-medium">{{ $m->plate_number }}</div>
                                    <div class="text-xs text-gray-400">
                                        Last service: {{ $m->last_service }}
                                    </div>
                                </div>

                                <div
                                    class="text-sm font-semibold {{ $m->total_service > 5 ? 'text-red-500' : 'text-green-500' }}">
                                    {{ $m->total_service }}x
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="bg-white p-5 rounded-2xl shadow h-[380px]">
                        <h3 class="mb-3 font-semibold">Driver KM</h3>
                        <canvas id="driverKmChart"></canvas>
                    </div>

                    <div class="bg-white p-5 rounded-2xl shadow h-[380px]">
                        <h3 class="mb-3 font-semibold">Approval Bottleneck</h3>
                        <canvas id="approvalTimeChart"></canvas>
                    </div>

                </div>

            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            window.dashboardData = {
                usageLabels: @json($usageLabels),
                usageData: @json($usageData),
                vehicleLabels: @json($vehicleLabels),
                vehicleData: @json($vehicleData),
                statusLabels: @json($statusLabels),
                statusData: @json($statusData),
                driverLabels: @json($driverLabels),
                driverData: @json($driverData),
                approvalTimeLabels: @json($approvalTimeLabels),
                approvalTimeData: @json($approvalTimeData),
                driverKmLabels: @json($driverKmLabels),
                driverKmData: @json($driverKmData),
            };
        </script>
    @endpush

    @vite(['resources/js/dashboard.js'])

</x-app-layout>
