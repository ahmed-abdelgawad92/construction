@extends('layouts.master')
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
				<ul>
					@foreach ($errors->all() as $key => $value)
						<li>{{$key}} {{$value}}</li>
					@endforeach
				</ul>
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
		<form class="form-horizontal" method="post" action="{{ route('addsupplier') }}">
		<div class="form-group row @if($errors->has('name')) has-error @endif">
			<label for="name" class="control-label col-sm-2 col-md-2 col-lg-2">أسم المورد *</label>
			<div class="col-sm-8 col-md-8 col-lg-8">
				<input type="text" name="name" id="name" value="{{old('name')}}" class="form-control" placeholder="أدخل أسم المورد">
				@if($errors->has('name'))
					@foreach($errors->get('name') as $error)
						<span class="help-block">{{ $error }}</span>
					@endforeach
				@endif
			</div>
		</div>
    @if (count($store_types)>0)
			<div class="form-group row @if($errors->has('type')) has-error @endif" id="type_checkbox">
				<label for="type" class="control-label col-sm-2 col-md-2 col-lg-2">نوع المورد *</label>
				<div class="col-sm-8 col-md-8 col-lg-8">
					<div class="input-group" id="type_checkbox_container">
					<?php
					if(old('type'))
						$old_types=old('type');
					?>
					@foreach($store_types as $type)
					<label class="checkbox_label" @if($errors->has('type')) style="color: #a94442" @endif>
					<input type="checkbox" @if(old('type')) @if(in_array($type->name,$old_types)) checked @endif @endif name="type[]" value="{{$type->name}}">
					{{$type->name}}
					</label>
					@endforeach
					<a href="#" id="add_extra_supplier_type">إضافة نوع مورد جديد؟</a>
					</div>
					@if($errors->has('type'))
						@foreach($errors->get('type') as $error)
							<span class="help-block">{{ $error }}</span>
						@endforeach
					@endif
				</div>
			</div>
			@if(old('supplier_type')!==null)
			@for($i=0; $i<count(old('supplier_type')); $i++)
			<div class="form-group row @if($errors->has("supplier_type.$i")||$errors->has("supplier_type_unit.$i")) has-error @endif" id="del_type{{$i}}">
				<label for="supplier_type{{$i>0?$i:null}}" class="control-label col-sm-2 col-md-2 col-lg-2">نوع المورد * <span data-type="{{$i}}" class="glyphicon glyphicon-trash delete_term_type"></span></label>
				<div class="col-sm-8 col-md-8 col-lg-8">
					<input type="text" name="supplier_type[{{$i}}]" id="supplier_type{{$i>0?$i:null}}" value="{{old("supplier_type.".$i)}}" class="form-control input-right supplier_type_input" placeholder="أدخل نوع مورد جديد">
					<input type="text" name="supplier_type_unit[{{$i}}]" id="supplier_type_unit{{$i>0?$i:null}}" value="{{old("supplier_type_unit.".$i)}}" class="form-control input-left supplier_type_unit_input" placeholder="أدخل الوحدة">
					@if($errors->has("supplier_type.$i"))
						@foreach($errors->get("supplier_type.$i") as $error)
							<span class="help-block">{{ $error }}</span>
						@endforeach
					@endif
					@if($errors->has("supplier_type_unit.$i"))
						@foreach($errors->get("supplier_type_unit.$i") as $error)
							<span class="help-block">{{ $error }}</span>
						@endforeach
					@endif
				</div>
			</div>
			@endfor
			@endif
		@elseif(old('supplier_type')!==null)
			@for($i=0; $i<count(old('supplier_type')); $i++)
			<div class="form-group row @if($errors->has("supplier_type.$i")||$errors->has("supplier_type_unit.$i")) has-error @endif" @if($i>0)id="del_type{{$i}}"@else id="type_checkbox"@endif>
				<label for="supplier_type{{$i>0?$i:null}}" class="control-label col-sm-2 col-md-2 col-lg-2">نوع المورد * @if($i>0)<span data-type="{{$i}}" class="glyphicon glyphicon-trash delete_term_type"></span>@else<a href="#" id="add_extra_supplier_type">إضافة نوع مورد جديد؟</a>@endif</label>
				<div class="col-sm-8 col-md-8 col-lg-8">
					<input type="text" name="supplier_type[{{$i}}]" id="supplier_type{{$i>0?$i:null}}" value="{{old("supplier_type.".$i)}}" class="form-control input-right supplier_type_input" placeholder="أدخل نوع مورد جديد">
					<input type="text" name="supplier_type_unit[{{$i}}]" id="supplier_type_unit{{$i>0?$i:null}}" value="{{old("supplier_type_unit.".$i)}}" class="form-control input-left supplier_type_unit_input" placeholder="أدخل الوحدة">
					@if($errors->has("supplier_type.$i"))
						@foreach($errors->get("supplier_type.$i") as $error)
							<span class="help-block">{{ $error }}</span>
						@endforeach
					@endif
					@if($errors->has("supplier_type_unit.$i"))
						@foreach($errors->get("supplier_type_unit.$i") as $error)
							<span class="help-block">{{ $error }}</span>
						@endforeach
					@endif
				</div>
			</div>
			@endfor
		@else
		<div class="form-group row" id="type_checkbox">
			<label for="supplier_type" class="control-label col-sm-2 col-md-2 col-lg-2">نوع المورد * <a href="#" id="add_extra_supplier_type">إضافة نوع مورد جديد؟</a></label>
			<div class="col-sm-8 col-md-8 col-lg-8">
				<input type="text" name="supplier_type[0]" id="supplier_type0" value="" class="form-control input-right supplier_type_input" placeholder="أدخل نوع مورد جديد">
				<input type="text" name="supplier_type_unit[0]" id="supplier_type_unit0" value="" class="form-control input-left supplier_type_unit_input" placeholder="أدخل الوحدة">
			</div>
		</div>
		@endif
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
			<label for="city" class="control-label col-sm-2 col-md-2 col-lg-2">المدينة *</label>
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
		<div class="col-sm-2 col-md-2 col-lg-2 offset-sm-5 offset-md-5 offset-lg-5">
			<button class="btn btn-primary form-control" id="save_btn">حفظ</button>
		</div>
		<input type="hidden" name="_token" value="{{csrf_token()}}">
		</form>
		</div>
	</div>
</div>
@endsection
