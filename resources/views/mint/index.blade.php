@extends('layout.layout')

@php
    $title = 'Add Mint';
    $subTitle = 'Add Mint';
@endphp

@section('content')
<div class="row gy-4">
    <div class="col-lg-16">
        <div class="card mt-24">
            <div class="card-header border-bottom">
                <h6 class="text-xl mb-0">Add Mint</h6>
            </div>
            <div class="card-body p-24">
                {{-- Show flash messages --}}
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form action="{{ route('mint.store') }}" method="POST" class="d-flex flex-column gap-20">
                    @csrf
                    <div>
                        <label class="form-label fw-bold text-neutral-900" for="address">Address:</label>
                        <input type="text" name="address" id="address"
                               class="form-control border border-neutral-200 radius-8"
                               placeholder="Enter Address" required>
                    </div>

                    <div>
                        <label class="form-label fw-bold text-neutral-900" for="amount">Amount:</label>
                        <input type="number" name="amount" id="amount"
                               class="form-control border border-neutral-200 radius-8"
                               placeholder="Enter Amount" required>
                    </div>

                    <button type="submit" class="btn btn-primary-600 radius-8">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
