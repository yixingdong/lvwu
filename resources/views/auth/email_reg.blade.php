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
    <form class="form-horizontal" method="POST" action="{{URL('reg/email')}}">
      {!! csrf_field() !!}
      <div class="box-body">
        <div class="input-group">
          <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
          <input type="email" class="form-control" placeholder="邮箱" name="email">
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
        <br/>
        <div class="form-group">
          <div class="col-sm-8">
              <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                  <input type="text" class="form-control" placeholder="输入图形验证码" name="cpt">
              </div>
          </div>
          <div class="col-sm-2">
              <img src="{{ url('tool/cpt') }}" onclick="this.src='{{ url('tool/cpt')}}?r='+Math.random();" alt="">
          </div>
        </div>
        <div class="box-footer">
          <a class="btn btn-default" href="{{URL('register')}}">手机注册</a>    
          <button type="submit" class="btn btn-info pull-right">注册</button>
        </div><!-- /.box-footer -->
    </form>
</div>
@stop
@section('script')
<script src="{{URL::asset('/')}}js/app.min.js"></script>
@stop