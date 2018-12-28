@extends('layouts.auth') 
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-12">
            <div class="text-center mt-5 pt-3 font-weight-light text-white">
                <h1 class="login-header"> Register</h1>
            </div>
            <form method="POST" action="{{ route('register') }}" class="mt-5">
                @csrf

                <div class="form-group row">
                    <div class="col-md-6 col-12 mx-auto ">
                        <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }} auth-input" name="name" value="{{ old('name') }}" placeholder="Name"
                            required autofocus> 
                        @if ($errors->has('name'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span> 
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6 col-12 mx-auto">
                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }} auth-input" name="email" value="{{ old('email') }}"
                            required placeholder="Email"> 
                        @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span> 
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6 col-12 mx-auto">
                        <input id="number" type="number" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }} auth-input" name="phone" value="{{ old('phone') }}"
                            required placeholder="Phone Number"> 
                        @if ($errors->has('phone'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('phone') }}</strong>
                            </span> 
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                
                    <div class="col-md-6 col-12 mx-auto">
                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }} auth-input" name="password" placeholder="Password"
                            required> 
                        @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span> 
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                
                    <div class="col-md-6 col-12 mx-auto">
                        <input id="password-confirm" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }} auth-input" name="password_confirmation" placeholder="Confirm Password" required> 
                       
                    </div>
                </div>
              


                <div class="form-group row mt-5">
                    <div class="col-md-6 col-12 mx-auto">
                        <button type="submit" class="btn btn-primary form-control rounded auth-button">
                            {{ __('REGISTER') }}
                        </button>

                        {{-- <a class="btn btn-link home-link py-3 text-primary font-weight-bold px-4 form-control" href="{{ route('/') }} ">
                            Go Back Home
                        </a> --}}
                    </div>
                </div>
                <div class="form-group row ">
                    <div class="col-md-6 col-12 mx-auto mb-3 text-center">
                        <a class=" home-link mt-4 text-white px-4" href="/login">
                            Already Have account? Click here
                        </a>
                      
                    </div>
                </div>
               
            </form>
        </div>
    </div>
</div>
@endsection