@extends('layouts.master')
@section('title','تعديل نوع البند')
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>تعديل نوع البند {{$type->name}}</h3>
		</div>
		<div class="panel-body">
			<form method="post" action="{{ route('updatetermtype',$type->id) }}" class="form-horizontal">
				<div class="form-group row @if($errors->has('type')) has-error @endif ">
					<label for="type" class="control-label col-sm-2 col-md-2 col-lg-2">نوع بند</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="text" name="type" id="type" class="form-control" placeholder="أدخل نوع البند" value="{{$type->name}}">
						@if($errors->has('type'))
							@foreach($errors->get('type') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="col-sm-2 col-md-2 col-lg-2 col-sm-offset-5 col-md-offset-5 col-lg-offset-5">
					<button class="btn btn-primary form-control" id="save_btn">تعديل</button>
				</div>
				<input type="hidden" name="_method" value="PUT">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
			</form>
		</div>
	</div>
</div>
@endsection
