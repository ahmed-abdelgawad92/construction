@extends('layouts.master')
@section('title','تعديل رسم')
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>تعديل بيانات الرسم</h3>
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
			@if(session('info'))
				<div class="alert alert-info">
						<h4>{{ session('info') }}</h4>
				</div>
			@endif
			<form method="post" action="{{ route('updategraph',$graph->id) }}" class="form-horizontal" id="add_graph">
				<div class="form-group row @if($errors->has('name')) has-error @endif">
					<label for="name" class="control-label col-sm-2 col-md-2 col-lg-2">أسم الرسم *</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="text" name="name" id="name" value="{{$graph->name}}" class="form-control" placeholder="أدخل أسم الرسم">
						@if($errors->has('name'))
							@foreach($errors->get('name') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group row @if($errors->has('type')) has-error @endif">
					<label for="type" class="control-label col-sm-2 col-md-2 col-lg-2">نوع الرسم *</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<select name="type" id="type" class="form-control">
							<option value="0" @if($graph->type==0) selected @endif >رسم أنشائية</option>
							<option value="1" @if($graph->type==1) selected @endif >رسم معمارى</option>
						</select>
						@if($errors->has('type'))
							@foreach($errors->get('type') as $error)
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
