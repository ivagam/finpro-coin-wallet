@extends('layout.layout')
@php
    $title = 'Mint Transactions';
    $subTitle = 'Mint Transaction Report';
@endphp

@section('content')
<div class="card">
    <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div class="d-flex flex-wrap align-items-center gap-3">
            <div class="d-flex align-items-center gap-2">
                <span>Show</span>
                <select class="form-select form-select-sm w-auto">
                    <option>10</option>
                    <option>15</option>
                    <option>20</option>
                </select>
            </div>
            <div class="icon-field">
                <input type="text" name="search" class="form-control form-control-sm w-auto" placeholder="Search">
                <span class="icon">
                    <iconify-icon icon="ion:search-outline"></iconify-icon>
                </span>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table bordered-table mb-0 align-middle text-nowrap">
                <thead>
                    <tr>
                    <th style="width: 5%;">S.L</th>
                    <th style="width: 10%;">User</th>
                    <th style="width: 35%;">Address</th>                    
                    <th style="width: 20%;">Amount</th>
                    <th style="width: 20%;">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mintReport as $index => $tx)
                <tr>
                    <td>{{ $mintReport->firstItem() + $index }}</td>
                    <td>{{ $tx['fullname'] ?? 'N/A' }}</td>                    
                    <td class="text-truncate" style="max-width: 250px;">{{ $tx['to_address'] ?? 'N/A' }}</td>
                    <td>{{ number_format($tx['amount'], 8) }}</td>
                    <td>{{ isset($tx['created_at']) ? \Carbon\Carbon::parse($tx['created_at'])->format('d/m/Y') : 'N/A' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No mint transactions found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mt-24">
            <span>Showing {{ $mintReport->count() }} of {{ $mintReport->total() }} entries</span>
            <div>
                {{ $mintReport->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
