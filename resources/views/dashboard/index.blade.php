@extends('layout.layout')
@php
    $title='Dashboard';
    $subTitle = 'Cryptocracy';
@endphp

@section('content')

            <!-- Crypto Home Widgets Start -->
            <div class="row row-cols-xxxl-5 row-cols-lg-3 row-cols-sm-2 row-cols-1 gy-4">

               
                        @if($is_admin == 1)
                         <div class="col">
                    <div class="card shadow-none border bg-gradient-end-3">
                        <div class="card-body p-20">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <img src="{{ asset('assets/images/finxcore-favi.png') }}" alt="" class="w-40-px h-40-px rounded-circle flex-shrink-0">
                                <div class="flex-grow-1">
                                    <h6 class="text-xl mb-1">Finxcore</h6>
                                    <p class="fw-medium text-secondary-light mb-0">FXC</p>
                                </div>
                            </div>
                            <div class="mt-3 d-flex flex-wrap justify-content-between align-items-center gap-1">
                                <div class="">
                                    <h6 class="mb-8">{{ number_format($data['total_balance'], 2) }} </h6>
                                </div>
                            </div>
                        </div>
                          </div>
                </div> 
                <div class="col">
                    <div class="card shadow-none border bg-gradient-end-3">
                        <div class="card-body p-20">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <img src="{{ asset('assets/images/finxcore-favi.png') }}" alt="" class="w-40-px h-40-px rounded-circle flex-shrink-0">
                                <div class="flex-grow-1">
                                    <h6 class="text-xl mb-1">Admin Wallet Balance</h6>
                                    <p class="fw-medium text-secondary-light mb-0">FXC</p>
                                </div>
                            </div>
                            <div class="mt-3 d-flex flex-wrap justify-content-between align-items-center gap-1">
                                <div class="">
                                    <h6 class="mb-8">{{ number_format($data['balance'], 2) }} </h6>
                                </div>
                            </div>
                        </div>
                          </div>
                </div> 
                <div class="col">
                    <div class="card shadow-none border bg-gradient-end-3">
                        <div class="card-body p-20">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <img src="{{ asset('assets/images/group.jpg') }}" alt="" class="w-40-px h-40-px rounded-circle flex-shrink-0">
                                <div class="flex-grow-1">
                                    <h6 class="text-xl mb-1">Total User</h6>
                                </div>
                            </div>
                            <div class="mt-3 d-flex flex-wrap justify-content-between align-items-center gap-1">
                                <div class="">
                                    <h6 class="mb-8">{{ $data['total_users'] }} </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>   
                        @else
                         <div class="col">
                    <div class="card shadow-none border bg-gradient-end-3">
                        <div class="card-body p-20">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <img src="{{ asset('assets/images/finxcore-favi.png') }}" alt="" class="w-40-px h-40-px rounded-circle flex-shrink-0">
                                <div class="flex-grow-1">
                                    <h6 class="text-xl mb-1">Finxcore</h6>
                                    <p class="fw-medium text-secondary-light mb-0">FXC</p>
                                </div>
                            </div>
                            <div class="mt-3 d-flex flex-wrap justify-content-between align-items-center gap-1">
                                <div class="">
                                    <h6 class="mb-8">{{ number_format($data['balance'], 2) }} </h6>
                                </div>
                            </div>
                        </div>
                          </div>
                </div> 
                <div class="col">
                    <div class="card shadow-none border bg-gradient-end-3">
                        <div class="card-body p-20">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <img src="{{ asset('assets/images/dollar.png') }}" alt="" class="w-40-px h-40-px rounded-circle flex-shrink-0">
                                <div class="flex-grow-1">
                                    <h6 class="text-xl mb-1">Currency Value</h6>
                                    <p class="fw-medium text-secondary-light mb-0">USD</p>
                                </div>
                            </div>
                            <div class="mt-3 d-flex flex-wrap justify-content-between align-items-center gap-1">
                                <div class="">
                                    <h6 class="mb-8">${{ number_format($data['balance'], 2) }} </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>   
                        @endif
                              

                

            </div>
            <!-- Crypto Home Widgets End -->

            <div class="row gy-4 mt-4">

                <!-- Crypto Home Widgets Start -->
                <div class="col-xxl-8">
                   
                    <div class="row gy-4">
                        <div class="col-xxl-12">
                            <div style="margin-bottom:15px" class="p-16 bg-neutral-50 radius-8 border-start-width-3-px border-neutral-600 border-top-0 border-end-0 border-bottom-0">
                                        <h6 class="text-primary-light text-md mb-8">Token Address</h6>
                                        <span class="text-primary-light mb-0">{{ session('user.address') }}</span>
                                    </div>

                            <div class="card h-100">
                                <div class="card-body p-24">
                                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between mb-20">
                                        <h6 class="mb-2 fw-bold text-lg mb-0">Recent Transaction</h6>
                                        <a href="{{ route('transferHistory') }}" class="text-primary-600 hover-text-primary d-flex align-items-center gap-1">
                                            View All
                                            <iconify-icon icon="solar:alt-arrow-right-linear" class="icon"></iconify-icon>
                                        </a>
                                    </div>
                                    <div class="table-responsive scroll-sm">
                                        @if(!empty($data['recentTransactions']))
                                        <table class="table bordered-table mb-0 xsm-table">
                                            <thead>
                                                    <tr>
                                                        <th>Ast</th>
                                                        <th>User</th>
                                                        <th>Amount</th>
                                                        <th>Type</th>
                                                        <th>Status</th>
                                                        <th>Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($data['recentTransactions'] as $tx)
                                                        <tr>
                                                            <td>
                                                            @if($tx['id'] == $cuser)  
                                                        <div class="d-flex align-items-center gap-2">
                                                            <span class="text-success-main bg-success-focus w-32-px h-32-px d-flex align-items-center justify-content-center rounded-circle text-xl">
                                                                <iconify-icon icon="tabler:arrow-up-right" class="icon"></iconify-icon>
                                                            </span>
                                                            <span class="fw-medium">FXC</span>
                                                        </div>
                                                            @else
                                                        <div class="d-flex align-items-center gap-2">
                                                            <span class="text-danger-main bg-danger-focus w-32-px h-32-px d-flex align-items-center justify-content-center rounded-circle text-xl">
                                                                <iconify-icon icon="tabler:arrow-down-left" class="icon"></iconify-icon>
                                                            </span>
                                                            <span class="fw-medium">FXC</span>
                                                        </div>
                                                            @endif  
                                                            </td>
                                                            <td>{{ $tx['fromusername'] ?? '-' }}</td>
                                                            <td>{{ $tx['amount'] }}</td>
                                                            <td>{{ ucfirst($tx['txn_type']) }}</td>
                                                            <td>
                                                                @if($tx['status'] == 'confirmed')
                                                                    <span class="bg-success-focus text-success-main px-16 py-4 radius-4 fw-medium text-sm">Confirmed</span>

                                                                @else
                                                        <span class="bg-danger-focus text-danger-main px-16 py-4 radius-4 fw-medium text-sm">Pending</span>
                                                                @endif
                                                            </td>
                                                            <td><span class="text-secondary-light text-sm">{{ \Carbon\Carbon::parse($tx['created_at'])->format('Y-m-d H:i') }}</span></td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            <p>No transactions found.</p>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Crypto Home Widgets End -->

                <div class="col-xxl-4">
        <div class="row gy-4">

        @if($is_admin == 1)
            <div class="col-xxl-12 col-lg-6">
                <div class="card h-100">
                    <div class="card-body p-24">

                        <span class="mb-4 text-sm text-secondary-light">Admin Wallet Balance</span>
                        <h6 class="mb-4">{{ number_format($data['balance'], 2) }}</h6>

                        <ul class="nav nav-pills pill-tab mb-24 mt-28 border input-form-light p-1 radius-8 bg-neutral-50" id="pills-tab" role="tablist">
                            <li class="nav-item w-50">
                                <button class="nav-link px-12 py-10 text-md w-100 text-center radius-8 active"
                                    id="pills-Buy-tab" data-bs-toggle="pill" data-bs-target="#pills-Buy"
                                    type="button">Send</button>
                            </li>
                            <li class="nav-item w-50">
                                <button class="nav-link px-12 py-10 text-md w-100 text-center radius-8"
                                    id="pills-Sell-tab" data-bs-toggle="pill" data-bs-target="#pills-Sell"
                                    type="button">Burn</button>
                            </li>
                        </ul>

                        <div class="tab-content">

                            {{-- ========== ADMIN SEND FORM ========== --}}
                            <div class="tab-pane fade show active" id="pills-Buy">

                                <form id="transferForm" class="ajax-form" method="POST" action="{{ route('ajaxSend') }}">
                                    @csrf
                                    <input type="hidden" name="type" value="send">

                                    <div class="mb-20">
                                        <label class="fw-semibold mb-8 text-primary-light">Trade Value</label>
                                        <div class="input-group input-group-lg border input-form-light radius-8">
                                            <input type="number" id="t_amount" name="amount" class="form-control bg-base border-0 radius-8"
                                                placeholder="Trade Value" required>

                                            <div class="input-group-text bg-neutral-50 border-0 fw-normal text-md">
                                                <select class="form-select form-select-sm bg-transparent fw-bolder border-0">
                                                    <option>FXC</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-20">
                                        <label class="fw-semibold mb-8 text-primary-light">Trade Address</label>
                                        <textarea name="address" id="t_address" class="form-control bg-base h-80-px radius-8" placeholder="Enter Address" required></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-primary ajax-submit">
                                        <span class="btn-text">Send</span>
                                        <span class="spinner-border spinner-border-sm ms-2" role="status" style="display:none"></span>
                                    </button>
                                     
                                    <x-alert />
                                    
                                </form>
                                <div id="transferMessage" class="mt-2"></div>


                            </div>

                            {{-- ========== ADMIN BURN FORM ========== --}}
                            <div class="tab-pane fade" id="pills-Sell">

                                <form id="burnForm" method="POST" action="{{ route('ajaxBurn') }}">
                                    @csrf
                                    <input type="hidden" name="type" value="burn">

                                    <div class="mb-20">
                                        <label class="fw-semibold mb-8 text-primary-light">Trade Value</label>
                                        <div class="input-group input-group-lg border input-form-light radius-8">
                                            <input type="number" name="amount" id="amount" class="form-control border-0 radius-8"
                                                placeholder="Estimated Value" required>

                                            <div class="input-group-text bg-neutral-50 border-0 fw-normal text-md">
                                                <select class="form-select form-select-sm bg-transparent fw-bolder border-0">
                                                    <option>FXC</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="invalid-feedback" id="error-amount"></div>

                                    </div>

                                    {{-- 🔥 ADDED BURN ADDRESS FIELD --}}
                                    <div class="mb-20">
                                        <label class="fw-semibold mb-8 text-primary-light">Burn Address</label>
                                        <textarea name="address" id="address" class="form-control bg-base h-80-px radius-8"
                                                placeholder="Enter Burn Address" required></textarea>
                                                <div class="invalid-feedback" id="error-address"></div>

                                    </div>

                                    <button type="submit" id="burnSubmit" class="btn btn-primary">
                                    <span id="burnBtnText">Burn</span>
                                    <span id="burnSpinner" class="spinner-border spinner-border-sm ms-2" role="status" aria-hidden="true" style="display:none"></span>
                                    </button>
                                    
                                    <x-alert />
                                    
                                </form>
                                <div id="burnMessage" style="margin-top:12px;"></div>


                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @else
            <div class="col-xxl-12 col-lg-6">
                <div class="card h-100">
                    <div class="card-body p-24">

                        <span class="mb-4 text-sm text-secondary-light">Total Balance</span>
                        <h6 class="mb-4">{{ number_format($data['balance'], 2) }}</h6>

                        <ul class="nav nav-pills pill-tab mb-24 mt-28 border input-form-light p-1 radius-8 bg-neutral-50" id="pills-tab" role="tablist">
                            <li class="nav-item w-50" role="presentation">
                                <button class="nav-link px-12 py-10 text-md w-100 text-center radius-8 active"
                                    id="pills-Send-tab" data-bs-toggle="pill" data-bs-target="#pills-Send"
                                    type="button" role="tab" aria-controls="pills-Send" aria-selected="true">
                                    Send
                                </button>
                            </li>

                            <li class="nav-item w-50" role="presentation">
                                <button class="nav-link px-12 py-10 text-md w-100 text-center radius-8"
                                    id="pills-Withdraw-tab" data-bs-toggle="pill" data-bs-target="#pills-Withdraw"
                                    type="button" role="tab" aria-controls="pills-Withdraw" aria-selected="false">
                                    Withdraw
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content" id="pills-tabContent">

                            {{-- ================= SEND FORM ================= --}}
                            <div class="tab-pane fade show active" id="pills-Send" role="tabpanel" aria-labelledby="pills-Send-tab">

                                  <form id="transferForm" class="ajax-form" method="POST" action="{{ route('ajaxSend') }}">
                                    @csrf
                                    <input type="hidden" name="type" value="send">

                                    <div class="mb-20">
                                        <label class="fw-semibold mb-8 text-primary-light">Trade Value</label>
                                        <div class="input-group input-group-lg border input-form-light radius-8">
                                            <input type="number" id="t_amount" name="amount" class="form-control bg-base border-0 radius-8"
                                                placeholder="Trade Value" required>

                                            <div class="input-group-text bg-neutral-50 border-0 fw-normal text-md">
                                                <select class="form-select form-select-sm bg-transparent fw-bolder border-0">
                                                    <option>FXC</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-20">
                                        <label class="fw-semibold mb-8 text-primary-light">Trade Address</label>
                                        <textarea name="address" id="t_address" class="form-control bg-base h-80-px radius-8" placeholder="Enter Address" required></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-primary ajax-submit">
                                        <span class="btn-text">Send</span>
                                        <span class="spinner-border spinner-border-sm ms-2" role="status" style="display:none"></span>
                                    </button>
                                     
                                    <x-alert />
                                    
                                </form>
                                <div id="transferMessage" class="mt-2"></div>

                            </div>

                            {{-- ================= WITHDRAW / BURN FORM ================= --}}
                            <div class="tab-pane fade" id="pills-Withdraw" role="tabpanel" aria-labelledby="pills-Withdraw-tab">

                                <form id="transferForm1" class="ajax-form1" method="POST" action="{{ route('ajaxWithdrawal') }}">
                                    @csrf
                                    <input type="hidden" name="type" value="burn">

                                    <div class="mb-20">
                                        <label class="fw-semibold mb-8 text-primary-light">Trade Value</label>
                                        <div class="input-group input-group-lg border input-form-light radius-8">
                                            <input type="text" name="amount" id="t_amount1" class="form-control bg-base border-0 radius-8"
                                                placeholder="Enter Amount" required>

                                            <div class="input-group-text bg-neutral-50 border-0 fw-normal text-md">
                                                <select class="form-select form-select-sm bg-transparent fw-bolder border-0">
                                                    <option>FXC</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
            <button type="submit" class="btn btn-primary ajax-submit1">
              <span class="btn-text">Submit</span>
              <span class="spinner-border spinner-border-sm ms-2" role="status" style="display:none"></span>
            </button>
                                    <x-alert />
                                </form>
                                        <div id="transferMessage1" class="mt-2"></div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        </div>
    </div>
