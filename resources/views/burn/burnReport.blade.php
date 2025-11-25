@extends('layout.layout')
@php
    $title = 'Burn Token Report';
    $subTitle = 'Burn Token Report';
    $script = '<script>
                    let table = new DataTable("#dataTable");
               </script>';
@endphp

@section('content')
<div class="card basic-data-table">
    
    <div class="card-body">
        <div class="table-responsive">
            <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
                <thead>
                    <tr>
                    <th>S.L</th>
                    <th>User</th>
                    <th>Address</th>                    
                    <th>Amount</th>
                    <th>Date</th>                    
                </tr>
            </thead>
            <tbody>                
                @forelse($burnReport as $index => $tx)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $tx['fromusername'] ?? 'N/A' }}</td>
                    <td class="text-truncate" style="max-width: 250px;">{{ $tx['to_address'] ?? 'N/A' }}</td>
                    <td>{{ number_format($tx['amount'], 8) }}</td>
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
