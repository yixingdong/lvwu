@extends('base.master')
@section('content')
    <div class="box box-info" style="border:solid #cccccc 1px;">
        <div class="box box-widget widget-user" style="margin-bottom:30px;">
            <div class="widget-user-header bg-aqua-active">
                <h3 class="widget-user-username">邮箱绑定</h3>
            </div>
            <div class="widget-user-image">
                <img class="img-circle" src="{{URL::asset('/')}}images/user1-128x128.jpg" alt="User Avatar">
            </div>
        </div>
        <!-- form start -->
        <form class="form-horizontal" method="POST" action="{{URL('bind/email')}}">
            {!! csrf_field() !!}
            <div class="box-body">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                    <input type="email" class="form-control" placeholder="邮箱" name="email">
                </div>
                <br/>
                <div class="box-footer">
                    <button type="submit" class="btn btn-info pull-right">绑定</button>
                </div><!-- /.box-footer -->
        </form>
    </div>
@stop
@section('script')
    <script src="{{URL::asset('/')}}js/app.min.js"></script>
@stop