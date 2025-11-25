@extends('layout.layout')
@php
    $title = 'Deposit Report';
    $subTitle = 'Deposit Report';
    $script = '<script>
                    let table = new DataTable("#dataTable");
               </script>';
@endphp

@section('content')
<div class="card basic-data-table">
    <div class="card-header">
        <h5 class="card-title mb-0">Deposit Report
            @if($is_admin != 1)
                <button type="button" style="float:right" class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2"
                        data-bs-toggle="modal" data-bs-target="#depositModel">
                    <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                    Deposit Amount
                </button>
            @endif
        </h5>
    </div>

    <div class="card-body">

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table bordered-table mb-0" id="dataTable" data-page-length="10">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Amount</th>
                        <th>Transaction Id</th>
                        <th>Status</th>
                        <th>Attachment</th>
                        <th>Approved Date</th>
                        <th>Created Date</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($transactions as $tx)
                    <tr data-idrow="{{ $tx['deposit_id'] }}">
                        <td>{{ $tx['fullname'] ?? '-' }}</td>
                        <td>{{ number_format($tx['amount'], 8) }}</td>
                        <td>{{ $tx['transaction_id'] }}</td>

                        <td class="status-col">
                            @if($tx['status'] == '1')
                                <span class="bg-success-focus text-success-main px-16 py-4 radius-4 fw-medium text-sm">Processed</span>
                            @else
                                <span class="bg-danger-focus text-danger-main px-16 py-4 radius-4 fw-medium text-sm">Pending</span>
                            @endif
                        </td>

                        <td>
                            <img src="{{ $tx['attachment'] ? $apiBase.'/uploads/'.$tx['attachment'] : asset('assets/images/no-image.jpg') }}"
                                 style="width:60px;height:60px;object-fit:cover;">
                        </td>

                         <td class="approved-col">
                            {{ isset($tx['approved_at']) ? \Carbon\Carbon::parse($tx['approved_at'])->format('d-M-Y') : '-' }}
                        </td>
                        <td>{{ isset($tx['created_at']) ? \Carbon\Carbon::parse($tx['created_at'])->format('d-M-Y') : '-' }}</td>

                        <td class="action-col">
                            @if($is_admin == 1 && $tx['status'] != 1)
                            <button type="button"
                                    class="approveDepositBtn bg-success-focus bg-hover-success-200 text-success-600 fw-medium w-40-px h-40-px 
                                           d-flex justify-content-center align-items-center rounded-circle"
                                    data-bs-toggle="modal"
                                    data-bs-target="#depositApproveModal"
                                    data-id="{{ $tx['deposit_id'] }}">
                                <iconify-icon icon="mdi:check" class="menu-icon"></iconify-icon>
                            </button>
                            @endif
                        </td>
                        
                    </tr>
                @empty
                    <tr data-idrow="1">
                        <td colspan="8" class="text-center">Record not found.</td>
                    </tr>
                @endforelse
                </tbody>

            </table>
        </div>

    </div>
</div>

 @if($is_admin != 1)

{{-- Deposit Form Modal --}}
<div class="modal fade" id="depositModel" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content radius-16 bg-base">

      <div class="modal-header">
        <h5 class="modal-title">Deposit Form</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body px-32 py-32">

        @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif

             <form action="{{ route('saveDeposit') }}" method="POST" enctype="multipart/form-data" class="d-flex flex-column gap-3">
            @csrf

            <div class="col-12">
                <label class="form-label fw-bold">Amount Deposited:</label>
                <input type="number" step="any" name="amount" class="form-control" required>
            </div>

            <div class="col-12">
                <label class="form-label fw-bold">Attach Transaction Screenshot:</label>
                <input type="file" name="attachment" class="form-control" required>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary mt-2">Submit</button>
            </div>

        </form>

      </div>

    </div>
  </div>
</div>
@else

{{-- APPROVE DEPOSIT MODAL --}}
<div class="modal fade" id="depositApproveModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content radius-16 bg-base">

      <div class="modal-header">
        <h5 class="modal-title">Approve Deposit</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body text-center">

        <p class="fw-bold text-lg">Are you sure you want to approve this deposit?</p>

        <form id="depositApproveForm" class="ajax-form" method="POST" action="{{ route('deposit.approve') }}">
          @csrf
          <input type="hidden" name="deposit_id" id="approveDepositId">

          <div class="d-flex justify-content-center gap-3 mt-4">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

              <button type="submit" class="btn btn-success ajax-submit">
                <span class="btn-text">Approve</span>
                <span class="spinner-border spinner-border-sm ms-2" role="status" style="display:none"></span>
              </button>
          </div>
        </form>

        <div id="depositApproveMsg" class="mt-3"></div>

      </div>

    </div>
  </div>
</div>
@endif

@endsection


{{-- JAVASCRIPT --}}
<script>

// OPEN MODAL & SET ID
document.addEventListener("click", function(e) {
    const btn = e.target.closest(".approveDepositBtn");
    if (!btn) return;
    document.getElementById("approveDepositId").value = btn.dataset.id;
});


// AJAX APPROVAL
document.addEventListener('submit', async function(event) {

    const form = event.target;
    if (!form.classList.contains('ajax-form')) return;
    event.preventDefault();

    const id = document.getElementById("approveDepositId").value;
    const row = document.querySelector(`[data-idrow='${id}']`);

    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    const btn = form.querySelector('.ajax-submit');
    const spinner = btn.querySelector('.spinner-border');
    const msgBox = document.getElementById("depositApproveMsg");

    btn.disabled = true;
    spinner.style.display = 'inline-block';
    msgBox.innerHTML = '';

    try {
        const res = await fetch(form.action, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrf,
                "Accept": "application/json"
            },
            body: JSON.stringify({ deposit_id: id })
        });

        const json = await res.json();

        if (json.status === "ok") {

            msgBox.innerHTML = `<div class="alert alert-success">${json.message}</div>`;

            const modal = bootstrap.Modal.getInstance(document.getElementById("depositApproveModal"));
            modal.hide();

            // Update row
            row.querySelector(".status-col").innerHTML =
                `<span class="bg-success-focus text-success-main px-16 py-4 radius-4 fw-medium text-sm">Processed</span>`;

            row.querySelector(".approved-col").innerHTML = json.approved_at;

            const action = row.querySelector(".action-col");
            if (action) action.innerHTML = "";

        } else {
            msgBox.innerHTML = `<div class="alert alert-danger">${json.message}</div>`;
        }

    } catch (err) {
        msgBox.innerHTML = `<div class="alert alert-danger">Network Error</div>`;
    }

    btn.disabled = false;
    spinner.style.display = "none";
});
</script>
