<div id="createModal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50">

    <div class="bg-white p-6 rounded-xl w-full max-w-md">

        <h2 class="text-lg font-bold mb-4">Add Driver</h2>

        <form method="POST" action="{{ route('driver.store') }}">
            @csrf

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Name
                </label>
                <input name="name" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                    placeholder="Santo">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Phone
                </label>
                <input name="phone" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                    placeholder="62823xxxx">
                @error('phone')
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
