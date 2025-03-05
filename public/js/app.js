document.addEventListener("DOMContentLoaded", function () {
    let hargaInput = document.getElementById("harga");

    hargaInput.addEventListener("input", function (e) {
        let value = this.value.replace(/\D/g, ""); // Hapus semua karakter non-angka
        if (value) {
            this.value = formatRupiah(value);
        } else {
            this.value = "";
        }
    });

    function formatRupiah(angka) {
        return new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
            minimumFractionDigits: 0
        }).format(angka);
    }
});
