@extends('layouts.master')
@section('title','تعديل بيانات المقاول')
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>تعديل بيانات المقاول {{$contractor->name}}</h3>
		</div>
		<div class="panel-body">
		@if (count($errors) > 0)
			<div class="alert alert-danger">
				<strong>خطأ</strong>
				@foreach($errors as $error)
					{{$error}}
				@endforeach
			</div>
		@endif
		@if(session('update_error'))
			<div class="alert alert-danger">
				<strong>خطأ</strong>
				<br>
				<ul>
					<li>{{ session('update_error') }}</li>
				</ul>
			</div>
		@endif
		@if(session('success'))
			<div class="alert alert-success">
				<strong>تمت بنجاح</strong>
				<br>
				<ul>
					<li>{{ session('success') }}</li>
				</ul>
			</div>
		@endif
		<form class="form-horizontal" method="post" action="{{ route('updatecontractor',$contractor->id) }}">
		<div class="form-group @if($errors->has('name')) has-error @endif">
			<label for="name" class="control-label col-sm-2 col-md-2 col-lg-2">أسم المقاول</label>
			<div class="col-sm-8 col-md-8 col-lg-8">
				<input type="text" name="name" id="name" value="{{$contractor->name}}" class="form-control" placeholder="أدخل أسم المقاول">
				@if($errors->has('name'))
					@foreach($errors->get('name') as $error)
						<span class="help-block">{{ $error }}</span>
					@endforeach
				@endif
			</div>
		</div>
		<div class="form-group @if($errors->has('type')) has-error @endif">
			<label for="type" class="control-label col-sm-2 col-md-2 col-lg-2">نوع المقاول</label>
			<div class="col-sm-8 col-md-8 col-lg-8">
				@foreach($term_types as $type)
				<label @if($errors->has('type')) style="color: #a94442" @endif>
				<input type="checkbox" name="type[]" value="{{$type->name}}" @if(in_array($type->name,$contractor_types)) checked @endif > {{$type->name}}
				</label>
				@endforeach
				@if($errors->has('type'))
					@foreach($errors->get('type') as $error)
						<span class="help-block">{{ $error }}</span>
					@endforeach
				@endif
			</div>
		</div>
		<div class="form-group @if($errors->has('address')) has-error @endif">
			<label for="address" class="control-label col-sm-2 col-md-2 col-lg-2">الشارع</label>
			<div class="col-sm-8 col-md-8 col-lg-8">
				<input type="text" name="address" id="address" value="{{$contractor->address}}" class="form-control" placeholder="أدخل الشارع">
				@if($errors->has('address'))
					@foreach($errors->get('address') as $error)
						<span class="help-block">{{ $error }}</span>
					@endforeach
				@endif
			</div>
		</div>
		<div class="form-group @if($errors->has('center')) has-error @endif">
			<label for="center" class="control-label col-sm-2 col-md-2 col-lg-2">المركز</label>
			<div class="col-sm-8 col-md-8 col-lg-8">
				<input type="text" name="center" id="center" value="{{$contractor->center}}" class="form-control" placeholder="أدخل المركز">
				@if($errors->has('center'))
					@foreach($errors->get('center') as $error)
						<span class="help-block">{{ $error }}</span>
					@endforeach
				@endif
			</div>
		</div>
		<div class="form-group @if($errors->has('city')) has-error @endif">
			<label for="city" class="control-label col-sm-2 col-md-2 col-lg-2">المدينة</label>
			<div class="col-sm-8 col-md-8 col-lg-8">
				<input type="text" name="city" id="city" value="{{$contractor->city}}" class="form-control" placeholder="أدخل المدينة">
				@if($errors->has('city'))
					@foreach($errors->get('city') as $error)
						<span class="help-block">{{ $error }}</span>
					@endforeach
				@endif
			</div>
		</div>
		<div class="form-group @if($errors->has('phone')) has-error @endif">
			<label for="phone" class="control-label col-sm-2 col-md-2 col-lg-2">التليفون</label>
			<div class="col-sm-8 col-md-8 col-lg-8">
				<input type="text" name="phone" id="phone" value="{{$contractor->phone}}" class="form-control" placeholder="أدخل التليفون">
				@if($errors->has('phone'))
					@foreach($errors->get('phone') as $error)
						<span class="help-block">{{ $error }}</span>
					@endforeach
				@endif
			</div>
		</div>
		<div class="col-sm-2 col-md-2 col-lg-2 col-sm-offset-5 col-md-offset-5 col-lg-offset-5">
			<button class="btn btn-primary form-control" id="save_btn">تعديل</button>
		</div>
		<input type="hidden" name="_method" value="PUT">
		<input type="hidden" name="_token" value="{{csrf_token()}}">
		</form>
		</div>
	</div>
</div>
@endsection
