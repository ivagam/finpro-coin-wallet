@extends('layout.layout')
@php
    $title = 'Deposit Report';
    $subTitle = 'Deposit Report';
@endphp

@section('content')
<div class="card basic-data-table">
    <div class="card-header">
        <h5 class="card-title mb-0">Deposit Report 
                    @if($is_admin != 1)    
                <button type="button" style="float:right" class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#exampleModalEdit">

                        <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                        Deposit Amount
</button>
                @endif
            </h5>
    </div>

    <div class="card-body">
        <!-- Responsive table container -->
        <div class="table-responsive">
            <table class="table bordered-table mb-0 align-middle text-nowrap">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Amount</th>
                        <th>Transaction Id</th>
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
                        <td>
                            <span class="bg-success-focus text-success-main px-16 py-4 radius-4 fw-medium text-sm">Approve</span>
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
                <h5 class="modal-title">Deposit Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-32 py-56">
                    
            
             <form action="{{ route('mint.store') }}" method="POST" class="d-flex flex-column gap-3">
                    @csrf

                    {{-- Address --}}
                    <div class="col-12">
                        <label for="amount" class="form-label fw-bold">Amount Deposited:</label>
                        <input type="number" name="amount" id="amount"
                               class="form-control"
                               placeholder="Enter Deposit Amount" required
                               >
                       
                    </div>

                    {{-- Amount --}}
                    <div class="col-12">
                        <label for="attachment" class="form-label fw-bold">Attach Transaction Screenshot:</label>
                        <input type="file" name="attachment" id="attachment"
                               class="form-control" required>
                     
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary mt-2">Submit</button>
                    </div>
                </form>
                   
                
            </div>
        </div>
    </div>

@endsection
