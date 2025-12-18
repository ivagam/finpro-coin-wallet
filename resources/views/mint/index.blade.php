@extends('layout.layout')

@php
    $title = 'Mint Token';
    $subTitle = 'Mint Token';
@endphp

@section('content')
<div class="row gy-4">
    <div class="col-lg-16">
        <div class="card mt-24">
            <div class="card-header border-bottom">
                <h6 class="text-xl mb-0">Mint Token</h6>
            </div>
            <div class="card-body p-24">
               
            <x-alert />

                <form action="{{ route('mint.store') }}" method="POST" class="d-flex flex-column gap-3">
                    @csrf

                    {{-- Address --}}
                    <div class="col-12">
                        <label for="address" class="form-label fw-bold">Token Address:</label>
                        <input type="text" name="address" id="address"
                               class="form-control @error('address') is-invalid @enderror"
                               placeholder="Enter Token Address"
                               value="{{ old('address') }}" required>
                        @error('address')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Amount --}}
                    <div class="col-12">
                        <label for="amount" class="form-label fw-bold">Token Value:</label>
                        <input type="number" name="amount" id="amount"
                               class="form-control @error('amount') is-invalid @enderror"
                               placeholder="Enter Token Value"
                               step="any"
                               value="{{ old('amount') }}" required>
                        @error('amount')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary mt-2">Submit</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection
