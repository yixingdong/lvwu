@extends('base.map')
@section('content')
    <div id="map" style="width:100%;height:850px;">
    </div>
@stop
@section('script')
    <script type="text/javascript" src="http://webapi.amap.com/maps?v=1.3&key=b6f97a31076e886a1236312d87e8b35e"></script>
    <script src="{{URL::asset('/')}}js/app.min.js"></script>
    <script src="{{URL::asset('/')}}js/map.js"></script>
@stop