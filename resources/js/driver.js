function openCreateModal() {
    document.getElementById("createModal").classList.remove("hidden");
}

function closeCreateModal() {
    document.getElementById("createModal").classList.add("hidden");
}

function openEditModal(id, name, phone) {
    document.getElementById("editModal").classList.remove("hidden");

    document.getElementById("name").value = name;
    document.getElementById("phone").value = phone;

    document.getElementById("editForm").action = `/driver/${id}`;
}

function closeEditModal() {
    document.getElementById("editModal").classList.add("hidden");
}

function openDeleteModal(id) {
    document.getElementById("deleteModal").classList.remove("hidden");
    document.getElementById("deleteForm").action = `/driver/${id}`;
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
