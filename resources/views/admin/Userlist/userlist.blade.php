@extends('admin.layout.layout')
@section('content')

<!-- <div class="content-header row">
    <div class="content-header-left col-md-6 col-xs-12 mb-1">
    <h2 class="content-header-title">User List</h2>
    </div>
        <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
    <div class="breadcrumb-wrapper col-xs-12">
        <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.html">Home</a>
        </li>
        <li class="breadcrumb-item"><a href="#">Tables</a>
        </li>
        <li class="breadcrumb-item active">Basic Tables
        </li>
        </ol>
    </div>
    </div> 
</div> -->
<div class="content-body">
    <div class="row">
        <div class="col-xs-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{url(route('admin.useroperation'))}}"><button type="button" style="float:right;" class="btn btn-primary btn-min-width mr-1 mb-1">Add User</button></a>

                    <h4 class="card-title">User List</h4>
                </div>
                <div class="card-body collapse in">
                    <div class="card-block card-dashboard">
                    
                        <div class="table-responsive">
                            <table class="table" id="myTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Mobile Number</th>
                                        <th>Status</th>
                                        <th>Date Created</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user_list as $key=>$value)
                                        <tr>
                                            <th scope="row">{{++$key}}</th>
                                            <td>{{$value['name']}}</td>
                                            <td>{{$value['email']}}</td>
                                            <td>{{$value['mobile']}}</td>
                                            <td><span class="tag tag-default tag-success">{{$value['status']}}</span></td>
                                            <td>{{$value['created_at']}}</td>
                                            <td>
                                                <a href="{{url(route('admin.useroperation',['id' => $value['admin_user_id']]))}}"><button type="button" class="btn btn-outline-primary">Edit</button></a>
                                            </td>
                                        </tr>
                                    @endforeach    
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <!-- Basic Tables end -->
</div>

@endsection

@section('script')
<script>
    $(document).ready( function () {
        $('#myTable').DataTable();
    } );
</script>
@endsection
