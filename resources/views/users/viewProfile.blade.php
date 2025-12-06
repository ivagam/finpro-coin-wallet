@extends('layout.layout')
@php
    $title='View Profile';
    $subTitle = 'View Profile';
    $script ='<script>
                    // ======================== Upload Image Start =====================
                    function readURL(input) {
                        if (input.files && input.files[0]) {
                            var reader = new FileReader();
                            reader.onload = function(e) {
                                $("#imagePreview").css("background-image", "url(" + e.target.result + ")");
                                $("#imagePreview").hide();
                                $("#imagePreview").fadeIn(650);
                            }
                            reader.readAsDataURL(input.files[0]);
                        }
                    }
                    $("#imageUpload").change(function() {
                        readURL(this);
                    });
                    // ======================== Upload Image End =====================

                    // ================== Password Show Hide Js Start ==========
                    function initializePasswordToggle(toggleSelector) {
                        $(toggleSelector).on("click", function() {
                            $(this).toggleClass("ri-eye-off-line");
                            var input = $($(this).attr("data-toggle"));
                            if (input.attr("type") === "password") {
                                input.attr("type", "text");
                            } else {
                                input.attr("type", "password");
                            }
                        });
                    }
                    // Call the function
                    initializePasswordToggle(".toggle-password");
                    // ========================= Password Show Hide Js End ===========================
            </script>';
@endphp

@section('content')

            <div class="row gy-4">
                <div class="col-lg-4">
                    <div class="user-grid-card position-relative border radius-16 overflow-hidden bg-base h-100">
                        <img src="{{ asset('assets/images/background-banner.jpg') }}" alt="" class="w-100 object-fit-cover">
                        <div class="pb-24 ms-16 mb-24 me-16  mt--100">
                            <div class="text-center border border-top-0 border-start-0 border-end-0">
                                 <img
                                                src="{{ $user['profile_image'] ? $apiBase.'/uploads/'. $user['profile_image'] : asset('assets/images/profile.png') }}"
                                                alt="Profile Image"
                                                class="border br-white border-width-2-px w-200-px h-200-px rounded-circle object-fit-cover"
                                            />
                                <h6 class="mb-0 mt-16">{{$user['fullname']}}</h6>
                                <span class="text-secondary-light mb-16">{{$user['email']}}</span>
                                <h6 class="text-lg text-primary-light fw-semibold mb-2"> Client Id: {{ session('user.id') }}</h6>
                                
                            </div>
                            <div class="mt-24">
                                <h6 class="text-xl mb-16">Personal Info</h6>
                                <ul>
                                    <li class="d-flex align-items-center gap-1 mb-12">
                                        <span class="w-30 text-md fw-semibold text-primary-light">Full Name</span>
                                        <span class="w-70 text-secondary-light fw-medium">: {{$user['fullname']}}</span>
                                    </li>
                                    <li class="d-flex align-items-center gap-1 mb-12">
                                        <span class="w-30 text-md fw-semibold text-primary-light"> Email</span>
                                        <span class="w-70 text-secondary-light fw-medium">: {{$user['email']}}</span>
                                    </li>
                                    <li class="d-flex align-items-center gap-1 mb-12">
                                        <span class="w-30 text-md fw-semibold text-primary-light"> Phone Number</span>
                                        <span class="w-70 text-secondary-light fw-medium">: {{$user['phone']}}</span>
                                    </li>
                                    @if(!$user['is_admin'])

                                    <li class="d-flex align-items-center gap-1 mb-12">
                                        <span class="w-30 text-md fw-semibold text-primary-light"> Pancard No</span>
                                        <span class="w-70 text-secondary-light fw-medium">: {{$user['pancard_no']}}</span>
                                    </li>
                                   
                                    <li class="d-flex align-items-center gap-1 mb-12">
                                        <span class="w-30 text-md fw-semibold text-primary-light">Pancart Image</span>
                                        <span class="w-70 text-secondary-light fw-medium">
                                            :
                                            <img
                                                src="{{ $user['pancard_image'] ? $apiBase.'/uploads/'. $user['pancard_image'] : asset('assets/images/no-image.jpg') }}"
                                                alt="Profile Image"
                                                style="width: 60px; height: 60px;  object-fit: cover;"
                                            />
                                        </span>
                                    </li>

                                   
                                    <li class="d-flex align-items-center gap-1 mb-12">
                                        <span class="w-30 text-md fw-semibold text-primary-light">Aadhar Front</span>
                                        <span class="w-70 text-secondary-light fw-medium">
                                            :
                                            <img
                                                src="{{ $user['aadhar_front'] ? $apiBase.'/uploads/'. $user['aadhar_front'] : asset('assets/images/no-image.jpg') }}"
                                                alt="Profile Image"
                                                style="width: 60px; height: 60px;  object-fit: cover;"
                                            />
                                        </span>
                                    </li>

                                   
                                    <li class="d-flex align-items-center gap-1 mb-12">
                                        <span class="w-30 text-md fw-semibold text-primary-light">Aadhar Back</span>
                                        <span class="w-70 text-secondary-light fw-medium">
                                            :
                                            <img
                                                src="{{ $user['aadhar_back'] ? $apiBase.'/uploads/'. $user['aadhar_back'] : asset('assets/images/no-image.jpg') }}"
                                                alt="Profile Image"
                                                style="width: 60px; height: 60px;  object-fit: cover;"
                                            />
                                        </span>
                                    </li>

                                    <li class="d-flex align-items-center gap-1">
                                        <span class="w-30 text-md fw-semibold text-primary-light"> KYC Status</span>
                                        <span class="w-70 text-secondary-light fw-medium">: {{$user['kyc_status']}}.</span>
                                    </li>
                                    <li class="d-flex align-items-center gap-1">
                                        <span class="w-30 text-md fw-semibold text-primary-light"> Account Status</span>
                                        <span class="w-70 text-secondary-light fw-medium">: {{ $user['is_active'] == 1 ? 'Active' : 'Inactive' }}</span>
                                    </li>
                                    @endif
                                </ul>
                                @if(!$user['is_admin'])

                                    <h6 class="text-xl mb-16">Bank Account Info</h6>
                                    <ul>
                                        <li class="d-flex align-items-center gap-1 mb-12">
                                            <span class="w-30 text-md fw-semibold text-primary-light">Account Name</span>
                                            <span class="w-70 text-secondary-light fw-medium">: {{$user['account_holder_name']}}</span>
                                        </li>
                                        <li class="d-flex align-items-center gap-1 mb-12">
                                            <span class="w-30 text-md fw-semibold text-primary-light">Account No</span>
                                            <span class="w-70 text-secondary-light fw-medium">: {{$user['account_no']}}</span>
                                        </li>
                                        <li class="d-flex align-items-center gap-1 mb-12">
                                            <span class="w-30 text-md fw-semibold text-primary-light">IFSC Code</span>
                                            <span class="w-70 text-secondary-light fw-medium">: {{$user['ifsc']}}</span>
                                        </li>
                                        <li class="d-flex align-items-center gap-1 mb-12">
                                            <span class="w-30 text-md fw-semibold text-primary-light">Bank Name</span>
                                            <span class="w-70 text-secondary-light fw-medium">: {{$user['bank_name']}}</span>
                                        </li>
                                        <li class="d-flex align-items-center gap-1 mb-12">
                                            <span class="w-30 text-md fw-semibold text-primary-light">Branch Name</span>
                                            <span class="w-70 text-secondary-light fw-medium">: {{$user['branch_name']}}</span>
                                        </li>
                                    </ul>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                     <div style="margin-bottom:15px" class="p-16 bg-neutral-50 radius-8 border-start-width-3-px border-neutral-600 border-top-0 border-end-0 border-bottom-0">
                                        <h6 class="text-primary-light text-md mb-8">Token Address</h6>
                                        <span class="text-primary-light mb-0">{{ session('user.address') }}</span>
                                    </div>

                    <div class="card h-100">
                           
                    <x-alert />
                    
                        <div class="card-body p-24">
                            <ul class="nav border-gradient-tab nav-pills mb-20 d-inline-flex" id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link d-flex align-items-center px-24 active" id="pills-edit-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-edit-profile" type="button" role="tab" aria-controls="pills-edit-profile" aria-selected="true">
                                        Edit Profile
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link d-flex align-items-center px-24" id="pills-change-passwork-tab" data-bs-toggle="pill" data-bs-target="#pills-change-passwork" type="button" role="tab" aria-controls="pills-change-passwork" aria-selected="false" tabindex="-1">
                                        Change Password
                                    </button>
                                </li>
                                @if(!$user['is_admin'])

                                <li class="nav-item" role="presentation">
                                    <button class="nav-link d-flex align-items-center px-24" id="pills-notification-tab" data-bs-toggle="pill" data-bs-target="#pills-notification" type="button" role="tab" aria-controls="pills-notification" aria-selected="false" tabindex="-1">
                                        Bank Account
                                    </button>
                                </li>
                                @endif
                            </ul>

                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-edit-profile" role="tabpanel" aria-labelledby="pills-edit-profile-tab" tabindex="0">
                                                                     
                                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                                        @csrf

                                        <input type="hidden" name="user_id" value="{{ $id ?? session('user_id') }}">
                                        <div class="row">
                                             <h6 class="text-md text-primary-light mb-16">Profile Image</h6>
                                    <!-- Upload Image Start -->
                                    <div class="mb-24 mt-16">
                                        <div class="avatar-upload">
                                            <div class="avatar-edit position-absolute bottom-0 end-0 me-24 mt-16 z-1 cursor-pointer">
                                                <input type='file' id="imageUpload" name="profile_image" accept=".png, .jpg, .jpeg" hidden>
                                                <label for="imageUpload" class="w-32-px h-32-px d-flex justify-content-center align-items-center bg-primary-50 text-primary-600 border border-primary-600 bg-hover-primary-100 text-lg rounded-circle">
                                                    <iconify-icon icon="solar:camera-outline" class="icon"></iconify-icon>
                                                </label>
                                            </div>
                                            <div class="avatar-preview">
                                                <div id="imagePreview" style='background-image: url("{{ $user["profile_image"] ? $apiBase."/uploads/".$user["profile_image"] : asset("assets/images/profile.png") }}")'>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Upload Image End -->
                                            <div class="col-sm-6">
                                                <div class="mb-20">
                                                    <label for="name" class="form-label fw-semibold text-primary-light text-sm mb-8">Full Name <span class="text-danger-600">*</span></label>
                                                    <input type="text" name="fullname" class="form-control radius-8" value="{{$user['fullname']}}" id="name" placeholder="Enter Full Name">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-20">
                                                    <label for="email" class="form-label fw-semibold text-primary-light text-sm mb-8">Email <span class="text-danger-600">*</span></label>
                                                    <input type="email" name="email" class="form-control radius-8" id="email" value="{{$user['email']}}" placeholder="Enter email address">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-20">
                                                    <label for="number" class="form-label fw-semibold text-primary-light text-sm mb-8">Phone</label>
                                                    <input type="number" class="form-control radius-8" name="phone"  value="{{$user['phone']}}" placeholder="Enter phone number">
                                                </div>
                                            </div>
                                    @if(!$user['is_admin'])
                                            <div class="col-sm-6">
                                                <div class="mb-20">
                                                    <label for="number" class="form-label fw-semibold text-primary-light text-sm mb-8">Pancard No</label>
                                                    <input type="text" class="form-control radius-8" name="pancard_no"  value="{{$user['pancard_no']}}" placeholder="Enter phone number">
                                                </div>
                                            </div>
                                    
                                        <div class="col-sm-6">
                                            <div class="mb-20">
                                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">Aadhar Front</label>

                                                <div class="avatar-upload">
                                                    <div class="avatar-edit position-absolute bottom-0 end-0 me-24 mt-16 z-1 cursor-pointer">
                                                        <input type="file" id="aadharFrontUpload" name="aadhar_front" accept=".png, .jpg, .jpeg" hidden>
                                                        <label for="aadharFrontUpload" class="w-32-px h-32-px d-flex justify-content-center align-items-center bg-primary-50 text-primary-600 border border-primary-600 bg-hover-primary-100 text-lg rounded-circle">
                                                            <iconify-icon icon="solar:camera-outline"></iconify-icon>
                                                        </label>
                                                    </div>
                                                    <div class="avatar-preview" style="border-radius:0px">
                                                        <div id="aadharFrontPreview" style='background-image: url("{{ $user["aadhar_front"] ? $apiBase."/uploads/".$user["aadhar_front"] : asset("assets/images/no-image.jpg") }}");border-radius:0px'></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="mb-20">
                                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">Aadhar Back</label>

                                                <div class="avatar-upload">
                                                    <div class="avatar-edit position-absolute bottom-0 end-0 me-24 mt-16 z-1 cursor-pointer">
                                                        <input type="file" id="aadharBackUpload" name="aadhar_back" accept=".png, .jpg, .jpeg" hidden>
                                                        <label for="aadharBackUpload" class="w-32-px h-32-px d-flex justify-content-center align-items-center bg-primary-50 text-primary-600 border border-primary-600 bg-hover-primary-100 text-lg rounded-circle">
                                                            <iconify-icon icon="solar:camera-outline"></iconify-icon>
                                                        </label>
                                                    </div>
                                                    <div class="avatar-preview" style="border-radius:0px">
                                                        <div id="aadharBackPreview" style='background-image: url("{{ $user["aadhar_back"] ? $apiBase."/uploads/".$user["aadhar_back"] : asset("assets/images/no-image.jpg") }}");border-radius:0px'></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        
                                        <div class="col-sm-6">
                                            <div class="mb-20">
                                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">Pan Card</label>

                                                <div class="avatar-upload">
                                                    <div class="avatar-edit position-absolute bottom-0 end-0 me-24 mt-16 z-1 cursor-pointer">
                                                        <input type="file" id="panCardUpload" name="pancard_image" accept=".png, .jpg, .jpeg" hidden>
                                                        <label for="panCardUpload" class="w-32-px h-32-px d-flex justify-content-center align-items-center bg-primary-50 text-primary-600 border border-primary-600 bg-hover-primary-100 text-lg rounded-circle">
                                                            <iconify-icon icon="solar:camera-outline"></iconify-icon>
                                                        </label>
                                                    </div>
                                                    <div class="avatar-preview" style="border-radius:0px">
                                                        <div id="panCardPreview" style='background-image: url("{{ $user["pancard_image"] ? $apiBase."/uploads/".$user["pancard_image"] : asset("assets/images/no-image.jpg") }}");border-radius:0px'></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                            
                                        </div>
                                        <div class="d-flex align-items-center justify-content-center gap-3">
                                           <a href="{{ route('dashboard.index') }}">  <button type="button" class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-56 py-11 radius-8">
                                                Cancel
                                            </button> </a>
                                            <button type="submit" class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8">
                                                Save
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <div class="tab-pane fade" id="pills-change-passwork" role="tabpanel" aria-labelledby="pills-change-passwork-tab" tabindex="0">
                                 
                                    <form action="{{ route('profile.changePassword') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $id ?? session('user_id') }}">
                                        <div class="mb-20">
                                            <label for="your-password" class="form-label fw-semibold text-primary-light text-sm mb-8">Current Password <span class="text-danger-600">*</span></label>
                                            <div class="position-relative">
                                                <input type="password" class="form-control radius-8" name="current_pass" id="current_pass" placeholder="Enter Current Password*">
                                                <span class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light" data-toggle="#your-password"></span>
                                            </div>
                                        </div>
                                        <div class="mb-20">
                                            <label for="your-password" class="form-label fw-semibold text-primary-light text-sm mb-8">New Password <span class="text-danger-600">*</span></label>
                                            <div class="position-relative">
                                                <input type="password" class="form-control radius-8" name="new_pass" id="new_pass" placeholder="Enter New Password*">
                                                <span class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light" data-toggle="#your-password"></span>
                                            </div>
                                        </div>
                                        <div class="mb-20">
                                            <label for="confirm-password" class="form-label fw-semibold text-primary-light text-sm mb-8">Confirmed Password <span class="text-danger-600">*</span></label>
                                            <div class="position-relative">
                                                <input type="password" class="form-control radius-8" name="confirm_pass" id="confirm_pass" placeholder="Confirm Password*">
                                                <span class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light" data-toggle="#confirm-password"></span>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-center gap-3">
                                            <a href="{{ route('dashboard.index') }}">  <button type="button" class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-56 py-11 radius-8">
                                                    Cancel
                                                </button> </a>
                                                <button type="submit" class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8">
                                                    Save
                                                </button>
                                            </div>
                                    </form>
                                </div>                                    
                            </div>

                                <div class="tab-pane fade" id="pills-notification" role="tabpanel" aria-labelledby="pills-notification-tab" tabindex="0">
                                     <form action="{{ route('profile.updateBankAccount') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $id ?? session('user_id') }}">
                                        <div class="row">
                                            <input type="hidden" name="bank_acc_id" value="{{$user['bank_acc_id']}}"/>
                                            <div class="col-sm-6">
                                                <div class="mb-20">
                                                    <label for="name" class="form-label fw-semibold text-primary-light text-sm mb-8">Account Holder Name <span class="text-danger-600">*</span></label>
                                                    <input type="text" name="account_holder_name" class="form-control radius-8" value="{{$user['account_holder_name']}}" id="name" placeholder="Enter account holder Name">
                                                </div>
                                            </div>
                                            
                                            <div class="col-sm-6">
                                                <div class="mb-20">
                                                    <label for="number" class="form-label fw-semibold text-primary-light text-sm mb-8">Account No <span class="text-danger-600">*</span></label>
                                                    <input type="number" class="form-control radius-8" name="account_no"  value="{{$user['account_no']}}" placeholder="Enter account number">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-20">
                                                    <label for="number" class="form-label fw-semibold text-primary-light text-sm mb-8">IFSC Code <span class="text-danger-600">*</span></label>
                                                    <input type="text" class="form-control radius-8" name="ifsc"  value="{{$user['ifsc']}}" placeholder="Enter ifsc code">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-20">
                                                    <label for="number" class="form-label fw-semibold text-primary-light text-sm mb-8">Bank Name <span class="text-danger-600">*</span></label>
                                                    <input type="text" class="form-control radius-8" name="bank_name"  value="{{$user['bank_name']}}" placeholder="Enter bank name">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-20">
                                                    <label for="number" class="form-label fw-semibold text-primary-light text-sm mb-8">Branch Name</label>
                                                    <input type="text" class="form-control radius-8" name="branch_name"  value="{{$user['branch_name']}}" placeholder="Enter branch name">
                                                </div>
                                            </div>
                                    
                                    
                                        <div class="col-sm-6">
                                            <div class="mb-20">
                                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">Bank Statement or Cancel Cheque</label>

                                                <div class="avatar-upload">
                                                    <div class="avatar-edit position-absolute bottom-0 end-0 me-24 mt-16 z-1 cursor-pointer">
                                                        <input type="file" id="aadharFrontUpload" name="attachment" accept=".png, .jpg, .jpeg" hidden>
                                                        <label for="aadharFrontUpload" class="w-32-px h-32-px d-flex justify-content-center align-items-center bg-primary-50 text-primary-600 border border-primary-600 bg-hover-primary-100 text-lg rounded-circle">
                                                            <iconify-icon icon="solar:camera-outline"></iconify-icon>
                                                        </label>
                                                    </div>
                                                    <div class="avatar-preview" style="border-radius:0px">
                                                        <div id="aadharFrontPreview" style='background-image: url("{{ $user["aadhar_front"] ? $apiBase."/uploads/".$user["aadhar_front"] : asset("assets/images/no-image.jpg") }}");border-radius:0px'></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        
                                        
                                            
                                        </div>
                                        <div class="d-flex align-items-center justify-content-center gap-3">
                                           <a href="{{ route('dashboard.index') }}">  <button type="button" class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-56 py-11 radius-8">
                                                Cancel
                                            </button> </a>
                                            <button type="submit" class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8">
                                                Save
                                            </button>
                                        </div>
                                    </form>
                                </div>
                        </div>
                    </div>
                </div>
            </div>

@endsection

<script>
document.addEventListener("DOMContentLoaded", function() {
    function previewImage(inputId, previewId) {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);

        if (!input || !preview) return; // safety check

        input.addEventListener('change', function () {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.style.backgroundImage = `url(${e.target.result})`;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    previewImage("aadharFrontUpload", "aadharFrontPreview");
    previewImage("aadharBackUpload", "aadharBackPreview");
    previewImage("panCardUpload", "panCardPreview");
});
</script>
