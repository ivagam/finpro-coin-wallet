@extends('layout.layout')
@php
    $title = 'User List';
    $subTitle = 'User List';
@endphp

@section('content')
<div class="card basic-data-table">
    <div class="card-header">
        <h5 class="card-title mb-0">User List 
     
        </h5>
    </div>

    <div class="card-body">
        <!-- Responsive table container -->
        <div class="table-responsive">
            <table class="table bordered-table mb-0 align-middle text-nowrap">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Token Address</th>
                        <th>Email</th>
                        <th>Phone No</th>
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
                                    <button type="button" class="bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle">
                                        <iconify-icon icon="majesticons:eye-line" class="icon text-xl"></iconify-icon>
                                    </button>
                                    <button type="button" class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle">
                                        <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                    </button>
                                    <button type="button" class="remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle">
                                        <iconify-icon icon="fluent:delete-24-regular" class="menu-icon"></iconify-icon>
                                    </button>
                                </div>
                            </td>
                        

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
@endsection
