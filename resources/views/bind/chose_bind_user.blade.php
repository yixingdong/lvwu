@extends('base.master')
@section('content')
    <div class="box box-info" style="border:solid #cccccc 1px;">
        <div class="box box-widget widget-user" style="margin-bottom:30px;">
            <div class="widget-user-header bg-aqua-active">
                <h3 class="widget-user-username">绑定手机账户方式</h3>
            </div>
            <div class="widget-user-image">
                <img class="img-circle" src="{{URL::asset('/')}}images/user9-128x128.jpg" alt="User Avatar">
            </div>
        </div>
        <!-- form start -->
        <form class="form-horizontal" action="{{URL('/bind/select')}}" method="POST">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="box-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label">
                        <input type="radio" name="bind" value="exist" checked /> 绑定已有账号
                    </label>
                    <label class="col-sm-4 control-label">
                        <input type="radio" name="bind" value="new" /> 新建手机账号
                    </label>
                </div>
            </div><!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-lg btn-warning pull-right">确定</button>
            </div><!-- /.box-footer -->
        </form>
    </div>
@stop
@section('script')
    <script src="{{URL::asset('/')}}js/app.min.js"></script>
@stop