@extends('layout.layout')
@php
    $title = 'Transfer Transactions';
    $subTitle = 'Transfer Transaction Report';
@endphp

@section('content')
<div class="card basic-data-table">
    <div class="card-header">
        <h5 class="card-title mb-0">Transfer Transactions</h5>
    </div>

    <div class="card-body">
        <!-- Responsive table container -->
        <div class="table-responsive">
            <table class="table bordered-table mb-0 align-middle text-nowrap">
                <thead>
                    <tr>
                        <th style="width: 5%;">S.L</th>
                        <th style="width: 10%;">User</th>
                        <th style="width: 25%;">From Address</th>
                        <th style="width: 25%;">To Address</th>
                        <th style="width: 15%;">Amount</th>
                        <th style="width: 20%;">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $index => $tx)
                    <tr>
                        <td>{{ $transactions->firstItem() + $index }}</td>
                        <td>{{ $tx['fullname'] ?? 'N/A' }}</td>
                        <td class="text-truncate" style="max-width: 250px;">{{ $tx['from_address'] ?? 'N/A' }}</td>
                        <td class="text-truncate" style="max-width: 250px;">{{ $tx['to_address'] ?? 'N/A' }}</td>
                        <td>{{ number_format($tx['amount'], 8) }}</td>
                        <td>{{ isset($tx['created_at']) ? \Carbon\Carbon::parse($tx['created_at'])->format('d/m/Y') : 'N/A' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No transfer transactions found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination and summary -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mt-3">
            <span>Showing {{ $transactions->count() }} of {{ $transactions->total() }} entries</span>
            <div>
                {{ $transactions->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