</div>     
@endsection

<script>
(function () {
  // Run when DOM is ready
  document.addEventListener('DOMContentLoaded', () => {
    // Get elements (will be null if not present on the page)
    const burnForm = document.getElementById('burnForm');
    if (!burnForm) return; // do nothing on pages without the form

    const submitBtn = document.getElementById('burnSubmit') || burnForm.querySelector('[type="submit"]');
    const spinner = document.getElementById('burnSpinner');
    const btnText = document.getElementById('burnBtnText');
    const messageEl = document.getElementById('burnMessage');

    // Utility: show message
    function showMessage(html, type = 'info') {
      if (!messageEl) return console.log('Message:', html);
      const cls = {
        success: 'alert alert-success',
        danger: 'alert alert-danger',
        warning: 'alert alert-warning',
        info: 'alert alert-info'
      }[type] || 'alert alert-info';
      messageEl.innerHTML = `<div class="${cls}">${html}</div>`;
    }

    // Utility: clear server-side validation errors
    function clearErrors() {
      ['address', 'amount'].forEach(name => {
        const err = document.getElementById('error-' + name);
        if (err) { err.textContent = ''; err.style.display = 'none'; }
        const input = burnForm.querySelector(`[name="${name}"]`);
        if (input) input.classList.remove('is-invalid');
      });
    }

    // Utility: display validation errors from Laravel
    function showValidationErrors(errors = {}) {
      Object.entries(errors).forEach(([k, v]) => {
        const input = burnForm.querySelector(`[name="${k}"]`);
        const errDiv = document.getElementById('error-' + k);
        if (input) input.classList.add('is-invalid');
        if (errDiv) { errDiv.textContent = Array.isArray(v) ? v[0] : v; errDiv.style.display = 'block'; }
      });
    }

    // Main submit handler (async + catches errors)
    burnForm.addEventListener('submit', async function (e) {
      e.preventDefault();
      clearErrors();
      if (messageEl) messageEl.innerHTML = '';

      // collect form data
      const formData = new FormData(burnForm);
      const data = Object.fromEntries(formData.entries());

      // UX: disable button + show spinner
      if (submitBtn) submitBtn.disabled = true;
      if (spinner) spinner.style.display = 'inline-block';
      if (btnText) btnText.setAttribute('aria-hidden', 'true');

      // CSRF token from meta (Laravel)
      const csrfMeta = document.querySelector('meta[name="csrf-token"]');
      const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : null;

      try {
        const res = await fetch(burnForm.action, {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken || '',      // Laravel CSRF
            'X-Requested-With': 'XMLHttpRequest', // identify AJAX
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(data)
        });

        // Try to parse JSON safely
        let payload = {};
        try { payload = await res.json(); } catch (err) { payload = {}; }

        if (res.ok) {
          // success
          showMessage(payload.message || 'Burn successful!', 'success');
          burnForm.reset();
        } else if (res.status === 422 && payload.errors) {
          // validation errors
          showValidationErrors(payload.errors);
          showMessage('Please fix the validation errors.', 'warning');
        } else {
          // other server errors
          const errMsg = payload.message || payload.error || `Server error (${res.status})`;
          showMessage(errMsg, 'danger');
        }
      } catch (err) {
        // network or unexpected error (caught to prevent Uncaught)
        console.error('Network/Unexpected error:', err);
        showMessage('Network error — please try again later.', 'danger');
      } finally {
        // restore UI
        if (submitBtn) submitBtn.disabled = false;
        if (spinner) spinner.style.display = 'none';
        if (btnText) btnText.removeAttribute('aria-hidden');
      }
    });
  });
})();
</script>

