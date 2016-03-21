@extends('base.master')
@section('content')
<div class="box box-info" style="border:solid #cccccc 1px;">
    <div class="box box-widget widget-user" style="margin-bottom:30px;">        
        <div class="widget-user-header bg-aqua-active">
          <h3 class="widget-user-username">欢迎小伙伴加入</h3>
        </div>
        <div class="widget-user-image">
          <img class="img-circle" src="{{URL::asset('/')}}images/user1-128x128.jpg" alt="User Avatar">
        </div>
    </div>
    <!-- form start -->
    <form class="form-horizontal" method="POST" action="{{URL('reset/confirmed')}}">
      {!! csrf_field() !!}
      <div class="box-body">
        <div class="input-group hidden">
          <span class="input-group-addon"><i class="fa fa-phone"></i></span>
          @if(Session::has('phone'))
          <input type="text" class="form-control" placeholder="手机号码" name="phone" value="{{Session::get('phone')}}">
          @endif
        </div>
        <br/>
        <div class="input-group">
          <span class="input-group-addon"><i class="fa fa-asterisk"></i></span>
          <input type="password" class="form-control" placeholder="设置密码" name="password">
        </div>
        <br/>
        <div class="input-group">
          <span class="input-group-addon"><i class="fa fa-asterisk"></i></span>
          <input type="password" class="form-control" placeholder="确认密码" name="password_confirmation">
        </div>       
        <div class="box-footer">          
          <button type="submit" class="btn btn-info pull-right">设置密码</button>
        </div><!-- /.box-footer -->
    </form>
</div>
@stop
@section('script')
<script src="{{URL::asset('/')}}js/app.min.js"></script>
@stop