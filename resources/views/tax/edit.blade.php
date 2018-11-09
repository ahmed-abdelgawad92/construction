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
			<form method="post" action="{{ route('updatetax',$tax->id) }}" class="form-horizontal" id="add_tax">
				<div class="form-group row @if($errors->has('name')) has-error @endif">
					<label for="name" class="control-label col-sm-2 col-md-2 col-lg-2">أسم الأستقطاع</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="text" name="name" id="name" value="{{$tax->name}}" class="form-control" placeholder="أدخل أسم هذه الأستقطاع" autocomplete="off">
						@if($errors->has('name'))
							@foreach($errors->get('name') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				@if ($tax->paid==0)
				<div class="form-group row @if($errors->has('value')) has-error @endif">
					<label for="value" class="control-label col-sm-2 col-md-2 col-lg-2">نسبة الأستقطاع</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<div class="input-group w-100">
							<input type="text" name="value" id="value" value="{{$tax->value}}" class="form-control input-right" style="width:85% !important;" placeholder="أدخل قيمة أو نسبة الأستقطاع" autocomplete="off">
							<select class="form-control input-left" style="width:15% !important;" name="type" id="type">
								<option @if($tax->type == 1) selected @endif value="1">%</option>
									<option @if($tax->type == 2) selected @endif value="2">جنيه</option>
									</select>
						</div>
						@if($errors->has('value'))
							@foreach($errors->get('value') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				@else
				<input type="hidden" name="value" value="{{$tax->value}}">
				@endif
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
