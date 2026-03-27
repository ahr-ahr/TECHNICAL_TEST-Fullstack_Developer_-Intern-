<div id="editModal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50">

    <div class="bg-white p-6 rounded-xl w-full max-w-md">

        <h2 class="text-lg font-bold mb-4">Edit Vehicle</h2>

        <form id="editForm" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Plat Nomor
                </label>
                <input id="edit_plate" name="plate_number"
                    class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500">

                @error('plate_number')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Tipe Kendaraan
                </label>
                <select id="edit_type" name="type"
                    class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500">

                    <option value="">-- Pilih Tipe --</option>
                    <option value="people">People</option>
                    <option value="goods">Goods</option>
                </select>

                @error('type')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Ownership
                </label>
                <select id="edit_ownership" name="ownership"
                    class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500">

                    <option value="">-- Pilih Ownership --</option>
                    <option value="company">Company</option>
                    <option value="rental">Rental</option>
                </select>

                @error('ownership')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Status
                </label>
                <select id="edit_status" name="status"
                    class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500">

                    <option value="">-- Pilih Status --</option>
                    <option value="available">Available</option>
                    <option value="in_use">In Use</option>
                    <option value="maintenance">Maintenance</option>
                </select>

                @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-2">

                <button type="button" onclick="closeEditModal()"
                    class="px-4 py-2 rounded-lg border text-gray-600 hover:bg-gray-100">
                    Close
                </button>

                <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                    Update
                </button>

            </div>

        </form>

    </div>
</div>
