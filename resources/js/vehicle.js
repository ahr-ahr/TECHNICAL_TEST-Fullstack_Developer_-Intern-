function openCreateModal() {
    document.getElementById("createModal").classList.remove("hidden");
}

function closeCreateModal() {
    document.getElementById("createModal").classList.add("hidden");
}

function openEditModal(id, plate_number, type, ownership, status) {
    document.getElementById("editModal").classList.remove("hidden");

    document.getElementById("edit_plate").value = plate_number;
    document.getElementById("edit_type").value = type;
    document.getElementById("edit_ownership").value = ownership;
    document.getElementById("edit_status").value = status;

    document.getElementById("editForm").action = `/vehicle/${id}`;
}

function closeEditModal() {
    document.getElementById("editModal").classList.add("hidden");
}

function openDeleteModal(id) {
    document.getElementById("deleteModal").classList.remove("hidden");
    document.getElementById("deleteForm").action = `/vehicle/${id}`;
}

function closeDeleteModal() {
    document.getElementById("deleteModal").classList.add("hidden");
}

window.openCreateModal = openCreateModal;
window.closeCreateModal = closeCreateModal;
window.openEditModal = openEditModal;
window.closeEditModal = closeEditModal;
window.openDeleteModal = openDeleteModal;
window.closeDeleteModal = closeDeleteModal;
