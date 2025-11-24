<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en" data-theme="light">

<x-head />

<body>

    <section class="auth forgot-password-page bg-base d-flex flex-wrap">
        <div class="auth-left d-lg-block d-none">
            <div class="d-flex align-items-center flex-column h-100 justify-content-center">
                <img src="{{ asset('assets/images/fin-verify.png') }}" alt="">
            </div>
        </div>
        <div class="auth-right py-32 px-24 d-flex flex-column justify-content-center">
            
            <div class="max-w-464-px mx-auto w-100">
                <div>
                    <h4 class="mb-12">Email Verification</h4>
                    @if ($success == '')
                        <div class="text-danger mt-3">{{$error}}</div>
                        <p><a  href="{{ route('signin') }}" class="text-primary-600 fw-semibold">Click here to login</a></a>
                    @else
                        <div class="text-success mt-3">Your account has been verified.</div>
                        <p><a  href="{{ route('signin') }}" class="text-primary-600 fw-semibold">Click here to login</a></a>
                    @endif
                </div>
            
          
            
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog modal-dialog-centered">
            <div class="modal-content radius-16 bg-base">
                <div class="modal-body p-40 text-center">
                    <div class="mb-32">
                        <img src="{{ asset('assets/images/auth/envelop-icon.png') }}" alt="">
                    </div>
                    <h6 class="mb-12">Verify your Email</h6>
                    <p class="text-secondary-light text-sm mb-0">Thank you, check your email for instructions to reset your password</p>
                    <button type="button" class="btn btn-primary text-sm btn-sm px-12 py-16 w-100 radius-12 mt-32">Skip</button>
                    <div class="mt-32 text-sm">
                        <p class="mb-0">Don’t receive an email? <a  href="" class="text-primary-600 fw-semibold">Resend</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

<x-script/>

</body>

</html>