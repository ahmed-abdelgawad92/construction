@extends('layouts.master')
@section('title','خطأ')
@section('content')
<div class="mt-5 row">
	<div class="col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-sm-8 offset-sm-2">
	<img src="{{ asset('images/client.jpg') }}" width="100%" class="img-responsive">
	<h1 class="center">{{$msg}}</h1>
	</div>
</div>
@endsection
