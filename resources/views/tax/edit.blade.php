@extends('layouts.master')
@section('title','تعديل الضريبة')
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>تعديل تفاصيل ضريبة {{$tax->name}} بمشروع <a href="{{ route('showproject',$tax->project->id) }}">{{$tax->project->name}}</a></h3>
		</div>
		<div class="panel-body">
			@if(session('update_error'))
				<div class="alert alert-danger">
					<strong>خطأ</strong>
					<br>
					<ul>
						<li>{{ session('update_error') }}</li>
					</ul>
				</div>
			@endif
			<form method="post" action="{{ route('updatetax',$tax->id) }}" class="form-horizontal">
				<div class="form-group row @if($errors->has('name')) has-error @endif">
					<label for="name" class="control-label col-sm-2 col-md-2 col-lg-2">أسم الضريبة</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="text" name="name" id="name" value="{{$tax->name}}" class="form-control" placeholder="أدخل أسم هذه الضريبة">
						@if($errors->has('name'))
							@foreach($errors->get('name') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group row @if($errors->has('percent')) has-error @endif">
					<label for="percent" class="control-label col-sm-2 col-md-2 col-lg-2">نسبة الضريبة</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<div class="input-group">
						<input type="text" name="percent" id="percent" value="{{$tax->percent}}" class="form-control" placeholder="أدخل نسبة الضريبة">
						<span class="input-group-addon" id="basic-addon1">%</span>
						</div>
						@if($errors->has('percent'))
							@foreach($errors->get('percent') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="col-sm-2 col-md-2 col-lg-2 offset-sm-5 offset-md-5 offset-lg-5">
					<button class="btn btn-primary form-control" id="save_btn">تعديل</button>
				</div>
				<input type="hidden" name="_token" value="{{csrf_token()}}">
				<input type="hidden" name="_method" value="PUT">
			</form>
		</div>
	</div>
</div>
@endsection
