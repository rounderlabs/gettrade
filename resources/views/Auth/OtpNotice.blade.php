@extends('layouts.front')
@section('content')
<main>
    <!--==========================  Contact Section Start  ==========================-->
    <div class="login-section bg--black-two">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12 col-xl-10">
                    <div class="login-content-box mx-auto">

                        <form method="POST" action="{{ route('otp.verify') }}">
                            @csrf
                            <div class="row row-gap-4">
                                <div class="col-12">
                                    <label  class="form-control-label">Verify OTP</label>
                                    <input type="text" class="form-control" name="username" placeholder="6 Digit OTP*">
                                </div>
                                <div class="col-12">
                                    <div class="submit-button">
                                        <button type="submit" class="btn btn--base-two w-100">
                                            Verify Otp
                                            <i class="flaticon-arrow-upper-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <p class="text-center">Already have an account?
                                <a href="register.html" class="text--base">Register</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--==========================  Contact Section End  ==========================-->
</main>
@endsection
