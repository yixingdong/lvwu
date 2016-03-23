@extends('base.master')
@section('content')
    <div class="box box-info" style="border:solid #cccccc 1px;">
        <div class="box box-widget widget-user" style="margin-bottom:30px;">
            <div class="widget-user-header bg-aqua-active">
                <h3 class="widget-user-username">账号绑定</h3>
            </div>
            <div class="widget-user-image">
                <img class="img-circle" src="{{URL::asset('/')}}images/user9-128x128.jpg" alt="User Avatar">
            </div>
        </div>
        <!-- form start -->
        <form class="form-horizontal" action="{{URL('bind/exist')}}" method="POST">
            {!! csrf_field() !!}
            <div class="box-body">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                    <input type="text" class="form-control" placeholder="手机号码" name="phone" value="{{ old('phone') }}">
                </div>
                <br/>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-asterisk"></i></span>
                    <input type="password" class="form-control" placeholder="密码" name="password">
                </div>
            </div><!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-info pull-right">绑定</button>
            </div><!-- /.box-footer -->
        </form>
    </div>
@stop
@section('script')
    <script src="{{URL::asset('/')}}js/app.min.js"></script>
@stop