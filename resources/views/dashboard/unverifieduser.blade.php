@extends('layout.layout')
@php
    $title = 'User List';
    $subTitle = 'User List';
    $script = '<script>
                    let table = new DataTable("#dataTable");
               </script>';
@endphp

@section('content')

<style>
.popup-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.3);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.popup-content {
    background: #fff;
    padding: 15px 20px;
    border-radius: 5px;
    text-align: center;
    min-width: 250px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    font-family: Arial, sans-serif;
}

.btn-confirm {
    background: #28a745;
    color: #fff;
    padding: 6px 12px;
    margin-right: 5px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
    font-size: 14px;
}

.btn-cancel {
    background: #dc5b67ff;
    color: #fff;
    padding: 6px 12px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
    font-size: 14px;
}
</style>

<div class="card basic-data-table">
    <div class="card-header">
        <h5 class="card-title mb-0">User List     
        </h5>
    </div>

    <div class="card-body">
        <!-- Responsive table container -->
        <div class="table-responsive">
            <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Token Address</th>
                        <th>Email</th>
                        <th>Phone No</th>
                        <th>KYC Status</th>
                        <th>Status</th>
                        <th>Created Date</th>                        
                        <th>Action</th>                        
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $tx)
                    <tr>
                        <td>
                             <div class="d-flex align-items-center">
                                        <img src="{{ $tx['profile_image'] ? $apiBase.'/uploads/'. $tx['profile_image'] : asset('assets/images/profile.png') }}" alt="" class="w-40-px h-40-px rounded-circle flex-shrink-0 me-12 overflow-hidden">
                                   
                                            <div class="flex-grow-1">
                                                <span class="text-md mb-0 fw-normal text-secondary-light">{{ $tx['fullname'] ?? '-' }}</span>
                                            </div>
                                        </div>
                        
                        
                    </td>
                        <td>{{ $tx['address'] ?? '-' }}</td>
                        <td>{{ $tx['email'] }}</td>
                        <td>{{ $tx['phone'] }}</td>
                        <td><span class="bg-danger-focus text-danger-main px-16 py-4 radius-4 fw-medium text-sm">Pending</span></td>
                        <td>
                              @if($tx['is_active'] == '1')
                                <span class="bg-success-focus text-success-main px-16 py-4 radius-4 fw-medium text-sm">Active</span>
                            @else
                                <span class="bg-danger-focus text-danger-main px-16 py-4 radius-4 fw-medium text-sm">Inactive</span>
                            @endif
                        </td>
                        <td>{{ isset($tx['created_at']) ? \Carbon\Carbon::parse($tx['created_at'])->format('d/m/Y') : '-' }}</td>
                        
                        <td class="text-center">
                                <div class="d-flex align-items-center gap-10 justify-content-center">
                                    <a href="{{ route('profile.view', ['id' => $tx['id']]) }}"
                                        title="View" 
                                        class="bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle">
                                        <iconify-icon icon="majesticons:eye-line" class="icon text-xl"></iconify-icon>
                                    </a>
                                    
                                    <button type="button" class="status-btn bg-warning-focus bg-hover-warning-200 text-warning-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle" data-user-id="{{ $tx['id'] }}" data-status="{{ $tx['is_active'] }}">
                                        <iconify-icon icon="mdi:account-check-outline" class="icon text-xl" style="pointer-events:none"></iconify-icon>
                                    </button>

                                    <button type="button" 
                                            title="Approve KYC"
                                            class="kyc-btn bg-success-focus bg-hover-success-200 text-success-600 fw-medium 
                                            w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle"
                                            data-user-id="{{ $tx['id'] }}">
                                            <iconify-icon icon="mdi:shield-check-outline" class="menu-icon"></iconify-icon>
                                    </button>
                                </div>
                            </td>
                    </tr>
                    @empty                    
                    @endforelse
                </tbody>
            </table>
        </div>
        
    </div>
</div>

<div id="status-popup" class="popup-overlay" style="display:none;">
    <div class="popup-content">
        <p id="status-message"></p>
        <form id="status-form" method="POST" action="{{ route('dashboard.changeStatus') }}">
            @csrf
            <input type="hidden" name="id" id="popup-user-id">
            <button type="submit" class="btn-confirm">Yes</button>
            <button type="button" class="btn-cancel">No</button>
        </form>
    </div>
</div>

<div id="kyc-popup" class="popup-overlay" style="display:none;">
    <div class="popup-content">
        <p>Are you sure you want to approve KYC?</p>
        <form id="kyc-form" method="POST" action="{{ route('dashboard.kycVerify') }}">
            @csrf
            <input type="hidden" name="id" id="kyc-user-id">
            <button type="submit" class="btn-confirm">Yes</button>
            <button type="button" class="btn-cancel kyc-cancel">No</button>
        </form>
    </div>
</div>
@endsection
<script>
document.addEventListener('DOMContentLoaded', function() {

    // Existing popup
    const popup = document.getElementById('status-popup');
    const popupUserId = document.getElementById('popup-user-id');
    const statusButtons = document.querySelectorAll('.status-btn');
    const btnCancel = popup.querySelector('.btn-cancel');

    // New KYC popup
    const kycPopup = document.getElementById('kyc-popup');
    const kycUserId = document.getElementById('kyc-user-id');
    const kycButtons = document.querySelectorAll('.kyc-btn');
    const kycCancel = document.querySelector('.kyc-cancel');

    // Existing status toggle
    statusButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            popup.style.display = 'flex';
            popupUserId.value = this.getAttribute('data-user-id');
        });
    });
    btnCancel.addEventListener('click', function() {
        popup.style.display = 'none';
    });

    kycButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            kycPopup.style.display = 'flex';
            kycUserId.value = this.getAttribute('data-user-id');
        });
    });

    kycCancel.addEventListener('click', function() {
        kycPopup.style.display = 'none';
    });

});

document.addEventListener('DOMContentLoaded', function () {

    const popup = document.getElementById('status-popup');
    const popupUserId = document.getElementById('popup-user-id');
    const statusMessage = document.getElementById('status-message');
    const statusButtons = document.querySelectorAll('.status-btn');
    const btnCancel = popup.querySelector('.btn-cancel');

    statusButtons.forEach(btn => {

        const status = btn.getAttribute('data-status');

        // ✅ Dynamic title
        btn.setAttribute(
            'title',
            status === '1' ? 'Deactivate User' : 'Activate User'
        );

        // ✅ Popup message
        btn.addEventListener('click', function () {
            popupUserId.value = this.getAttribute('data-user-id');

            statusMessage.textContent =
                status === '1'
                ? 'Are you sure you want to deactivate this user?'
                : 'Are you sure you want to activate this user?';

            popup.style.display = 'flex';
        });
    });

    btnCancel.addEventListener('click', function () {
        popup.style.display = 'none';
    });

});
</script>
