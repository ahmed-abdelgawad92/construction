@extends('layouts.master')
@section('title','تعديل موظف')
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>تعديل الموظف
			@if(Route::current()->getName()=='updateemployee')
			<a href="{{ route('showemployee',$employee->id) }}">{{$employee->name}}</a>
			@elseif(Route::current()->getName()=='updatecompanyemployee')
			<a href="{{ route('showcompanyemployee',$employee->id) }}">{{$employee->name}}</a>
			@endif
			</h3>
		</div>
		<div class="panel-body">
			@if(Route::current()->getName()=='updateemployee')
			<form method="post" action="{{ route('updateemployee',$employee->id) }}" class="form-horizontal" id="add_employee">
			@elseif(Route::current()->getName()=='updatecompanyemployee')
			<form method="post" action="{{ route('updatecompanyemployee',$employee->id) }}" class="form-horizontal" id="add_employee">
			@endif
				<div class="form-group row @if($errors->has('name')) has-error @endif">
					<label for="name" class="control-label col-sm-2 col-md-2 col-lg-2">أسم الموظف *</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="text" name="name" id="name" value="{{$employee->name}}" class="form-control" placeholder="أدخل أسم الموظف">
						@if($errors->has('name'))
							@foreach($errors->get('name') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group row @if($errors->has('job')) has-error @endif">
					<label for="job" class="control-label col-sm-2 col-md-2 col-lg-2">المسمى الوظيفى *</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="text" name="job" id="job" value="{{$employee->job}}" class="form-control" placeholder="أدخل المسمى الوظيفى">
						@if($errors->has('job'))
							@foreach($errors->get('job') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				@if(Route::current()->getName()=='updatecompanyemployee')
				<div class="form-group row @if($errors->has('salary')) has-error @endif">
					<label for="salary" class="control-label col-sm-2 col-md-2 col-lg-2">الراتب *</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="text" name="salary" id="salary" value="{{$employee->salary}}" class="form-control" placeholder="أدخل الراتب">
						@if($errors->has('salary'))
							@foreach($errors->get('salary') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				@endif
				@php
				$phones=explode(',',$employee->phone);
				@endphp
				@for($i=0;$i<count($phones);$i++)
				<div class="form-group row @if($errors->has('phone.'.$i)) has-error @endif" @if($i==0) id="phone_template" @else id="del_phone{{$i}}" @endif>
					<label for="phone{{$i}}" class="control-label col-sm-2 col-md-2 col-lg-2">تليفون @if($i==0)<a href="#" id="add_another_phone"> أضافة رقم جديد؟</a> *@else <span data-phone="{{$i}}" class="glyphicon glyphicon-trash delete_phone"></span> @endif</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="text" name="phone[{{$i}}]" id="phone{{$i}}" value="{{$phones[$i]}}" class="form-control phone_input" placeholder="أدخل التليفون">
						@if($errors->has('phone.'.$i))
							@foreach($errors->get('phone.'.$i) as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				@endfor
				<div class="form-group row @if($errors->has('address')) has-error @endif">
					<label for="address" class="control-label col-sm-2 col-md-2 col-lg-2">الشارع</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="text" name="address" id="address" value="{{$employee->address}}" class="form-control" placeholder="أدخل الشارع">
						@if($errors->has('address'))
							@foreach($errors->get('address') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group row @if($errors->has('village')) has-error @endif">
					<label for="village" class="control-label col-sm-2 col-md-2 col-lg-2">القرية</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="text" name="village" id="village" value="{{$employee->village}}" class="form-control" placeholder="أدخل القرية">
						@if($errors->has('village'))
							@foreach($errors->get('village') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group row @if($errors->has('city')) has-error @endif">
					<label for="city" class="control-label col-sm-2 col-md-2 col-lg-2">المدينة *</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="text" name="city" id="city" value="{{$employee->city}}" class="form-control" placeholder="أدخل المدينة">
						@if($errors->has('city'))
							@foreach($errors->get('city') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="col-sm-2 col-md-2 col-lg-2 offset-sm-5 offset-md-5 offset-lg-5">
					<button class="btn btn-primary form-control" id="save_btn">تعديل</button>
				</div>
				<input type="hidden" name="_method" value="PUT">
				<input type="hidden" name="_token" value="{{csrf_token()}}">
			</form>
		</div>
	</div>
</div>
@endsection
