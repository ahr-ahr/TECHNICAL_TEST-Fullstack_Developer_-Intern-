<div id="showModal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50">

    <div class="bg-white p-6 rounded-xl w-full max-w-lg max-h-[90vh] flex flex-col">

        <h2 class="text-lg font-bold mb-4">Detail Booking</h2>

        <div class="space-y-4 text-sm overflow-y-auto pr-2 flex-1">

            <div>
                <h3 class="font-semibold mb-1">Booking</h3>
                <div><b>Tanggal:</b> <span id="show_date"></span></div>
                <div><b>Tujuan:</b> <span id="show_purpose"></span></div>
                <div><b>Status:</b> <span id="show_status"></span></div>
            </div>

            <div>
                <h3 class="font-semibold mb-1">Kendaraan</h3>
                <div><b>Plat:</b> <span id="show_plate"></span></div>
                <div><b>Tipe:</b> <span id="show_vehicle_type"></span></div>
                <div><b>Ownership:</b> <span id="show_vehicle_owner"></span></div>
                <div><b>Status:</b> <span id="show_vehicle_status"></span></div>
            </div>

            <div>
                <h3 class="font-semibold mb-1">Driver</h3>
                <div><b>Nama:</b> <span id="show_driver"></span></div>
                <div><b>Phone:</b> <span id="show_driver_phone"></span></div>
            </div>

            <div>
                <h3 class="font-semibold mb-1">Dibuat Oleh</h3>
                <div><b>Nama:</b> <span id="show_user"></span></div>
                <div><b>Email:</b> <span id="show_user_email"></span></div>
            </div>

            <div>
                <h3 class="font-semibold mb-1">Approval</h3>
                <div id="show_approvals" class="space-y-1"></div>
            </div>

            <div id="usage_section" class="hidden">
                <h3 class="font-semibold mb-1">Usage</h3>

                <div><b>Start KM:</b> <span id="show_start_km"></span></div>
                <div><b>End KM:</b> <span id="show_end_km"></span></div>
            </div>

            <div id="fuel_section" class="hidden">
                <h3 class="font-semibold mb-1">Fuel</h3>
                <div><b>Liters:</b> <span id="show_liters"></span></div>
                <div><b>Cost:</b> <span id="show_cost"></span></div>
            </div>

            <div id="service_section" class="hidden">
                <h3 class="font-semibold mb-1">Service</h3>
                <div><b>Description:</b> <span id="show_service_desc"></span></div>
                <div><b>Cost:</b> <span id="show_service_cost"></span></div>
            </div>

        </div>

        <div class="flex justify-end mt-4">

            <div class="mt-6 space-y-4">

                <!-- START BUTTON -->
                <form id="startForm" method="POST" class="hidden">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <button id="startBtn" type="submit"
                        class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Start Usage
                    </button>
                </form>

                <!-- COMPLETE FORM -->
                <form id="completeForm" method="POST" class="space-y-3 hidden">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="grid grid-cols-2 gap-2">
                        <input name="start_km" type="number" placeholder="Start KM"
                            class="border rounded px-3 py-2 w-full" />

                        <input name="end_km" type="number" placeholder="End KM"
                            class="border rounded px-3 py-2 w-full" />
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <input name="liters" type="number" placeholder="Fuel (Liters)"
                            class="border rounded px-3 py-2 w-full" />

                        <input name="cost" type="number" placeholder="Fuel Cost"
                            class="border rounded px-3 py-2 w-full" />
                    </div>

                    <input name="service_description" type="text" placeholder="Service Description (Optional)"
                        class="border rounded px-3 py-2 w-full" />

                    <input name="service_cost" type="number" placeholder="Service Cost (Optional)"
                        class="border rounded px-3 py-2 w-full" />

                    <button type="submit"
                        class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Complete Usage
                    </button>

                </form>

                <!-- CLOSE BUTTON -->
                <div class="flex justify-end">
                    <button onclick="closeShowModal()"
                        class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100">
                        Close
                    </button>
                </div>

            </div>
        </div>

    </div>
</div>
