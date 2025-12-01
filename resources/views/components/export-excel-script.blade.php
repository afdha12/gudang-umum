<script>
    const periods = @json($availablePeriods);

    document.getElementById('tahunExport').addEventListener('change', function() {
        const tahun = this.value;
        const bulanSelect = document.getElementById('bulanExport');

        bulanSelect.innerHTML = '<option value="">Pilih Bulan</option>';
        if (!tahun) return;

        const bulanIndo = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        // Ambil bulan untuk tahun tertentu
        const bulanTersedia = periods
            .filter(p => p.tahun == tahun)
            .map(p => p.bulan);

        // FIX: urutkan numerik
        bulanTersedia.sort((a, b) => a - b);

        bulanTersedia.forEach(b => {
            bulanSelect.innerHTML += `<option value="${b}">${bulanIndo[b]}</option>`;
        });
    });

    document.getElementById('exportExcelForm').addEventListener('submit', function(e) {
        var tahun = document.getElementById('tahunExport').value;
        var bulan = document.getElementById('bulanExport').value;

        const valid = periods.some(p => p.tahun == tahun && p.bulan == bulan);
        if (!valid) {
            e.preventDefault();
            alert('Bulan dan tahun yang dipilih tidak tersedia!');
            return;
        }

        const from = `${tahun}-${String(bulan).padStart(2, '0')}-01`;
        const lastDay = new Date(tahun, bulan, 0).getDate();
        const to = `${tahun}-${String(bulan).padStart(2, '0')}-${lastDay}`;

        document.getElementById('fromExport').value = from;
        document.getElementById('toExport').value = to;

        document.getElementById('exportExcelModal').classList.add('hidden');
    });
</script>
