<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Approval Monitoring
        </h2>
    </x-slot>

    <div class="p-6 max-w-7xl mx-auto space-y-6">

        <form method="GET" class="flex flex-wrap gap-2">

            <select name="status" class="px-3 py-2 border rounded-lg text-sm">
                <option value="">All</option>
                <option value="pending">Pending</option>
                <option value="waiting_level_2">Waiting L2</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>

            <select name="sort" class="px-3 py-2 border rounded-lg text-sm">
                <option value="latest">Latest</option>
                <option value="oldest">Oldest</option>
            </select>

            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">
                Filter
            </button>

        </form>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

            @forelse ($approvals as $b)

                <div class="bg-white rounded-xl shadow p-4 space-y-3 border">

                    <div class="flex justify-between items-center">

                        <div>
                            <div class="font-semibold text-gray-800">
                                {{ $b->vehicle->plate_number }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $b->driver->name }}
                            </div>
                        </div>

                        <span
                            class="text-xs px-2 py-1 rounded-full
                            @if ($b->status === 'approved') bg-green-100 text-green-700
                            @elseif($b->status === 'rejected') bg-red-100 text-red-700
                            @else bg-gray-100 text-gray-600 @endif">

                            {{ $b->status }}

                        </span>

                    </div>

                    <div class="text-xs text-gray-500">
                        {{ $b->start_date }} - {{ $b->end_date }}
                    </div>

                    <div class="text-sm text-gray-700">
                        {{ $b->purpose }}
                    </div>

                    <div class="space-y-2 text-xs">

                        @foreach ($b->approvals->sortBy('level') as $a)
                            @php
                                $user = auth()->user();

                                $canApprove = false;

                                if ($user->role === 'approver' && $a->approver_id == $user->id) {
                                    if ($a->level == 1 && $a->status == 'pending') {
                                        $canApprove = true;
                                    }

                                    if ($a->level == 2 && $a->status == 'pending') {
                                        $level1Approved = $b->approvals
                                            ->where('level', 1)
                                            ->where('status', 'approved')
                                            ->isNotEmpty();

                                        if ($level1Approved) {
                                            $canApprove = true;
                                        }
                                    }
                                }
                            @endphp
                            <div class="flex justify-between items-center">

                                <div>
                                    L{{ $a->level }} - {{ $a->approver->name }}
                                </div>

                                <span
                                    class="px-2 py-0.5 rounded
                                    @if ($a->status === 'approved') bg-green-100 text-green-700
                                    @elseif($a->status === 'rejected') bg-red-100 text-red-700
                                    @else bg-gray-100 text-gray-600 @endif">

                                    {{ $a->status ?? 'pending' }}

                                </span>
                                @if ($canApprove)
                                    <div class="flex gap-2 pt-2">

                                        <form method="POST" action="{{ route('approval.approve', $a->id) }}">
                                            @csrf
                                            <button class="bg-green-600 text-white px-3 py-1 rounded text-xs">
                                                Approve
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('approval.reject', $a->id) }}">
                                            @csrf
                                            <button class="bg-red-600 text-white px-3 py-1 rounded text-xs">
                                                Reject
                                            </button>
                                        </form>

                                    </div>
                                @endif
                            </div>
                        @endforeach

                    </div>

                </div>

            @empty
                <div class="col-span-full text-center text-gray-500 py-10">
                    No approval data
                </div>
            @endforelse

        </div>

        <div class="mt-4">
            {{ $approvals->links() }}
        </div>

    </div>

</x-app-layout>
