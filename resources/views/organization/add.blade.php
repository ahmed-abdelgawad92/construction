@extends('layouts.master')
@section('title','أضافة عميل')
@section('content')
<br><br>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3>أضافة عميل</h3>
	</div>
	<div class="panel-body">
	@if (count($errors) > 0)
		<div class="alert alert-danger">
			<strong>خطأ</strong>
		</div>
	@endif
	@if(session('insert_error'))
		<div class="alert alert-danger">
			<strong>خطأ</strong>
			<br>
			<ul>
				<li>{{ session('insert_error') }}</li>
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
	<form class="form-horizontal" method="post" action="{{ route('addorganization') }}" id="addOrganization">
		<div class="form-group row @if($errors->has('name')) has-error @endif">
			<label for="name" class="control-label col-sm-2 col-md-2 col-lg-2">أسم العميل *</label>
			<div class="col-sm-8 col-md-8 col-lg-8">
				<input type="text" name="name" id="name" value="{{old('name')}}" class="form-control" placeholder="أدخل أسم العميل">
				@if($errors->has('name'))
					@foreach($errors->get('name') as $error)
						<span class="help-block">{{ $error }}</span>
					@endforeach
				@endif
			</div>
		</div>
		<div class="form-group row @if($errors->has('address')) has-error @endif">
			<label for="address" class="control-label col-sm-2 col-md-2 col-lg-2">شارع *</label>
			<div class="col-sm-8 col-md-8 col-lg-8">
				<input type="text" name="address" id="address" value="{{old('address')}}" class="form-control" placeholder="أدخل الشارع">
				@if($errors->has('address'))
					@foreach($errors->get('address') as $error)
						<span class="help-block">{{ $error }}</span>
					@endforeach
				@endif
			</div>
		</div>
		<div class="form-group row @if($errors->has('center')) has-error @endif">
			<label for="center" class="control-label col-sm-2 col-md-2 col-lg-2">مركز *</label>
			<div class="col-sm-8 col-md-8 col-lg-8">
				<input type="text" name="center" id="center" value="{{old('center')}}" class="form-control" placeholder="أدخل المركز">
				@if($errors->has('center'))
					@foreach($errors->get('center') as $error)
						<span class="help-block">{{ $error }}</span>
					@endforeach
				@endif
			</div>
		</div>
		<div class="form-group row @if($errors->has('city')) has-error @endif">
			<label for="city" class="control-label col-sm-2 col-md-2 col-lg-2">مدينة *</label>
			<div class="col-sm-8 col-md-8 col-lg-8">
				<input type="text" name="city" id="city" value="{{old('city')}}" class="form-control" placeholder="أدخل المدينة">
				@if($errors->has('city'))
					@foreach($errors->get('city') as $error)
						<span class="help-block">{{ $error }}</span>
					@endforeach
				@endif
			</div>
		</div>
		@if(old('phone')!==null)
		@for($i=0; $i<count(old('phone')); $i++)
		<div class="form-group row @if($errors->has("phone.$i")) has-error @endif" @if($i==0) id="phone_template" @else id="del_phone{{$i}}" @endif>
			<label for="phone" class="control-label col-sm-2 col-md-2 col-lg-2">تليفون * @if($i==0)<a href="#" id="add_another_phone"> أضافة رقم جديد؟</a>@else <span data-phone="{{$i}}" class="glyphicon glyphicon-trash delete_phone"></span> @endif</label>
			<div class="col-sm-8 col-md-8 col-lg-8">
				<input type="text" name="phone[{{$i}}]" id="phone{{$i>0?$i:null}}" value="{{old("phone.".$i)}}" class="form-control phone_input number" placeholder="أدخل التليفون">
				@if($errors->has("phone.$i"))
					@foreach($errors->get("phone.$i") as $error)
						<span class="help-block">{{ $error }}</span>
					@endforeach
				@endif
			</div>
		</div>
		@endfor
		@else
		<div class="form-group row @if($errors->has('phone')) has-error @endif" id="phone_template">
			<label for="phone" class="control-label col-sm-2 col-md-2 col-lg-2">تليفون * <a href="#" id="add_another_phone"> أضافة رقم جديد؟</a></label>
			<div class="col-sm-8 col-md-8 col-lg-8">
				<input type="text" name="phone[0]" id="phone" value="" class="form-control phone_input number" placeholder="أدخل التليفون">
			</div>
		</div>
		@endif
		<div class="form-group row @if($errors->has('type')) has-error @endif">
			<label for="type" class="control-label col-sm-2 col-md-2 col-lg-2">نوع العميل</label>
			<div class="col-sm-8 col-md-8 col-lg-8">
				<label><input type="radio" name="type" value="0" id="" checked> عميل</label>
				<label><input type="radio" name="type" value="1" id=""> مقاول</label>
				@if($errors->has('type'))
					@foreach($errors->get('type') as $error)
						<span class="help-block">{{ $error }}</span>
					@endforeach
				@endif
			</div>
		</div>
		<div class="col-sm-2 col-md-2 col-lg-2 offset-sm-5 offset-md-5 offset-lg-5">
			<button class="btn btn-primary form-control" id="save_btn">حفظ</button>
		</div>

		<input type="hidden" name="_token" value="{{ csrf_token() }}">
	</form>
	</div>
</div>
@endsection()
