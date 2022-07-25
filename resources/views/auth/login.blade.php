@extends('layouts.app_empty')
@push('body')
<div class="home-bg"></div>
<div class="align-block-center">
    <div class="card card-shadow ">
        <form class="form-horizontal" role="form" method="POST" action="{{ asset('/login') }}">
            @csrf
            <div class="card-img-top alert-div">
                @if(session()->has('error'))
                <div class="alert alert-danger m-2" >{{session()->get('error')}}</div>
                @endif
            </div>
            <div class="card-body">
                <div class="card-title">
                    <h5>HuiswerkApp</h5>
                </div>
                <div class="logo">
                    <img src="{{url('/images/login-logo.png')}}">
                </div>

                <div class="form-group">
                    <input id="email" type="email" class="form-control" name="email" placeholder="E-mailadres" required
                        autofocus>
                </div>

                <div class="form-group">
                    <input id="password" type="password" class="form-control" name="password" placeholder="Wachtwoord"
                        required>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    Inloggen
                </button>
            </div>
        </form>
    </div>
</div>
@endpush