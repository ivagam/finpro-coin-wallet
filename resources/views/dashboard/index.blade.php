@extends('layout.layout')
@php
    $title='Dashboard';
    $subTitle = 'Cryptocracy';
    $script = ' <script src="' . asset('assets/js/homeFourChart.js') . '"></script>';
@endphp

@section('content')

            <!-- Crypto Home Widgets Start -->
            <div class="row row-cols-xxxl-5 row-cols-lg-3 row-cols-sm-2 row-cols-1 gy-4">

               
                        @if($is_admin == 1)
                         <div class="col">
                    <div class="card shadow-none border bg-gradient-end-3">
                        <div class="card-body p-20">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <img src="{{ asset('assets/images/finxcore-favi.png') }}" alt="" class="w-40-px h-40-px rounded-circle flex-shrink-0">
                                <div class="flex-grow-1">
                                    <h6 class="text-xl mb-1">Finxcore</h6>
                                    <p class="fw-medium text-secondary-light mb-0">FXC</p>
                                </div>
                            </div>
                            <div class="mt-3 d-flex flex-wrap justify-content-between align-items-center gap-1">
                                <div class="">
                                    <h6 class="mb-8">{{ number_format($data['total_balance'], 2) }} </h6>
                                </div>
                            </div>
                        </div>
                          </div>
                </div> 
                <div class="col">
                    <div class="card shadow-none border bg-gradient-end-3">
                        <div class="card-body p-20">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <img src="{{ asset('assets/images/finxcore-favi.png') }}" alt="" class="w-40-px h-40-px rounded-circle flex-shrink-0">
                                <div class="flex-grow-1">
                                    <h6 class="text-xl mb-1">Admin Wallet Balance</h6>
                                    <p class="fw-medium text-secondary-light mb-0">FXC</p>
                                </div>
                            </div>
                            <div class="mt-3 d-flex flex-wrap justify-content-between align-items-center gap-1">
                                <div class="">
                                    <h6 class="mb-8">{{ number_format($data['balance'], 2) }} </h6>
                                </div>
                            </div>
                        </div>
                          </div>
                </div> 
                <div class="col">
                    <div class="card shadow-none border bg-gradient-end-3">
                        <div class="card-body p-20">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <img src="{{ asset('assets/images/group.jpg') }}" alt="" class="w-40-px h-40-px rounded-circle flex-shrink-0">
                                <div class="flex-grow-1">
                                    <h6 class="text-xl mb-1">Total User</h6>
                                </div>
                            </div>
                            <div class="mt-3 d-flex flex-wrap justify-content-between align-items-center gap-1">
                                <div class="">
                                    <h6 class="mb-8">{{ $data['total_users'] }} </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>   
                        @else
                         <div class="col">
                    <div class="card shadow-none border bg-gradient-end-3">
                        <div class="card-body p-20">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <img src="{{ asset('assets/images/finxcore-favi.png') }}" alt="" class="w-40-px h-40-px rounded-circle flex-shrink-0">
                                <div class="flex-grow-1">
                                    <h6 class="text-xl mb-1">Finxcore</h6>
                                    <p class="fw-medium text-secondary-light mb-0">FXC</p>
                                </div>
                            </div>
                            <div class="mt-3 d-flex flex-wrap justify-content-between align-items-center gap-1">
                                <div class="">
                                    <h6 class="mb-8">{{ number_format($data['balance'], 2) }} </h6>
                                </div>
                            </div>
                        </div>
                          </div>
                </div> 
                <div class="col">
                    <div class="card shadow-none border bg-gradient-end-3">
                        <div class="card-body p-20">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <img src="{{ asset('assets/images/dollar.png') }}" alt="" class="w-40-px h-40-px rounded-circle flex-shrink-0">
                                <div class="flex-grow-1">
                                    <h6 class="text-xl mb-1">Currency Value</h6>
                                    <p class="fw-medium text-secondary-light mb-0">USD</p>
                                </div>
                            </div>
                            <div class="mt-3 d-flex flex-wrap justify-content-between align-items-center gap-1">
                                <div class="">
                                    <h6 class="mb-8">${{ number_format($data['balance'], 2) }} </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>   
                        @endif
                              

                

            </div>
            <!-- Crypto Home Widgets End -->

            <div class="row gy-4 mt-4">

                <!-- Crypto Home Widgets Start -->
                <div class="col-xxl-8">
                    <div class="row gy-4">
                        <div class="col-xxl-12">
                            <div class="card h-100">
                                <div class="card-body p-24">
                                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between mb-20">
                                        <h6 class="mb-2 fw-bold text-lg mb-0">Recent Transaction</h6>
                                        <a href="{{ route('transferHistory') }}" class="text-primary-600 hover-text-primary d-flex align-items-center gap-1">
                                            View All
                                            <iconify-icon icon="solar:alt-arrow-right-linear" class="icon"></iconify-icon>
                                        </a>
                                    </div>
                                    <div class="table-responsive scroll-sm">
                                        @if(!empty($data['recentTransactions']))
                                        <table class="table bordered-table mb-0 xsm-table">
                                            <thead>
                                                    <tr>
                                                        <th>Ast</th>
                                                        <th>User</th>
                                                        <th>Amount</th>
                                                        <th>Type</th>
                                                        <th>Status</th>
                                                        <th>Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($data['recentTransactions'] as $tx)
                                                        <tr>
                                                            <td>
                                                            @if($tx['id'] == $cuser)  
 <div class="d-flex align-items-center gap-2">
                                                            <span class="text-success-main bg-success-focus w-32-px h-32-px d-flex align-items-center justify-content-center rounded-circle text-xl">
                                                                <iconify-icon icon="tabler:arrow-up-right" class="icon"></iconify-icon>
                                                            </span>
                                                            <span class="fw-medium">FXC</span>
                                                        </div>
                                                            @else
 <div class="d-flex align-items-center gap-2">
                                                            <span class="text-danger-main bg-danger-focus w-32-px h-32-px d-flex align-items-center justify-content-center rounded-circle text-xl">
                                                                <iconify-icon icon="tabler:arrow-down-left" class="icon"></iconify-icon>
                                                            </span>
                                                            <span class="fw-medium">FXC</span>
                                                        </div>
                                                            @endif  
                                                            </td>
                                                            <td>{{ $tx['fromusername'] ?? '-' }}</td>
                                                            <td>{{ $tx['amount'] }}</td>
                                                            <td>{{ ucfirst($tx['txn_type']) }}</td>
                                                            <td>
                                                                @if($tx['status'] == 'confirmed')
                                                                    <span class="bg-success-focus text-success-main px-16 py-4 radius-4 fw-medium text-sm">Confirmed</span>

                                                                @else
                                                        <span class="bg-danger-focus text-danger-main px-16 py-4 radius-4 fw-medium text-sm">Pending</span>
                                                                @endif
                                                            </td>
                                                            <td><span class="text-secondary-light text-sm">{{ \Carbon\Carbon::parse($tx['created_at'])->format('Y-m-d H:i') }}</span></td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            <p>No transactions found.</p>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Crypto Home Widgets End -->

                <div class="col-xxl-4">
                    <div class="row gy-4">
                        @if($is_admin == 1)
                         <div class="col-xxl-12 col-lg-6">
                            <div class="card h-100">
                                <div class="card-body p-24">
                                    <span class="mb-4 text-sm text-secondary-light">Admin Wallet Balance</span>
                                    <h6 class="mb-4">
                                        {{ number_format($data['balance'], 2) }} 
                                    </h6>

                                    <ul class="nav nav-pills pill-tab mb-24 mt-28 border input-form-light p-1 radius-8 bg-neutral-50" id="pills-tab" role="tablist">
                                        <li class="nav-item w-50" role="presentation">
                                            <button class="nav-link px-12 py-10 text-md w-100 text-center radius-8 active" id="pills-Buy-tab" data-bs-toggle="pill" data-bs-target="#pills-Buy" type="button" role="tab" aria-controls="pills-Buy" aria-selected="true">Send</button>
                                        </li>
                                        <li class="nav-item w-50" role="presentation">
                                            <button class="nav-link px-12 py-10 text-md w-100 text-center radius-8" id="pills-Sell-tab" data-bs-toggle="pill" data-bs-target="#pills-Sell" type="button" role="tab" aria-controls="pills-Sell" aria-selected="false">Burn</button>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="pills-tabContent">
                                        <div class="tab-pane fade show active" id="pills-Buy" role="tabpanel" aria-labelledby="pills-Buy-tab" tabindex="0">
                                            
                                            <div class="mb-20">
                                                <label for="tradeValue" class="fw-semibold mb-8 text-primary-light">Trade Value</label>
                                                <div class="input-group input-group-lg border input-form-light radius-8">
                                                    <input type="text" class="form-control bg-base border-0 radius-8" id="tradeValue" placeholder="Trade Value">
                                                    <div class="input-group-text bg-neutral-50 border-0 fw-normal text-md ps-1 pe-1">
                                                        <select class="form-select form-select-sm w-auto bg-transparent fw-bolder border-0 text-secondary-light">
                                                            <option class="bg-base">FXC</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-20">
                                                <label class="fw-semibold mb-8 text-primary-light">Trade Address</label>
                                                <textarea class="form-control bg-base h-80-px radius-8" placeholder="Enter Address"></textarea>
                                            </div>
                                           
                                            <a  href="" class="btn btn-primary text-sm btn-sm px-8 py-12 w-100 radius-8"> Transfer Now</a>
                                        </div>
                                        <div class="tab-pane fade" id="pills-Sell" role="tabpanel" aria-labelledby="pills-Sell-tab" tabindex="0">
                                            <div class="mb-20">
                                                <label for="estimatedValueSell" class="fw-semibold mb-8 text-primary-light">Trade Value</label>
                                                <div class="input-group input-group-lg border input-form-light radius-8">
                                                    <input type="text" class="form-control border-0 radius-8" id="estimatedValueSell" placeholder="Estimated Value">
                                                    <div class="input-group-text bg-neutral-50 border-0 fw-normal text-md ps-1 pe-1">
                                                        <select class="form-select form-select-sm w-auto bg-transparent fw-bolder border-0 text-secondary-light">
                                                            <option>FXC</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                              <div class="mb-20">
                                                <label class="fw-semibold mb-8 text-primary-light">Trade Address</label>
                                                <textarea class="form-control bg-base h-80-px radius-8" placeholder="Enter Address"></textarea>
                                            </div>
                                           
                                            
                                           
                                            <a  href="" class="btn btn-primary text-sm btn-sm px-8 py-12 w-100 radius-8">Submit</a>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        @else
                        <div class="col-xxl-12 col-lg-6">
                            <div class="card h-100">
                                <div class="card-body p-24">
                                    <span class="mb-4 text-sm text-secondary-light">Total Balance</span>
                                    <h6 class="mb-4">
                                        {{ number_format($data['balance'], 2) }} 
                                    </h6>

                                    <ul class="nav nav-pills pill-tab mb-24 mt-28 border input-form-light p-1 radius-8 bg-neutral-50" id="pills-tab" role="tablist">
                                        <li class="nav-item w-50" role="presentation">
                                            <button class="nav-link px-12 py-10 text-md w-100 text-center radius-8 active" id="pills-Buy-tab" data-bs-toggle="pill" data-bs-target="#pills-Buy" type="button" role="tab" aria-controls="pills-Buy" aria-selected="true">Send</button>
                                        </li>
                                        <li class="nav-item w-50" role="presentation">
                                            <button class="nav-link px-12 py-10 text-md w-100 text-center radius-8" id="pills-Sell-tab" data-bs-toggle="pill" data-bs-target="#pills-Sell" type="button" role="tab" aria-controls="pills-Sell" aria-selected="false">Widthraw</button>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="pills-tabContent">
                                        <div class="tab-pane fade show active" id="pills-Buy" role="tabpanel" aria-labelledby="pills-Buy-tab" tabindex="0">
                                            
                                            <div class="mb-20">
                                                <label for="tradeValue" class="fw-semibold mb-8 text-primary-light">Trade Value</label>
                                                <div class="input-group input-group-lg border input-form-light radius-8">
                                                    <input type="text" class="form-control bg-base border-0 radius-8" id="tradeValue" placeholder="Trade Value">
                                                    <div class="input-group-text bg-neutral-50 border-0 fw-normal text-md ps-1 pe-1">
                                                        <select class="form-select form-select-sm w-auto bg-transparent fw-bolder border-0 text-secondary-light">
                                                            <option class="bg-base">FXC</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-20">
                                                <label class="fw-semibold mb-8 text-primary-light">Trade Value</label>
                                                <textarea class="form-control bg-base h-80-px radius-8" placeholder="Enter Address"></textarea>
                                            </div>
                                           
                                            <a  href="" class="btn btn-primary text-sm btn-sm px-8 py-12 w-100 radius-8"> Transfer Now</a>
                                        </div>
                                        <div class="tab-pane fade" id="pills-Sell" role="tabpanel" aria-labelledby="pills-Sell-tab" tabindex="0">
                                            <div class="mb-20">
                                                <label for="estimatedValueSell" class="fw-semibold mb-8 text-primary-light">Trade Value</label>
                                                <div class="input-group input-group-lg border input-form-light radius-8">
                                                    <input type="text" class="form-control border-0 radius-8" id="estimatedValueSell" placeholder="Estimated Value">
                                                    <div class="input-group-text bg-neutral-50 border-0 fw-normal text-md ps-1 pe-1">
                                                        <select class="form-select form-select-sm w-auto bg-transparent fw-bolder border-0 text-secondary-light">
                                                            <option>FXC</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                           
                                            
                                           
                                            <a  href="" class="btn btn-primary text-sm btn-sm px-8 py-12 w-100 radius-8">Submit</a>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        @endif
                        
                    </div>
                </div>

            </div>
            
@endsection