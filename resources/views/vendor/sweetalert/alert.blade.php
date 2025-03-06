@if (config('sweetalert.alwaysLoadJS') === true || Session::has('alert.config') || Session::has('alert.delete'))
    @if (config('sweetalert.animation.enable'))
        <link rel="stylesheet" href="{{ config('sweetalert.animatecss') }}">
    @endif

    @if (config('sweetalert.theme') != 'default')
        <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-{{ config('sweetalert.theme') }}" rel="stylesheet">
    @endif

    @if (config('sweetalert.neverLoadJS') === false)
        <script src="{{ $cdn ?? asset('vendor/sweetalert/sweetalert.all.js') }}"></script>
    @endif

    @if (Session::has('alert.delete') || Session::has('alert.config'))
        <script>
            document.addEventListener('click', function (event) {
                // Check if the clicked element or its parent has the attribute
                var target = event.target;

                // Handle Confirm Delete
                var confirmDeleteElement = target.closest('[data-confirm-delete]');
                if (confirmDeleteElement) {
                    event.preventDefault();
                    Swal.fire({
                        title: "Hapus Data!",
                        text: "Apakah Anda Yakin Ingin Menghapusnya?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Ya, Hapus!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var form = document.createElement('form');
                            form.action = confirmDeleteElement.href;
                            form.method = 'POST';
                            form.innerHTML = `@csrf @method('DELETE')`;
                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                    return;
                }

                // Handle Confirm Approval (Setujui)
                var confirmApproveElement = target.closest('[data-confirm-approve]');
                if (confirmApproveElement) {
                    event.preventDefault();
                    Swal.fire({
                        title: "Setujui Permintaan?",
                        text: "Apakah Anda yakin ingin menyetujui permintaan ini? Stok akan dikurangi!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Ya, Setujui!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var form = document.createElement('form');
                            form.action = confirmApproveElement.href;
                            form.method = 'POST';
                            form.innerHTML = `@csrf @method('PUT')`;
                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                    return;
                }
            });

            @if (Session::has('alert.config'))
                Swal.fire({!! Session::pull('alert.config') !!});
            @endif
        </script>
    @endif
@endif
