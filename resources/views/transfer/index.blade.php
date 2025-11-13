@extends('layout.layout')

@php
    $title = 'Add Transfer';
    $subTitle = 'Add Transfer';
@endphp

@section('content')
<div class="row gy-4">
    <div class="col-lg-16">
        <div class="card mt-24">
            <div class="card-header border-bottom">
                <h6 class="text-xl mb-0">Add Transfer</h6>
            </div>
            <div class="card-body p-24">

                {{-- Flash messages --}}
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form action="{{ route('transfer.store') }}" method="POST" class="d-flex flex-column gap-3">
                    @csrf

                    {{-- Address --}}
                    <div>
                        <label for="address" class="form-label fw-bold">Address:</label>
                        <input type="text" name="address" id="address"
                               class="form-control @error('address') is-invalid @enderror"
                               placeholder="Enter Address"
                               value="{{ old('address') }}">
                        @error('address')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Amount --}}
                    <div>
                        <label for="amount" class="form-label fw-bold">Amount:</label>
                        <input type="number" name="amount" id="amount"
                               class="form-control @error('amount') is-invalid @enderror"
                               placeholder="Enter Amount"
                               value="{{ old('amount') }}">
                        @error('amount')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary mt-2">Submit</button>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection
