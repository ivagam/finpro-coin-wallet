<!DOCTYPE html>
<html lang="en" data-theme="light">

<x-head/>

<body>

<section class="auth bg-base d-flex flex-wrap">
    <div class="auth-left d-lg-block d-none">
        <div class="d-flex align-items-center flex-column h-100 justify-content-center">
            <img src="{{ asset('assets/images/auth/auth-img.png') }}" alt="">
        </div>
    </div>

    <div class="auth-right py-32 px-24 d-flex flex-column justify-content-center">
        <div class="max-w-464-px mx-auto w-100">
            <div>
                <a href="{{ route('index') }}" class="mb-40 max-w-290-px">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="">
                </a>
                <h4 class="mb-12">Sign In to your Account</h4>
                <p class="mb-32 text-secondary-light text-lg">Welcome back! Please enter your details</p>
            </div>

            <!-- ✅ FIXED: added IDs for JS -->
            <form id="signin-form" action="{{ url('/signin') }}" method="POST">
                @csrf
                <div class="icon-field mb-16">
                    <span class="icon top-50 translate-middle-y">
                        <iconify-icon icon="mage:email"></iconify-icon>
                    </span>
                    <input 
                        type="email" 
                        name="email"
                        class="form-control h-56-px bg-neutral-50 radius-12" 
                        placeholder="Email" 
                        required
                    >
                </div>

                <div class="position-relative mb-20">
                    <div class="icon-field">
                        <span class="icon top-50 translate-middle-y">
                            <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                        </span>
                        <input 
                            type="password" 
                            name="password"
                            class="form-control h-56-px bg-neutral-50 radius-12" 
                            placeholder="Password" 
                            required
                        >
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 radius-12 mt-32">Sign In</button>
            </form>

            @if (session('error'))
                <div class="text-danger text-center mt-3">{{ session('error') }}</div>
            @endif

            <!-- ✅ Added message element -->
            <div id="login-message" class="mt-3 text-center text-danger fw-medium"></div>
        </div>
    </div>
</section>

@php
        $script = '<script>
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

<x-script />

</body>

</html>