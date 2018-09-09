@extends('layouts.master')
@section('title','خطأ')
@section('content')
<div class="row">
	<div class="col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3 col-sm-8 col-sm-offset-2">
	<img src="{{ asset('images/404.png') }}" width="100%" class="img-responsive">
	</div>
</div>
@endsection
@section('guestcontent')
<div class="row">
	<div class="col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3 col-sm-8 col-sm-offset-2">
	<img src="{{ asset('images/404.png') }}" width="100%" class="img-responsive">
	</div>
</div>
@endsection
