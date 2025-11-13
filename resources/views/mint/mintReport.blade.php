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
        <table class="table bordered-table mb-0">
            <thead>
                <tr>
                    <th scope="col">S.L</th>
                    <th scope="col">User</th>
                    <th scope="col">Recipient Address</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mintTransactions as $index => $tx)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $tx['fullname'] ?? 'N/A' }}</td>
                    <td>{{ $tx['to_address'] ?? 'N/A' }}</td>
                    <td>{{ number_format($tx['amount'], 8) }}</td>
                    <td>{{ \Carbon\Carbon::parse($tx['created_at'])->format('d M Y H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No mint transactions found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mt-24">
            <span>Showing {{ count($mintTransactions) }} entries</span>
        </div>
    </div>
</div>
@endsection
