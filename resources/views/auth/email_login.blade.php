@extends('base.master')
@section('content')
<div class="box box-info" style="border:solid #cccccc 1px;">
	<div class="box box-widget widget-user" style="margin-bottom:30px;">	    
	    <div class="widget-user-header bg-aqua-active">
	      <h3 class="widget-user-username">ET欢迎您超人归来</h3>
	    </div>
	    <div class="widget-user-image">
	      <img class="img-circle" src="{{URL::asset('/')}}images/user8-128x128.jpg" alt="User Avatar">
	    </div>
	</div>
	<!-- form start -->
	<form class="form-horizontal" action="{{ URL('login/email') }}" method="POST">
	  {!! csrf_field() !!}
	  <div class="box-body">
		<div class="input-group">
	      <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
	      <input type="email" class="form-control" placeholder="邮箱" name="email" value="{{ old('email') }}">
        </div>
        <br/>
		<div class="input-group">
	      <span class="input-group-addon"><i class="fa fa-asterisk"></i></span>
	      <input type="password" class="form-control" placeholder="密码" name="password">
        </div>
        <br/>
	    <div class="form-group">
	      <div class="col-sm-10">
	        <div class="checkbox">
	          <label>
	            <input type="checkbox" name="remember"> 记住我
	          </label>
	        </div>
	      </div>
	    </div>
	  </div><!-- /.box-body -->
	  <div class="box-footer">
	    <a class="btn btn-default" href="{{url('login')}}">手机登录</a>
		<a class="btn btn-default" href="{{URL('reset/email')}}">忘记密码</a>
	    <button type="submit" class="btn btn-info pull-right">登录</button>
	  </div><!-- /.box-footer -->
	</form>
</div>
@stop
@section('script')
<script src="{{URL::asset('/')}}js/app.min.js"></script>
@stop