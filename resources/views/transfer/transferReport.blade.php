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
            <form method="GET" action="{{ route('transfer.export') }}" target="_blank">
            <label>From: <input type="date" name="from" class="form-control" required></label>
            <label>To: <input type="date" name="to" class="form-control" required></label>            
            <button type="submit" class="btn btn-success">Export Excel</button>
        </form>
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
<script>
document.getElementById('exportForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const form = e.target;
    const from = form.from.value;
    const to = form.to.value;

    const url = '{{ route("transfer.export") }}' + '?from=' + from + '&to=' + to;

    try {
        const res = await fetch(url, { credentials: 'same-origin' });
        const data = await res.json();

        if (data.file_url) {
            // Open the Node.js Excel file in a new tab
            window.open(data.file_url, '_blank');
        } else {
            alert(data.error || 'Failed to generate Excel');
        }
    } catch (err) {
        console.error(err);
        alert('Error exporting Excel');
    }
});
</script>