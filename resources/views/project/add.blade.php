@extends('layouts.master')
@section('title','أضافة مشروع')
@section('content')
<div class="content">
<div class="panel panel-default">
	<div class="panel-heading">
		<h3>أضافة مشروع</h3>
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
	@if (count($orgs)>0)
	<form class="form-horizontal" method="post" action="{{ route('addproject') }}" id="add_project">
		<div class="form-group @if($errors->has('organization_id')) has-error @endif">
			<label for="organization_id" class="control-label col-sm-2 col-md-2 col-lg-2">تابع للعميل</label>
			<div class="col-sm-8 col-md-8 col-lg-8">
				<select class="form-control" name="organization_id" id="organization_id">
					@if(count($orgs)==1)
						<option value="{{$orgs[0]->id}}">{{$orgs[0]->name}}</option>
					@else
						@foreach($orgs as $org)
						<option value="{{$org->id}}">{{$org->name}}</option>
						@endforeach
					@endif
				</select>
				@if($errors->has('organization_id'))
					@foreach($errors->get('organization_id') as $error)
						<span class="help-block">{{ $error }}</span>
					@endforeach
				@endif
				<a href="{{ route('addorganization') }}">هل ترغب فى أضافة عميل جديد لهذا المشروع؟</a>
			</div>
		</div>
		<div class="form-group @if($errors->has('name')) has-error @endif">
			<label for="name" class="control-label col-sm-2 col-md-2 col-lg-2">أسم المشروع</label>
			<div class="col-sm-8 col-md-8 col-lg-8">
				<input type="text" name="name" id="name" value="{{old('name')}}" class="form-control" placeholder="أدخل أسم المشروع">
				@if($errors->has('name'))
					@foreach($errors->get('name') as $error)
						<span class="help-block">{{ $error }}</span>
					@endforeach
				@endif
			</div>
		</div>
		<div class="form-group @if($errors->has('def_num')) has-error @endif">
			<label for="def_num" class="control-label col-sm-2 col-md-2 col-lg-2">الرقم التعريفى للمشروع</label>
			<div class="col-sm-8 col-md-8 col-lg-8">
				<input type="text" name="def_num" id="def_num" value="{{old('def_num')}}" class="form-control" placeholder="أدخل الرقم التعريفى للمشروع">
				@if($errors->has('def_num'))
					@foreach($errors->get('def_num') as $error)
						<span class="help-block">{{ $error }}</span>
					@endforeach
				@endif
			</div>
		</div>
		<div class="form-group @if($errors->has('address')) has-error @endif">
			<label for="address" class="control-label col-sm-2 col-md-2 col-lg-2">شارع</label>
			<div class="col-sm-8 col-md-8 col-lg-8">
				<input type="text" name="address" id="address" value="{{old('address')}}" class="form-control" placeholder="أدخل الشارع">
				@if($errors->has('address'))
					@foreach($errors->get('address') as $error)
						<span class="help-block">{{ $error }}</span>
					@endforeach
				@endif
			</div>
		</div>
		<div class="form-group @if($errors->has('village')) has-error @endif">
			<label for="village" class="control-label col-sm-2 col-md-2 col-lg-2">قرية</label>
			<div class="col-sm-8 col-md-8 col-lg-8">
				<input type="text" name="village" id="village" value="{{old('village')}}" class="form-control" placeholder="أدخل القرية">
				@if($errors->has('village'))
					@foreach($errors->get('village') as $error)
						<span class="help-block">{{ $error }}</span>
					@endforeach
				@endif
			</div>
		</div>
		<div class="form-group @if($errors->has('center')) has-error @endif">
			<label for="center" class="control-label col-sm-2 col-md-2 col-lg-2">مركز</label>
			<div class="col-sm-8 col-md-8 col-lg-8">
				<input type="text" name="center" id="center" value="{{old('center')}}" class="form-control" placeholder="أدخل المركز">
				@if($errors->has('center'))
					@foreach($errors->get('center') as $error)
						<span class="help-block">{{ $error }}</span>
					@endforeach
				@endif
			</div>
		</div>
		<div class="form-group @if($errors->has('city')) has-error @endif">
			<label for="city" class="control-label col-sm-2 col-md-2 col-lg-2">مدينة</label>
			<div class="col-sm-8 col-md-8 col-lg-8">
				<input type="text" name="city" id="city" value="{{old('city')}}" class="form-control" placeholder="أدخل المدينة">
				@if($errors->has('city'))
					@foreach($errors->get('city') as $error)
						<span class="help-block">{{ $error }}</span>
					@endforeach
				@endif
			</div>
		</div>
		<div class="form-group @if($errors->has('extra_data')) has-error @endif">
			<label for="extra_data" class="control-label col-sm-2 col-md-2 col-lg-2">بيانات أضافية</label>
			<div class="col-sm-8 col-md-8 col-lg-8">
				<input type="text" name="extra_data" id="extra_data" value="{{old('extra_data')}}" class="form-control" placeholder="أدخل بيانات أضافية عن المشروع">
				@if($errors->has('extra_data'))
					@foreach($errors->get('extra_data') as $error)
						<span class="help-block">{{ $error }}</span>
					@endforeach
				@endif
			</div>
		</div>
		<div class="form-group @if($errors->has('model_used')) has-error @endif">
			<label for="model_used" class="control-label col-sm-2 col-md-2 col-lg-2">النموذج المستخدم</label>
			<div class="col-sm-8 col-md-8 col-lg-8">
				<input type="text" name="model_used" id="model_used" value="{{old('model_used')}}" class="form-control" placeholder="أدخل النموذج المستخدم">
				@if($errors->has('model_used'))
					@foreach($errors->get('model_used') as $error)
						<span class="help-block">{{ $error }}</span>
					@endforeach
				@endif
			</div>
		</div>
		<div class="form-group @if($errors->has('implementing_period')) has-error @endif">
			<label for="implementing_period" class="control-label col-sm-2 col-md-2 col-lg-2">مدة التنفيذ (بالشهر)</label>
			<div class="col-sm-8 col-md-8 col-lg-8">
				<input type="text" name="implementing_period" id="implementing_period" value="{{old('implementing_period')}}" class="form-control" placeholder="أدخل مدة التنفيذ بالشهور">
				@if($errors->has('implementing_period'))
					@foreach($errors->get('implementing_period') as $error)
						<span class="help-block">{{ $error }}</span>
					@endforeach
				@endif
			</div>
		</div>
		<div class="form-group @if($errors->has('floor_num')) has-error @endif">
			<label for="floor_num" class="control-label col-sm-2 col-md-2 col-lg-2">عدد الأدوار</label>
			<div class="col-sm-8 col-md-8 col-lg-8">
				<input type="text" name="floor_num" id="floor_num" value="{{old('floor_num')}}" class="form-control" placeholder="أدخل عدد الأدوار">
				@if($errors->has('floor_num'))
					@foreach($errors->get('floor_num') as $error)
						<span class="help-block">{{ $error }}</span>
					@endforeach
				@endif
			</div>
		</div>
		<div class="form-group @if($errors->has('approximate_price')) has-error @endif">
			<label for="approximate_price" class="control-label col-sm-2 col-md-2 col-lg-2">السعر الكلى التقريبى للمشروع</label>
			<div class="col-sm-8 col-md-8 col-lg-8">
				<input type="text" name="approximate_price" id="approximate_price" value="{{old('approximate_price')}}" class="form-control" placeholder="أدخل السعر الكلى التقريبى للمشروع">
				@if($errors->has('approximate_price'))
					@foreach($errors->get('approximate_price') as $error)
						<span class="help-block">{{ $error }}</span>
					@endforeach
				@endif
			</div>
		</div>
		<div class="form-group @if($errors->has('started_at')) has-error @endif">
			<label for="started_at" class="control-label col-sm-2 col-md-2 col-lg-2">تاريخ استلام الموقع</label>
			<div class="col-sm-8 col-md-8 col-lg-8">
				<input type="text" name="started_at" id="started_at" autocomplete="off" value="{{old('started_at')}}" class="form-control" placeholder="أدخل تاريخ استلام الموقع">
				@if($errors->has('started_at'))
					@foreach($errors->get('started_at') as $error)
						<span class="help-block">{{ $error }}</span>
					@endforeach
				@endif
			</div>
		</div>
		<div class="form-group @if($errors->has('cash_box')) has-error @endif">
			<label for="cash_box" class="control-label col-sm-2 col-md-2 col-lg-2">صندوق المال</label>
			<div class="col-sm-8 col-md-8 col-lg-8">
				<input type="text" name="cash_box" id="cash_box" value="{{old('cash_box')}}" class="form-control" placeholder="أدخل رأس مال الصندوق">
				@if($errors->has('cash_box'))
					@foreach($errors->get('cash_box') as $error)
						<span class="help-block">{{ $error }}</span>
					@endforeach
				@endif
			</div>
		</div>
		<div class="form-group @if($errors->has('loan')) has-error @endif">
			<label for="loan" class="control-label col-sm-2 col-md-2 col-lg-2">قيمة القرض</label>
			<div class="col-sm-8 col-md-8 col-lg-8">
				<input type="text" name="loan" id="loan" value="{{old('loan')}}" class="form-control" placeholder="أدخل قيمة القرض">
				@if($errors->has('loan'))
					@foreach($errors->get('loan') as $error)
						<span class="help-block">{{ $error }}</span>
					@endforeach
				@endif
			</div>
		</div>
		<div class="form-group @if($errors->has('loan_interest_rate')) has-error @endif">
			<label for="loan_interest_rate" class="control-label col-sm-2 col-md-2 col-lg-2">نسبة الفائدة</label>
			<div class="col-sm-8 col-md-8 col-lg-8">
				<input type="text" name="loan_interest_rate" id="loan_interest_rate" value="{{old('loan_interest_rate')}}" class="form-control" placeholder="أدخل نسبة فائدة القرض">
				@if($errors->has('loan_interest_rate'))
					@foreach($errors->get('loan_interest_rate') as $error)
						<span class="help-block">{{ $error }}</span>
					@endforeach
				@endif
			</div>
		</div>
		<div class="form-group @if($errors->has('bank')) has-error @endif">
			<label for="bank" class="control-label col-sm-2 col-md-2 col-lg-2">أسم البنك</label>
			<div class="col-sm-8 col-md-8 col-lg-8">
				<input type="text" name="bank" id="bank" value="{{old('bank')}}" class="form-control" placeholder="أدخل أسم البنك">
				@if($errors->has('bank'))
					@foreach($errors->get('bank') as $error)
						<span class="help-block">{{ $error }}</span>
					@endforeach
				@endif
			</div>
		</div>
		<div class="col-sm-2 col-md-2 col-lg-2 col-sm-offset-5 col-md-offset-5 col-lg-offset-5">
			<button class="btn btn-primary form-control" id="save_btn">حفظ</button>
		</div>
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
	</form>
	@else
		<div class="alert alert-warning">
		لا يوجد عملاء لكى تسطتيع أنشاء مشروع
			<a href="{{ route('addorganization') }}" class="btn btn-primary">أضافة عميل</a>
		</div>
	@endif
	</div>
</div>
</div>
@endsection
