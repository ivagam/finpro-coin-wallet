@extends('layout.layout')
@php
    $title = 'Withdrawal Report';
    $subTitle = 'Withdrawal Report';
    $script = '<script>
                    let table = new DataTable("#dataTable");
               </script>';
@endphp

@section('content')
<div class="card basic-data-table">
    <div class="card-header">
        <h5 class="card-title mb-0">Withdrawal Report 
        @if($is_admin != 1)    
        <button type="button" style="float:right" class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#exampleModalEdit">

                        <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                        Withdrawal Request
</button>
        @endif
        </h5>
    </div>

    <div class="card-body">
        <!-- Responsive table container -->
        <div class="table-responsive">
            <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Amount</th>
                        <th>Request Id</th>
                        <th>Status</th>
                        <th>Approved Date</th>
                        <th>Created Date</th>
                        @if($is_admin == 1)
                        <th>Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $index => $tx)
                    <tr>
                        <td>{{ $tx['fullname'] ?? '-' }}</td>
                        <td>{{ number_format($tx['amount'], 8) }}</td>
                        <td>{{ $tx['request_id'] }}</td>
                      
                        <td>
                              @if($tx['status'] == '1')
                                <span class="bg-success-focus text-success-main px-16 py-4 radius-4 fw-medium text-sm">Proccessed</span>
                            @else
                                <span class="bg-danger-focus text-danger-main px-16 py-4 radius-4 fw-medium text-sm">Pending</span>
                            @endif
                        </td>
                        <td>{{ isset($tx['approved_at']) ? \Carbon\Carbon::parse($tx['approved_at'])->format('d/m/Y') : '-' }}</td>
                        <td>{{ isset($tx['created_at']) ? \Carbon\Carbon::parse($tx['created_at'])->format('d/m/Y') : '-' }}</td>
                        @if($is_admin == 1)
                        <td class="text-center">
                            <div class="d-flex align-items-center gap-10">
                                <button type="button"
                                        class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle approveBtn"
                                        data-id="{{ $tx['withdrawal_id'] }}"
                                        data-request="{{ $tx['request_id'] }}"
                                        data-amount="{{ $tx['amount'] }}">
                                    <iconify-icon icon="mdi:check" class="menu-icon"></iconify-icon>
                                </button>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Record not found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination and summary -->
        
    </div>
</div>
 <!-- Modal Edit Currecny -->
    <div class="modal fade" id="exampleModalEdit" tabindex="-1" aria-labelledby="exampleModalEditLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog modal-dialog-centered">
            <div class="modal-content radius-16 bg-base">
                  <div class="modal-header">
                <h5 class="modal-title">Withdrawal Request Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-32 py-56">
                    
            
             <form action="{{ route('mint.store') }}" method="POST" class="d-flex flex-column gap-3">
                    @csrf

                    {{-- Address --}}
                    <div class="col-12">
                        <label for="amount" class="form-label fw-bold">Amount:</label>
                        <input type="number" name="amount" id="amount"
                               class="form-control"
                               placeholder="Enter Request Amount" required
                               >
                       
                    </div>

                   
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary mt-2">Submit</button>
                    </div>
                </form>
                   
                
            </div>
        </div>
    </div>



    <div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content radius-16 bg-base">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Withdrawal Approval</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p class="text-center fw-bold fs-5">
                    Are you sure you want to approve this withdrawal request?
                </p>
                <p class="text-center">
                    <span class="text-primary fw-bold">Request ID: </span>
                    <span id="modalRequestId"></span><br>
                    <span class="text-primary fw-bold">Amount: </span>
                    <span id="modalAmount"></span>
                </p>
            </div>

            <div class="modal-footer justify-content-center">
                <button class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-success" id="confirmApproveBtn">Yes, Approve</button>
            </div>
        </div>
    </div>
</div>


@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    let selectedId = null;

    // OPEN MODAL WHEN ADMIN CLICKS APPROVE BUTTON
    document.addEventListener("click", function(e) {

        let btn = e.target.closest(".approveBtn");
        if (!btn) return;

        selectedId = btn.dataset.id;

        document.getElementById("modalRequestId").textContent = btn.dataset.request;
        document.getElementById("modalAmount").textContent = btn.dataset.amount;

        let approveModal = new bootstrap.Modal(document.getElementById('approveModal'));
        approveModal.show();
    });

    // CONFIRM APPROVAL BUTTON
    const confirmBtn = document.getElementById("confirmApproveBtn");

    if (confirmBtn) {
        confirmBtn.addEventListener("click", function() {

            if (!selectedId) return;

            fetch("{{ route('transfer.approve') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ withdrawal_id: selectedId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === "ok") {
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(err => alert("Server error"));
        });
    }

});
</script>
@endpush