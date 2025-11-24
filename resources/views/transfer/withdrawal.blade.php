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
<div class="modal fade" id="exampleModalEdit" tabindex="-1" aria-labelledby="exampleModalEditLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content radius-16 bg-base">
      <div class="modal-header">
        <h5 class="modal-title">Withdrawal Request Form</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body px-32 py-56">
        <form id="transferForm" class="ajax-form" method="POST" action="{{ route('ajaxWithdrawal') }}">
          @csrf

          <div class="col-12 mb-3">
            <label for="t_amount" class="form-label fw-bold">Amount:</label>
            <input type="number" name="amount" id="t_amount" class="form-control" placeholder="Enter Request Amount" required>
            <div class="invalid-feedback" id="error-amount" style="display:none"></div>
          </div>

          <div class="col-12">
            <button type="submit" class="btn btn-primary ajax-submit">
              <span class="btn-text">Submit</span>
              <span class="spinner-border spinner-border-sm ms-2" role="status" style="display:none"></span>
            </button>
          </div>
        </form>

        <div id="transferMessage" class="mt-2"></div>
      </div>
    </div>
  </div>
</div>




@endsection

<script>
(function () {
  document.addEventListener('DOMContentLoaded', () => {
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

    function showMessage(container, html, type = 'info') {
      if (!container) { console.log('Message:', html); return; }
      const clsMap = { success: 'alert alert-success', danger: 'alert alert-danger', warning: 'alert alert-warning', info: 'alert alert-info' };
      container.innerHTML = `<div class="${clsMap[type] || clsMap.info}">${html}</div>`;
    }

    function clearValidation(form) {
      form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
      form.querySelectorAll('.invalid-feedback').forEach(div => { div.style.display = 'none'; div.textContent = ''; });
    }

    function showValidationErrors(form, errors) {
      Object.entries(errors).forEach(([k, v]) => {
        const input = form.querySelector(`[name="${k}"]`);
        const errDiv = form.querySelector(`#error-${k}`);
        if (input) input.classList.add('is-invalid');
        if (errDiv) { errDiv.textContent = Array.isArray(v) ? v[0] : v; errDiv.style.display = 'block'; }
      });
    }

    // Use delegated submit handling so forms inside modals are handled even if injected later
    document.addEventListener('submit', async function (event) {
      const form = event.target;
      if (!form || !form.classList.contains('ajax-form')) return; // only handle forms with .ajax-form

      event.preventDefault();
      clearValidation(form);

      // UI elements within this form (defaults if missing)
      const submitBtn = form.querySelector('.ajax-submit');
      const btnText = submitBtn ? submitBtn.querySelector('.btn-text') : null;
      const spinner = submitBtn ? submitBtn.querySelector('.spinner-border') : null;

      // Per-form message container: prefer #<formId>Message, else next sibling
      const msgContainer = (form.id && document.getElementById(form.id + 'Message')) || form.nextElementSibling;

      if (msgContainer) msgContainer.innerHTML = '';

      // Collect payload
      const data = Object.fromEntries(new FormData(form).entries());

      // Lock UI
      if (submitBtn) submitBtn.disabled = true;
      if (spinner) spinner.style.display = 'inline-block';
      if (btnText) btnText.setAttribute('aria-hidden', 'true');

      try {
        const res = await fetch(form.action, {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(data),
        });

        let json = {};
        try { json = await res.json(); } catch (e) { json = {}; }

        // Laravel validation (422)
        if (res.status === 422 && json.errors) {
          showValidationErrors(form, json.errors);
          showMessage(msgContainer, 'Please fix the form errors.', 'warning');
          return;
        }

        // Prefer server-indicated status field if present
        const apiStatus = (json.status || '').toLowerCase();

        if (apiStatus === 'ok') {
          showMessage(msgContainer, json.message || 'Operation successful.', 'success');

          // optional: close modal if inside one
          try {
            const modalEl = form.closest('.modal');
            if (modalEl) {
              const bsModal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
              bsModal.hide();
            }
          } catch (e) {
            // ignore if bootstrap not available
          }

          form.reset();
        } else {
          // show server side message or fallback to message/error fields
          const errMsg = json.message || json.error || `Server error (${res.status})`;
          showMessage(msgContainer, errMsg, 'danger');
        }

      } catch (err) {
        console.error('Ajax form error:', err);
        showMessage(msgContainer, 'Network error — please try again.', 'danger');
      } finally {
        if (submitBtn) submitBtn.disabled = false;
        if (spinner) spinner.style.display = 'none';
        if (btnText) btnText.removeAttribute('aria-hidden');
      }
    });
  });
})();
</script>