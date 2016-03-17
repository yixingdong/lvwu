@extends('base.master')
@section('content')
<div class="box box-info" style="border:solid #cccccc 1px;">
	<div class="box box-widget widget-user" style="margin-bottom:30px;">	    
	    <div class="widget-user-header bg-aqua-active">
	      <h3 class="widget-user-username">完善个人信息</h3>
	    </div>
	    <div class="widget-user-image">
	      <img class="img-circle" src="{{URL::asset('/')}}images/user9-128x128.jpg" alt="User Avatar">
	    </div>
	</div>
	<!-- form start -->
	<form class="form" action="{{URL('profile/create')}}" method="POST">
	  {!! csrf_field() !!}
	  <div class="box-body">
		<div class="form-group">
          <label class="text-info">上传头像</label>
          <input type="file" name="avatar" class='btn btn-danger'>
          <p class="help-block"></p>
        </div>
        <br/>
		<div class="form-group">
          <label class="text-info">个性宣言</label>
          <textarea name="slogan" class="form-control" rows="3" placeholder="Enter ..."></textarea>
        </div>
	  </div><!-- /.box-body -->
	  <div class="box-footer">
	    <button type="submit" class="btn btn-info pull-right">提交</button>
	  </div><!-- /.box-footer -->
	</form>
</div>
@stop
@section('script')
<script src="{{URL::asset('/')}}app.min.js"></script>
@stop