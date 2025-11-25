@extends('layout.layout')
@php
    $title = 'Transaction History';
    $subTitle = 'Transaction History Report';
    $script = '<script>
                    let table = new DataTable("#dataTable");
               </script>';
@endphp

@section('content')
<div class="card basic-data-table">
    <div class="card-header">
        <h5 class="card-title mb-0">Transaction History</h5>
    </div>

    <div class="card-body">
        <!-- Responsive table container -->
        <div class="table-responsive">
            <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
                <thead>
                    <tr>
                        <th>S.L</th>
                        <th>From User</th>
                        <th>To User</th>
                        <th>From Address</th>
                        <th>To Address</th>
                        <th>Amount</th>
                        <th>Type</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $index => $tx)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $tx['fromusername'] ?? '-' }}</td>
                        <td>{{ $tx['tousername'] ?? '-' }}</td>
                        <td class="text-truncate" style="max-width: 250px;">{{ $tx['from_address'] ?? 'N/A' }}</td>
                        <td class="text-truncate" style="max-width: 250px;">{{ $tx['to_address'] ?? 'N/A' }}</td>
                        <td>{{ number_format($tx['amount'], 8) }}</td>
                        <td>{{ ucfirst($tx['txn_type']) }}</td>
                        <td>{{ isset($tx['created_at']) ? \Carbon\Carbon::parse($tx['created_at'])->format('d/m/Y') : 'N/A' }}</td>
                    </tr>
                    @empty                    
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection
