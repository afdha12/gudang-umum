<!-- Modal -->
<div class="modal fade" id="selectDivisionsModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="selectDivisionsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('divisions.update', ['division' => 0]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="selectDivisionsModalLabel">Pilih Divisi yang Dikelola oleh Wadir</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    @foreach ($divisions as $division)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="coo_divisions[]" value="{{ $division->id }}"
                                id="division{{ $division->id }}" {{ $division->managed_by_coo ? 'checked' : '' }}>
                            <label class="form-check-label text-capitalize" for="division{{ $division->id }}">
                                {{ $division->division_name }}
                            </label>
                        </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>