@extends('admin.login_layout.login_layout')
@section('content')

    <div class="card-body collapse in">
        <div class="card-block">
            {{ Form::open(array('class' => 'form-horizontal form-simple','id' => 'id_reset_password','route' => 'set_reset_password')) }}
                <fieldset class="form-group position-relative has-icon-left">
                    {{ Form::password('password',array('class' => 'form-control form-control-lg input-lg','placeholder' => 'Enter Password')) }}
                    <div class="form-control-position">
                        <i class="icon-mail6"></i>
                    </div>
                </fieldset>
                <fieldset class="form-group position-relative has-icon-left">
                    {{ Form::password('cpassword',array('class' => 'form-control form-control-lg input-lg','placeholder' => 'Enter confirm Password')) }}
                    <div class="form-control-position">
                        <i class="icon-mail6"></i>
                    </div>
                </fieldset>
                <button type="submit" class="btn btn-primary btn-lg btn-block"><i class="icon-lock4"></i> Set Password</button>
            {{ Form::close() }}	
        </div>
    </div>
    <div class="card-footer no-border">
        <p class="float-sm-left text-xs-center"><a href="{{url(route('admin.login'))}}" class="card-link">Login</a></p>
        <!-- <p class="float-sm-right text-xs-center"><a href="{{url(route('admin.signup'))}}" class="card-link">Create Account</a></p> -->
    </div>

@endsection