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
		@if(session('info'))
			<div class="alert alert-info">
				<strong>{{ session('info') }}</strong>
			</div>
		@endif
		<form class="form-horizontal" method="post" action="{{ route('updatecontractor',$contractor->id) }}" id="add_contractor">
		<div class="form-group row @if($errors->has('name')) has-error @endif">
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
		<div class="form-group row @if($errors->has('type')) has-error @endif"  id="type_checkbox">
			<label for="type" class="control-label col-sm-2 col-md-2 col-lg-2">نوع المقاول</label>
			<div class="col-sm-8 col-md-8 col-lg-8">
        <div class="input-group" id="type_checkbox_container">
  				@foreach($term_types as $type)
  				<label class="checkbox_label" @if($errors->has('type')) style="color: #a94442" @endif>
  				<input type="checkbox" name="type[]" value="{{$type->name}}" @if(in_array($type->name,$contractor_types)) checked @endif > {{$type->name}}
  				</label>
  				@endforeach
          <a href="#" id="add_extra_term_type">إضافة نوع مقاول جديد؟</a>
        </div>
				@if($errors->has('type'))
					@foreach($errors->get('type') as $error)
						<span class="help-block">{{ $error }}</span>
					@endforeach
				@endif
			</div>
		</div>
    @if(old('contractor_type')!==null)
    @for($i=0; $i<count(old('contractor_type')); $i++)
    <div class="form-group row @if($errors->has("contractor_type.$i")) has-error @endif" id="del_type{{$i}}">
      <label for="contractor_type{{$i>0?$i:null}}" class="control-label col-sm-2 col-md-2 col-lg-2">نوع المقاول * <span data-type="{{$i}}" class="glyphicon glyphicon-trash delete_term_type"></span></label>
      <div class="col-sm-8 col-md-8 col-lg-8">
        <input type="text" name="contractor_type[{{$i}}]" id="contractor_type{{$i>0?$i:null}}" value="{{old("contractor_type.".$i)}}" class="form-control term_type_input" placeholder="أدخل نوع مقاول جديد">
        @if($errors->has("contractor_type.$i"))
          @foreach($errors->get("contractor_type.$i") as $error)
            <span class="help-block">{{ $error }}</span>
          @endforeach
        @endif
      </div>
    </div>
    @endfor
    @endif
		<div class="form-group row @if($errors->has('address')) has-error @endif">
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
		<div class="form-group row @if($errors->has('center')) has-error @endif">
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
		<div class="form-group row @if($errors->has('city')) has-error @endif">
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
    @php
      $phones = explode(",",$contractor->phone);
    @endphp
    @for($i=0; $i<count($phones); $i++)
		<div class="form-group row @if($errors->has("phone.$i")) has-error @endif" @if($i==0) id="phone_template" @else id="del_phone{{$i}}" @endif>
			<label for="phone" class="control-label col-sm-2 col-md-2 col-lg-2">تليفون * @if($i==0)<a href="#" id="add_another_phone"> أضافة رقم جديد؟</a>@else <span data-phone="{{$i}}" class="glyphicon glyphicon-trash delete_phone"></span> @endif</label>
			<div class="col-sm-8 col-md-8 col-lg-8">
				<input type="text" name="phone[{{$i}}]" id="phone{{$i>0?$i:null}}" value="{{$phones[$i]}}" class="form-control phone_input number" placeholder="أدخل التليفون">
				@if($errors->has("phone.$i"))
					@foreach($errors->get("phone.$i") as $error)
						<span class="help-block">{{ $error }}</span>
					@endforeach
				@endif
			</div>
		</div>
		@endfor
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
