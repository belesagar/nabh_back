@extends('admin.login_layout.login_layout')
@section('content')


    <div class="card-body collapse in">	
        <div class="card-block">
            <form class="form-horizontal form-simple" action="index.html" novalidate>
                <fieldset class="form-group position-relative has-icon-left mb-1">
                    <input type="text" class="form-control form-control-lg input-lg" id="user-name" placeholder="User Name">
                    <div class="form-control-position">
                        <i class="icon-head"></i>
                    </div>
                </fieldset>
                <fieldset class="form-group position-relative has-icon-left mb-1">
                    <input type="email" class="form-control form-control-lg input-lg" id="user-email" placeholder="Your Email Address" required>
                    <div class="form-control-position">
                        <i class="icon-mail6"></i>
                    </div>
                </fieldset>
                <fieldset class="form-group position-relative has-icon-left">
                    <input type="password" class="form-control form-control-lg input-lg" id="user-password" placeholder="Enter Password" required>
                    <div class="form-control-position">
                        <i class="icon-key3"></i>
                    </div>
                </fieldset>
                <button type="submit" class="btn btn-primary btn-lg btn-block"><i class="icon-unlock2"></i> Register</button>
            </form>
        </div>
        <p class="text-xs-center">Already have an account ? <a href="{{url(route('admin.login'))}}" class="card-link">Login</a></p>
    </div>

@endsection