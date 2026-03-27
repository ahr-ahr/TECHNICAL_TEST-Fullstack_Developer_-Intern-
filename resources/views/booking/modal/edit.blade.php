<div id="editModal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50">

    <div class="bg-white p-6 rounded-xl w-full max-w-md">

        <h2 class="text-lg font-bold mb-4">Edit Booking</h2>

        <form id="editForm" method="POST">
            @csrf
            @method('PUT')

            <!-- VEHICLE -->
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Vehicle</label>
                <select id="edit_vehicle" name="vehicle_id"
                    class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500">

                    <option value="">-- Select Vehicle --</option>
                    @foreach ($vehicles as $v)
                        <option value="{{ $v->id }}">{{ $v->plate_number }}</option>
                    @endforeach
                </select>
                @error('vehicle_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- DRIVER -->
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Driver</label>
                <select id="edit_driver" name="driver_id"
                    class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500">

                    <option value="">-- Select Driver --</option>
                    @foreach ($drivers as $d)
                        <option value="{{ $d->id }}">{{ $d->name }}</option>
                    @endforeach
                </select>
                @error('driver_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- START DATE -->
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Start Date</label>
                <input type="date" id="edit_start_date" name="start_date"
                    class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                @error('start_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- END DATE -->
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">End Date</label>
                <input type="date" id="edit_end_date" name="end_date"
                    class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                @error('end_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- PURPOSE -->
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Purpose</label>
                <textarea id="edit_purpose" name="purpose" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                @error('purpose')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- BUTTON -->
            <div class="flex justify-end gap-2">

                <button type="button" onclick="closeEditModal()"
                    class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100">
                    Close
                </button>

                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Update
                </button>

            </div>

        </form>

    </div>
</div>
