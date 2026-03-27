document.addEventListener("DOMContentLoaded", () => {
    function openCreateModal() {
        document.getElementById("createModal").classList.remove("hidden");
    }

    function closeCreateModal() {
        document.getElementById("createModal").classList.add("hidden");
    }

    function openEditModal(
        id,
        vehicle_id,
        driver_id,
        start_date,
        end_date,
        purpose,
    ) {
        document.getElementById("editModal").classList.remove("hidden");

        document.getElementById("edit_vehicle").value = vehicle_id;
        document.getElementById("edit_driver").value = driver_id;
        document.getElementById("edit_start_date").value = start_date;
        document.getElementById("edit_end_date").value = end_date;
        document.getElementById("edit_purpose").value = purpose;

        document.getElementById("editForm").action = `/booking/${id}`;
    }

    function closeEditModal() {
        document.getElementById("editModal").classList.add("hidden");
    }

    function openDeleteModal(id) {
        document.getElementById("deleteModal").classList.remove("hidden");
        document.getElementById("deleteForm").action = `/booking/${id}`;
    }

    function closeDeleteModal() {
        document.getElementById("deleteModal").classList.add("hidden");
    }

    function closeShowModal() {
        document.getElementById("showModal").classList.add("hidden");
    }

    async function openShowModal(id) {
        const res = await fetch(`/booking/${id}`);
        const data = await res.json();

        document.getElementById("showModal").classList.remove("hidden");

        document.getElementById("show_date").innerText =
            data.start_date + " - " + data.end_date;
        document.getElementById("show_purpose").innerText = data.purpose;
        document.getElementById("show_status").innerText = data.status;

        document.getElementById("show_plate").innerText =
            data.vehicle?.plate_number ?? "-";
        document.getElementById("show_vehicle_type").innerText =
            data.vehicle?.type ?? "-";
        document.getElementById("show_vehicle_owner").innerText =
            data.vehicle?.ownership ?? "-";
        document.getElementById("show_vehicle_status").innerText =
            data.vehicle?.status ?? "-";

        document.getElementById("show_driver").innerText =
            data.driver?.name ?? "-";
        document.getElementById("show_driver_phone").innerText =
            data.driver?.phone ?? "-";

        document.getElementById("show_user").innerText =
            data.requester?.name ?? "-";
        document.getElementById("show_user_email").innerText =
            data.requester?.email ?? "-";

        const approvalContainer = document.getElementById("show_approvals");
        approvalContainer.innerHTML = "";

        data.approvals.forEach((a) => {
            approvalContainer.innerHTML += `
                <div class="flex justify-between border-b pb-1">
                    <span>Level ${a.level} - ${a.approver?.name ?? "-"}</span>
                    <span class="text-xs">${a.status ?? "pending"}</span>
                </div>
            `;
        });

        document.getElementById("usage_section").classList.add("hidden");
        document.getElementById("fuel_section").classList.add("hidden");
        document.getElementById("service_section").classList.add("hidden");

        if (data.usage) {
            document.getElementById("usage_section").classList.remove("hidden");

            document.getElementById("show_start_km").innerText =
                data.usage.start_km ?? "-";
            document.getElementById("show_end_km").innerText =
                data.usage.end_km ?? "-";
        }

        if (data.fuel_logs?.length) {
            const fuel = data.fuel_logs[0];

            document.getElementById("fuel_section").classList.remove("hidden");

            document.getElementById("show_liters").innerText =
                fuel.liters ?? "-";
            document.getElementById("show_cost").innerText = fuel.cost ?? "-";
        }

        if (data.service_logs?.length) {
            const service = data.service_logs[0];

            document
                .getElementById("service_section")
                .classList.remove("hidden");

            document.getElementById("show_service_desc").innerText =
                service.description ?? "-";
            document.getElementById("show_service_cost").innerText =
                service.cost ?? "-";
        }

        setupUsageAction(data);
    }

    const startForm = document.getElementById("startForm");
    const startBtn = document.getElementById("startBtn");
    const completeForm = document.getElementById("completeForm");

    function setupUsageAction(booking) {
        if (!startBtn || !completeForm || !startForm) return;

        startBtn.classList.add("hidden");
        completeForm.classList.add("hidden");

        startForm.action = `/usage/${booking.id}/start`;
        completeForm.action = `/usage/${booking.id}/complete`;

        if (booking.status === "approved") {
            startBtn.classList.remove("hidden");
        }

        if (booking.status === "in_use") {
            completeForm.classList.remove("hidden");
        }
    }

    if (startForm) {
        startForm.addEventListener("submit", function () {
            startBtn.disabled = true;
            startBtn.innerText = "Starting...";
        });
    }

    if (completeForm) {
        completeForm.addEventListener("submit", function () {
            const btn = completeForm.querySelector("button[type='submit']");
            btn.disabled = true;
            btn.innerText = "Processing...";
        });
    }

    window.openCreateModal = openCreateModal;
    window.closeCreateModal = closeCreateModal;
    window.openEditModal = openEditModal;
    window.closeEditModal = closeEditModal;
    window.openDeleteModal = openDeleteModal;
    window.closeDeleteModal = closeDeleteModal;
    window.openShowModal = openShowModal;
    window.closeShowModal = closeShowModal;
});