<script>
(function () {
  document.addEventListener('DOMContentLoaded', () => {
    // Helper to show messages per-form
    function showMessage(container, html, type = 'info') {
      if (!container) { console.log(html); return; }
      const cls = {
        success: 'alert alert-success',
        danger: 'alert alert-danger',
        warning: 'alert alert-warning',
        info: 'alert alert-info'
      }[type] || 'alert alert-info';
      container.innerHTML = `<div class="${cls}">${html}</div>`;
    }

    function clearValidation(form) {
      form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
      form.querySelectorAll('.invalid-feedback').forEach(div => { div.style.display='none'; div.textContent=''; });
    }

    function showValidationErrors(form, errors) {
      Object.entries(errors).forEach(([k, v]) => {
        const input = form.querySelector(`[name="${k}"]`);
        const errDiv = form.querySelector(`#error-${k}`);
        if (input) input.classList.add('is-invalid');
        if (errDiv) { errDiv.textContent = Array.isArray(v) ? v[0] : v; errDiv.style.display = 'block'; }
      });
    }

    // Bind all forms with class .ajax-form
    const forms = document.querySelectorAll('form.ajax-form');
    if (!forms.length) return;

    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

    forms.forEach(form => {
      const submitBtn = form.querySelector('.ajax-submit');
      const btnText = submitBtn ? submitBtn.querySelector('.btn-text') : null;
      const spinner = submitBtn ? submitBtn.querySelector('.spinner-border') : null;

      form.addEventListener('submit', async function (e) {
        e.preventDefault();
        clearValidation(form);

        // per-form message container: if id exists (formid + 'Message'), use it, else fallback to next sibling
        const formId = form.id || '';
        const messageContainer = document.getElementById(formId + 'Message') || form.nextElementSibling;

        // collect form data
        const fm = new FormData(form);
        const payload = Object.fromEntries(fm.entries());

        // ui lock
        if (submitBtn) { submitBtn.disabled = true; }
        if (spinner) spinner.style.display = 'inline-block';
        if (btnText) btnText.setAttribute('aria-hidden', 'true');
        if (messageContainer) messageContainer.innerHTML = '';

        try {
          const res = await fetch(form.action, {
            method: 'POST',
            headers: {
              'Accept': 'application/json',
              'X-CSRF-TOKEN': csrfToken,
              'X-Requested-With': 'XMLHttpRequest',
              'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
          });

          let payloadJson = {};
          try { payloadJson = await res.json(); } catch (err) { payloadJson = {}; }

          if (res.status === 422 && payloadJson.errors) {
            showValidationErrors(form, payloadJson.errors);
            showMessage(messageContainer, 'Please fix the validation errors.', 'warning');
          } else {
            const apiStatus = (payloadJson.status || '').toLowerCase();

            if (apiStatus === 'ok') {
              showMessage(messageContainer, payloadJson.message || 'Operation succeeded', 'success');
              form.reset();
            } else {
              // show server-provided message or error
              const errMsg = payloadJson.message || payloadJson.error || `Server error (${res.status})`;
              showMessage(messageContainer, errMsg, 'danger');
            }
          }
        } catch (err) {
          console.error('Ajax form error', err);
          showMessage(messageContainer, 'Network error — please try again.', 'danger');
        } finally {
          if (submitBtn) submitBtn.disabled = false;
          if (spinner) spinner.style.display = 'none';
          if (btnText) btnText.removeAttribute('aria-hidden');
        }
      });
    });
  });
})();

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
      if (!form || !form.classList.contains('ajax-form1')) return; // only handle forms with .ajax-form

      event.preventDefault();
      clearValidation(form);

      // UI elements within this form (defaults if missing)
      const submitBtn = form.querySelector('.ajax-submit1');
      const btnText = submitBtn ? submitBtn.querySelector('.btn-text') : null;
      const spinner = submitBtn ? submitBtn.querySelector('.spinner-border') : null;

      // Per-form message container: prefer #<formId>Message, else next sibling
      const msgContainer = (form.id && document.getElementById(form.id + 'Message1')) || form.nextElementSibling;

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
