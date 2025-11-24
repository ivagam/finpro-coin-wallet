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
        <button type="button" style="float:right" class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2"
            data-bs-toggle="modal" data-bs-target="#exampleModalEdit">
            <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
            Withdrawal Request
        </button>
        @endif
        </h5>
    </div>

    <div class="card-body">

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
                    @forelse($transactions as $tx)
                    <tr id="row-{{ $tx['withdrawal_id'] }}">
                        <td>{{ $tx['fullname'] ?? '-' }}</td>
                        <td>{{ number_format($tx['amount'], 8) }}</td>
                        <td>{{ $tx['request_id'] }}</td>

                        <td class="status-col">
                            @if($tx['status'] == '1')
                                <span class="bg-success-focus text-success-main px-16 py-4 radius-4 fw-medium text-sm">
                                    Processed
                                </span>
                            @else
                                <span class="bg-danger-focus text-danger-main px-16 py-4 radius-4 fw-medium text-sm">
                                    Pending
                                </span>
                            @endif
                        </td>

                        <td class="approved-col">
                            {{ isset($tx['approved_at']) ? \Carbon\Carbon::parse($tx['approved_at'])->format('d-M-Y') : '-' }}
                        </td>
                        <td>{{ isset($tx['created_at']) ? \Carbon\Carbon::parse($tx['created_at'])->format('d-M-Y') : '-' }}</td>

                        @if($is_admin == 1)
                        <td class="text-center action-col">
                            @if($tx['status'] != 1)
                            <button type="button"
                                  class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px 
                                        d-flex justify-content-center align-items-center rounded-circle approveBtn"
                                  data-bs-toggle="modal"
                                  data-bs-target="#approveModal"
                                  data-id="{{ $tx['withdrawal_id'] }}">
                                  <iconify-icon icon="mdi:check" class="menu-icon"></iconify-icon>
                            </button>
                            @endif
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center">Record not found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>



{{-- APPROVE MODAL --}}
<div class="modal fade" id="approveModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content radius-16 bg-base">

      <div class="modal-header">
        <h5 class="modal-title">Approve Withdrawal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body text-center">

        <p class="fw-bold text-lg">Are you sure you want to approve this withdrawal?</p>

        <form id="approveForm" class="ajax-form" method="POST" action="{{ route('transfer.approve') }}">
          @csrf
          <input type="hidden" name="withdrawal_id" id="approveWithdrawalId">

          <div class="d-flex justify-content-center gap-3 mt-4">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

              <button type="submit" class="btn btn-success ajax-submit">
                <span class="btn-text">Approve</span>
                <span class="spinner-border spinner-border-sm ms-2" role="status" style="display:none"></span>
              </button>
          </div>
        </form>

        <div id="approveFormMessage" class="mt-3"></div>

      </div>

    </div>
  </div>
</div>

@endsection


{{-- JAVASCRIPT --}}
<script>

// OPEN MODAL AND SET HIDDEN FIELD
document.addEventListener("click", function(e) {
    const btn = e.target.closest(".approveBtn");
    if (!btn) return;

    document.getElementById("approveWithdrawalId").value = btn.dataset.id;
});


// AJAX FOR APPROVAL
document.addEventListener('submit', async function (event) {

    const form = event.target;
    if (!form.classList.contains('ajax-form')) return;
    event.preventDefault();

    const withdrawId = document.getElementById("approveWithdrawalId").value;
    const row = document.getElementById(`row-${withdrawId}`);

    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    const submitBtn = form.querySelector('.ajax-submit');
    const spinner = submitBtn.querySelector('.spinner-border');
    const msgBox = document.getElementById("approveFormMessage");

    submitBtn.disabled = true;
    spinner.style.display = 'inline-block';
    msgBox.innerHTML = '';

    try {
        const res = await fetch(form.action, {
            method: 'POST',
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": csrf
            },
            body: JSON.stringify({ withdrawal_id: withdrawId })
        });

        const json = await res.json();

        if (json.status === "ok") {

            msgBox.innerHTML = `<div class="alert alert-success">${json.message}</div>`;

            const modal = bootstrap.Modal.getInstance(document.getElementById("approveModal"));
            modal.hide();

            // Update row
            row.querySelector(".status-col").innerHTML =
                `<span class="bg-success-focus text-success-main px-16 py-4 radius-4 fw-medium text-sm">Processed</span>`;

            row.querySelector(".approved-col").innerHTML = json.approved_at;

            const actionCell = row.querySelector(".action-col");
            if (actionCell) actionCell.innerHTML = "";

        } else {
            msgBox.innerHTML = `<div class="alert alert-danger">${json.message}</div>`;
        }

    } catch (error) {
        msgBox.innerHTML = `<div class="alert alert-danger">Network Error</div>`;
    }

    submitBtn.disabled = false;
    spinner.style.display = 'none';

});
</script>