@extends('admin.login_layout.login_layout')
@section('content')

    <div class="card-body collapse in">
        <div class="card-block">
            {{ Form::open(array('class' => 'form-horizontal form-simple','id' => 'id_forgot_password','route' => 'get_forgot_password_data')) }}
                <fieldset class="form-group position-relative has-icon-left">
                    {{ Form::text('email','',array('class' => 'form-control form-control-lg input-lg','placeholder' => 'Enter Your Email ID')) }}
                    <div class="form-control-position">
                        <i class="icon-mail6"></i>
                    </div>
                </fieldset>
                <button type="submit" class="btn btn-primary btn-lg btn-block"><i class="icon-lock4"></i> Recover Password</button>
            {{ Form::close() }}	
        </div>
    </div>
    <div class="card-footer no-border">
        <p class="float-sm-left text-xs-center"><a href="{{url(route('admin.login'))}}" class="card-link">Login</a></p>
        <!-- <p class="float-sm-right text-xs-center"><a href="{{url(route('admin.signup'))}}" class="card-link">Create Account</a></p> -->
    </div>

@endsection