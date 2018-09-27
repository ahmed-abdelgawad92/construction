 row@extends('layouts.master')
@section('title','أضافة مورد')
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>أضافة مورد</h3>
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
		@if(count($store_types)>0)
		<form class="form-horizontal" method="post" action="{{ route('addsupplier') }}">
		<div class="form-group row @if($errors->has('name')) has-error @endif">
			<label for="name" class="control-label col-sm-2 col-md-2 col-lg-2">أسم المورد</label>
			<div class="col-sm-8 col-md-8 col-lg-8">
				<input type="text" name="name" id="name" value="{{old('name')}}" class="form-control" placeholder="أدخل أسم المورد">
				@if($errors->has('name'))
					@foreach($errors->get('name') as $error)
						<span class="help-block">{{ $error }}</span>
					@endforeach
				@endif
			</div>
		</div>
		<div class="form-group row @if($errors->has('type')) has-error @endif">
			<label for="type" class="control-label col-sm-2 col-md-2 col-lg-2">نوع المورد</label>
			<div class="col-sm-8 col-md-8 col-lg-8">
				<?php
				if(old('type'))
					$old_types=old('type');
				?>
				@foreach($store_types as $type)
				<label @if($errors->has('type')) style="color: #a94442" @endif>
				<input type="checkbox" @if(old('type')) @if(in_array($type->name,$old_types)) checked @endif @endif name="type[]" value="{{$type->name}}">
				{{$type->name}}
				</label>
				@endforeach
				@if($errors->has('type'))
					@foreach($errors->get('type') as $error)
						<span class="help-block">{{ $error }}</span>
					@endforeach
				@endif
			</div>
		</div>
		<div class="form-group row @if($errors->has('address')) has-error @endif">
			<label for="address" class="control-label col-sm-2 col-md-2 col-lg-2">الشارع</label>
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
			<label for="center" class="control-label col-sm-2 col-md-2 col-lg-2">المركز</label>
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
			<label for="city" class="control-label col-sm-2 col-md-2 col-lg-2">المدينة</label>
			<div class="col-sm-8 col-md-8 col-lg-8">
				<input type="text" name="city" id="city" value="{{old('city')}}" class="form-control" placeholder="أدخل المدينة">
				@if($errors->has('city'))
					@foreach($errors->get('city') as $error)
						<span class="help-block">{{ $error }}</span>
					@endforeach
				@endif
			</div>
		</div>
		<div class="form-group row @if($errors->has('phone')) has-error @endif">
			<label for="phone" class="control-label col-sm-2 col-md-2 col-lg-2">التليفون</label>
			<div class="col-sm-8 col-md-8 col-lg-8">
				<input type="text" name="phone" id="phone" value="{{old('phone')}}" class="form-control" placeholder="أدخل التليفون">
				@if($errors->has('phone'))
					@foreach($errors->get('phone') as $error)
						<span class="help-block">{{ $error }}</span>
					@endforeach
				@endif
			</div>
		</div>
		<div class="col-sm-2 col-md-2 col-lg-2 offset-sm-5 offset-md-5 offset-lg-5">
			<button class="btn btn-primary form-control" id="save_btn">حفظ</button>
		</div>
		<input type="hidden" name="_token" value="{{csrf_token()}}">
		</form>
		@else
		<div class="alert alert-warning">لا يوجد أنواع خامات , لا يمكن أضافة مورد لأن أنواع المورد معتمدة على أنواع الخامات لذا يجب أضافة أنواع الخامات أولا <a href="{{ route('addtermtype') }}" class="btn btn-warning">أضافة نوع خام</a> </div>
		@endif
		</div>
	</div>
</div>
@endsection
