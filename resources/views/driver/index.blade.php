<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Driver
        </h2>
    </x-slot>

    <div class="p-6 max-w-7xl mx-auto">


        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Kelola Driver</h3>
                <p class="text-sm text-gray-500">Manage data driver perusahaan</p>
            </div>

            <button onclick="openCreateModal()"
                class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                <i class="fa-solid fa-plus"></i>
                <span>Tambah</span>
            </button>
        </div>

        <form method="GET" class="flex flex-col sm:flex-row gap-2 mb-4">

            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name / phone..."
                class="px-3 py-2 border rounded-lg text-sm w-full sm:w-64">

            <select name="sort" class="px-3 py-2 border rounded-lg text-sm">
                <option value="latest">Latest</option>
                <option value="oldest">Oldest</option>
                <option value="name_asc">Name A-Z</option>
                <option value="name_desc">Name Z-A</option>
            </select>

            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">
                Filter
            </button>

        </form>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden border">

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-blue-50 text-gray-700">
                        <tr>
                            <th class="p-3 text-left">Nama</th>
                            <th class="p-3 text-left">Nomor Telpon</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($drivers as $d)
                            <tr class="border-t hover:bg-gray-50 transition">

                                <td class="p-3 font-medium text-gray-800">
                                    {{ $d->name }}
                                </td>

                                <td class="p-3 capitalize">
                                    {{ $d->phone }}
                                </td>

                                <td class="p-3">
                                    <div class="flex justify-center gap-3">

                                        <div class="flex justify-center gap-4">

                                            <button
                                                onclick="openEditModal(
            {{ $d->id }},
            {{ json_encode($d->name) }},
            {{ json_encode($d->phone) }},
        )"
                                                class="flex items-center gap-1 text-blue-600 hover:text-blue-800 text-sm transition">

                                                <i class="fa-solid fa-pen-to-square"></i>
                                                <span>Edit</span>
                                            </button>

                                            <button onclick="openDeleteModal({{ $d->id }})"
                                                class="flex items-center gap-1 text-red-600 hover:text-red-800 text-sm transition">

                                                <i class="fa-solid fa-trash"></i>
                                                <span>Hapus</span>
                                            </button>

                                        </div>

                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-6 text-center text-gray-500">
                                    Belum ada data drivers
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $drivers->links() }}
        </div>

    </div>

    @include('driver.modal.create')
    @include('driver.modal.edit')
    @include('driver.modal.delete')

    @push('scripts')
        @vite('resources/js/driver.js')
    @endpush

</x-app-layout>
