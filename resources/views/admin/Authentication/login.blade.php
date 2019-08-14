@extends('admin.login_layout.login_layout')
@section('content')


        <div class="card-body collapse in">
            <div class="card-block">
                {{ Form::open(array('class' => 'form-horizontal form-simple','id' => 'id_admin_login','route' => 'get_admin_login_data')) }}
                    <fieldset class="form-group position-relative has-icon-left mb-0">
                        {{ Form::text('email','',array('class' => 'form-control form-control-lg input-lg','placeholder' => 'Enter Email')) }}
                        <div class="form-control-position">
                            <i class="icon-head"></i>
                        </div>
                    </fieldset>
                    <fieldset class="form-group position-relative has-icon-left">
                        {{ Form::password('password',array('class' => 'form-control form-control-lg input-lg','placeholder' => 'Enter Password')) }}
                         <div class="form-control-position">
                            <i class="icon-key3"></i>
                        </div>
                    </fieldset>
                    <fieldset class="form-group row">
                        <div class="col-md-6 col-xs-12 text-xs-center text-md-left">
                            <fieldset>
                                {{ Form::checkbox('remember-me','remember-me',false,array('class' => 'chk-remember')) }}
                         
                                <!-- <input type="checkbox" id="remember-me" class="chk-remember"> -->
                                <label for="remember-me"> Remember Me</label>
                            </fieldset>
                        </div>
                        <div class="col-md-6 col-xs-12 text-xs-center text-md-right"><a href="{{url(route('admin.forgot_password'))}}" class="card-link">Forgot Password?</a></div>
                    </fieldset>
                    {{ Form::submit('Login',array('class' => 'btn btn-primary btn-lg btn-block')) }}
                    
                {{ Form::close() }}	
            </div>
        </div>
        <div class="card-footer">
            <div class="">
                <p class="float-sm-left text-xs-center m-0"><a href="{{url(route('admin.forgot_password'))}}" class="card-link">Recover password</a></p>
                <!-- <p class="float-sm-right text-xs-center m-0"><a href="{{url(route('admin.signup'))}}" class="card-link">Sign Up</a></p> -->
            </div>
        </div>
@endsection