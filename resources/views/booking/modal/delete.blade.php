<div id="deleteModal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50">

    <div class="bg-white p-6 rounded-xl w-full max-w-md">

        <h2 class="text-lg font-bold mb-2 text-gray-800">
            Hapus Data
        </h2>

        <p class="text-sm text-gray-600 mb-6">
            Yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.
        </p>

        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')

            <div class="flex justify-end gap-2">

                <button type="button" onclick="closeDeleteModal()"
                    class="px-4 py-2 rounded-lg border text-gray-600 hover:bg-gray-100">
                    Batal
                </button>

                <button type="submit" class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700">
                    Hapus
                </button>

            </div>
        </form>

    </div>
</div>
