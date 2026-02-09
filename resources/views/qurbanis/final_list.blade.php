@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">Qurbani Final List - Day {{ $day }}</h4>

        <button type="button" class="btn btn-success" onclick="sendEmailAndOpenPDF('{{ $day }}')">
            <i class="bi bi-download"></i> Download PDF
        </button>
    </div>

    <!-- FULL PAGE LOADER -->
    <div id="page-loader" style="
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        align-items: center;
        justify-content: center;">
        <div class="spinner-border text-light" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    {{-- Your card list generation logic --}}
    @php
        $receiptarray = [];
        $boxCount = 0;
        $listNo = 1;
        $mobile = [];
    @endphp

    @for ($i = 0; $i < count($columns); $i++)
        @if($boxCount % 2 == 0)
        <div class="row mb-4">
        @endif

        <div class="col-md-6 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-3">
                    <h6 class="fw-bold text-center">{{ $boxCount + 1 }}</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 10%">ID</th>
                                    <th style="width: 30%">Receipt</th>
                                    <th>Name</th>
                                    <th>Phone Number</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 0; @endphp
                                @foreach ($columns[$i] as $hisse)
                                    @php
                                        $qurbani = App\Models\Qurbani::find($hisse['qurbani_id']);
                                    @endphp
                                    <tr>
                                        <td>{{ ++$no }}</td>
                                        <td>
                                            @if (!in_array($qurbani->id, $receiptarray))
                                                {{ $qurbani->id }}
                                                @if (!empty($qurbani->receipt_book))
                                                    ({{ $qurbani->receipt_book }})
                                                @endif
                                            @endif
                                        </td>
                                        <td>{{ $hisse['name'] }}</td>
                                        <td>
                                            @if (!in_array($qurbani->id, $mobile))
                                                @if (!empty($qurbani->mobile))
                                                    {{ $qurbani->mobile }}
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                    @php $receiptarray[] = $qurbani->id; @endphp
                                    @php $mobile[] = $qurbani->id; @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @php $boxCount++; @endphp

        @if($boxCount % 2 == 0)
        </div>
        @endif
    @endfor

    @if($boxCount % 2 != 0)
        </div>
    @endif
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function sendEmailAndOpenPDF(day) {
        const loader = document.getElementById('page-loader');
        loader.style.display = 'flex';

        fetch(`/send-email/${day}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        })
        .then(response => {
            loader.style.display = 'none';
            if (response.ok) {
                Swal.fire({
                    icon: 'success',
                    title: 'Email has been sent!',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.open(`/final-list/${day}`, '_blank');
                });
            } else {
                Swal.fire('Failed', 'Failed to send email.', 'error');
            }
        })
        .catch(error => {
            loader.style.display = 'none';
            Swal.fire('Error', 'Error occurred while sending email.', 'error');
            console.error(error);
        });
    }
</script>
@endsection
