@include('admin.login_layout.header')
@section('title', '{{title}}')
<div class="card-header no-border">
    <div class="card-title text-xs-center">
        <div class="p-1"><img src="{{asset('admin/images/logo/robust-logo-dark.png')}}" alt="branding logo"></div>
    </div>
    <h6 class="card-subtitle line-on-side text-muted text-xs-center font-small-3 pt-2"><span>{{$title}}</span></h6>
    {!! \Helpers::show_message() !!}
</div>
@yield('content')
@include('admin.login_layout.footer')
