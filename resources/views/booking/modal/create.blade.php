<div id="createModal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50">

    <div class="bg-white p-6 rounded-xl w-full max-w-md">

        <h2 class="text-lg font-bold mb-4">Create Booking</h2>

        <form method="POST" action="{{ route('booking.store') }}">
            @csrf

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Kendaraan
                </label>
                <select name="vehicle_id" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Pilih Kendaraan --</option>
                    @foreach ($vehicles as $v)
                        <option value="{{ $v->id }}">
                            {{ $v->plate_number }}
                        </option>
                    @endforeach
                </select>
                @error('vehicle_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Driver
                </label>
                <select name="driver_id" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Pilih Driver --</option>
                    @foreach ($drivers as $d)
                        <option value="{{ $d->id }}">
                            {{ $d->name }}
                        </option>
                    @endforeach
                </select>
                @error('driver_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Approver Level 1
                </label>
                <select name="approver_1" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Pilih Approver --</option>
                    @foreach ($approvers as $a)
                        <option value="{{ $a->id }}">
                            {{ $a->name }}
                        </option>
                    @endforeach
                </select>
                @error('approver_1')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Approver Level 2
                </label>
                <select name="approver_2" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Pilih Approver --</option>
                    @foreach ($approvers as $a)
                        <option value="{{ $a->id }}">
                            {{ $a->name }}
                        </option>
                    @endforeach
                </select>
                @error('approver_2')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Tanggal Mulai
                </label>
                <input type="date" name="start_date"
                    class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                @error('start_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Tanggal Selesai
                </label>
                <input type="date" name="end_date"
                    class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                @error('end_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Tujuan
                </label>
                <textarea name="purpose" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                    placeholder="Ke site tambang..."></textarea>
                @error('purpose')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeCreateModal()"
                    class="px-4 py-2 rounded-lg border text-gray-600 hover:bg-gray-100">
                    Close
                </button>

                <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                    Save
                </button>
            </div>

        </form>

    </div>
</div>
