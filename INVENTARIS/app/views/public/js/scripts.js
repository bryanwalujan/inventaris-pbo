// Tambahan logika untuk konfirmasi penghapusan jika diperlukan
document.addEventListener("DOMContentLoaded", () => {
    const deleteButtons = document.querySelectorAll(".btn-delete");
    deleteButtons.forEach(button => {
        button.addEventListener("click", function(e) {
            if (!confirm("Apakah Anda yakin ingin menghapus item ini?")) {
                e.preventDefault();
            }
        });
    });
});
