<div id="exportExcelModal" class="fixed inset-0 flex items-center justify-center modal-backdrop-blur z-[9999] hidden">

    <div class="bg-white rounded-lg shadow-lg w-full max-w-md relative">

        <!-- Close -->
        <button type="button" onclick="document.getElementById('exportExcelModal').classList.add('hidden')"
            class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;
        </button>

        <form method="GET" action="{{ $action }}" class="p-6" id="exportExcelForm">
            <h2 class="text-lg font-semibold mb-4 text-center">Pilih Bulan & Tahun</h2>

            @php
                $tahunList = $availablePeriods->pluck('tahun')->unique()->sortDesc();
            @endphp

            <div class="flex gap-4 mb-6">
                <div class="flex-1">
                    <label class="text-sm font-medium mb-1">Tahun</label>
                    <select name="tahun" id="tahunExport" class="w-full border rounded px-2 py-1" required>
                        <option value="">Pilih Tahun</option>
                        @foreach ($tahunList as $tahun)
                            <option value="{{ $tahun }}">{{ $tahun }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1">
                    <label class="text-sm font-medium mb-1">Bulan</label>
                    <select name="bulan" id="bulanExport" class="w-full border rounded px-2 py-1" required>
                        <option value="">Pilih Bulan</option>
                    </select>
                </div>
            </div>

            <input type="hidden" name="from" id="fromExport">
            <input type="hidden" name="to" id="toExport">

            <div class="flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('exportExcelModal').classList.add('hidden')"
                    class="px-4 py-2 rounded border text-gray-700 hover:bg-gray-100">Batal</button>

                <button type="submit"
                    class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700">Download</button>
            </div>
        </form>
    </div>
</div>
