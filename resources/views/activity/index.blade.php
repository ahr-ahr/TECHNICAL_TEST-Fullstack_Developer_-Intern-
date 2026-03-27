<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Activity Log
        </h2>
    </x-slot>

    <div class="p-4 sm:p-6 max-w-4xl mx-auto space-y-6">

        <!-- FILTER -->
        <form method="GET" class="flex flex-col sm:flex-row sm:flex-wrap gap-2">

            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..."
                class="px-3 py-2 border rounded-lg text-sm w-full sm:w-48 focus:ring-2 focus:ring-blue-500">

            <select name="action" class="px-3 py-2 border rounded-lg text-sm w-full sm:w-auto">
                <option value="">All Action</option>

                @foreach ($actions as $action)
                    <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('_', ' ', $action)) }}
                    </option>
                @endforeach

            </select>

            <input type="date" name="date" value="{{ request('date') }}"
                class="px-3 py-2 border rounded-lg text-sm w-full sm:w-auto">

            <select name="sort" class="px-3 py-2 border rounded-lg text-sm w-full sm:w-auto">
                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
            </select>

            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm w-full sm:w-auto">
                Filter
            </button>

        </form>

        <!-- TIMELINE -->
        <div class="space-y-6">

            @forelse ($groupedLogs as $date => $items)

                <!-- DATE -->
                <h3 class="text-sm font-semibold text-gray-500">
                    {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
                </h3>

                <div class="space-y-3">

                    @foreach ($items as $log)
                        <div class="flex gap-3 items-start">

                            <!-- DOT -->
                            <div
                                class="hidden sm:block w-3 h-3 mt-2 rounded-full
                                @if (str_contains($log->action, 'create')) bg-green-500
                                @elseif(str_contains($log->action, 'update')) bg-yellow-500
                                @elseif(str_contains($log->action, 'delete')) bg-red-500
                                @else bg-gray-400 @endif">
                            </div>

                            <!-- CARD -->
                            <div class="bg-white p-4 rounded-xl shadow-sm border w-full">

                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 mb-1">

                                    <!-- ACTION -->
                                    <span
                                        class="text-xs px-2 py-1 rounded-full w-fit
                                        @if (str_contains($log->action, 'create')) bg-green-100 text-green-700
                                        @elseif(str_contains($log->action, 'update')) bg-yellow-100 text-yellow-700
                                        @elseif(str_contains($log->action, 'delete')) bg-red-100 text-red-700
                                        @else bg-gray-100 text-gray-700 @endif">
                                        {{ $log->action }}
                                    </span>

                                    <!-- TIME -->
                                    <span class="text-xs text-gray-400">
                                        {{ $log->created_at->format('H:i') }}
                                    </span>

                                </div>

                                <!-- DESC -->
                                <div class="text-sm text-gray-700 break-words">
                                    {{ $log->description }}
                                </div>

                            </div>

                        </div>
                    @endforeach

                </div>

            @empty
                <div class="text-center text-gray-500 py-10">
                    No activity found
                </div>
            @endforelse

        </div>

        <!-- PAGINATION -->
        <div class="mt-6 overflow-x-auto">
            {{ $logs->links() }}
        </div>

    </div>

</x-app-layout>
