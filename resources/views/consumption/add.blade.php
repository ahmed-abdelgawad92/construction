@extends('layouts.master')
@section('title','أضافة أستهلاك')
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>أضافة أستهلاك جديد للبند <a href="{{ route('showterm',$term->id) }}">{{$term->code}}</a> بمشروع <a href="{{ route('showproject',$term->project->id) }}">{{$term->project->name}}</a></h3>
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
			@if(session('amount_error'))
				<div class="alert alert-danger">
					<strong>خطأ</strong>
					<br>
					<ul>
						<li>{{ session('amount_error') }} <a href="{{route('addstores',['cid'=>0,'pid'=>$term->project_id,'tid'=>$term->id])}}" class="btn btn-danger">أضافة خامات</a></li>
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
			<form method="post" action="{{ route('addconsumption',$term->id) }}" class="form-horizontal" id="add_consumption">
				@foreach($store_types as $type)
					<input type="hidden" name="{{$type->name}}" value="{{$type->unit}}">
				@endforeach
				@if (old("type")!=null)
					@for ($i=0; $i < count(old("type")); $i++)
						<div class="form-group row @if($errors->has('type.'.$i)) has-error @endif" id="choose_raw_to_consume{{$i+1}}">
							<label for="type_consumption{{$i+1}}" class="control-label col-sm-2 col-md-2 col-lg-2">نوع الخام *</label>
							<div class="col-sm-8 col-md-8 col-lg-8">
								<select id="type_consumption{{$i+1}}" name="type[]" class="form-control type_consumption">
									<option value="0">أختار نوع الخام المستهلك</option>
									@foreach($store_types as $type)
										@if(old('type.'.$i)==$type->name)
											<option value="{{$type->name}}" selected>{{$type->name}}</option>
											@php
												$unit= $type->unit ?? "";
											@endphp
										@else
											<option value="{{$type->name}}">{{$type->name}}</option>
										@endif
									@endforeach
								</select>
								@if($errors->has('type.'.$i))
									@foreach($errors->get('type.'.$i) as $error)
										<span class="help-block">{{ $error }}</span>
									@endforeach
								@endif
							</div>
							@if ($i>0)
							<div class="col-sm-2 col-lg-2 col-md-2">
								<button type="button" class="btn btn-danger delete_consumption_input_group" data-id="{{$i+1}}">حذف</button>
							</div>
							@else
							<div class="col-sm-2 col-lg-2 col-md-2">
								<button type="button" class="btn btn-primary" id="add_new_consumption_input_group">أضافة أستهلاك</button>
							</div>
							@endif
						</div>
						<div class="form-group row flex @if($errors->has('amount.'.$i)) has-error @endif" id="amount_cons{{$i+1}}">
							<label for="amount{{$i+1}}" class="control-label col-sm-2 col-md-2 col-lg-2">الكمية *</label>
							<div class="col-sm-8 col-md-8 col-lg-8">
								<div class="input-group amount_group">
									<input type="text" name="amount[]" id="amount{{$i+1}}" value="{{old('amount.'.$i)}}" class="form-control amount" placeholder="أدخل الكمية" aria-describedby="basic-addon1">
									<span class="input-group-addon" id="basic-addon{{$i+1}}">{{$unit}}</span>
								</div>
								@if($errors->has('amount.'.$i))
									@foreach($errors->get('amount.'.$i) as $error)
										<span class="help-block">{{ $error }}</span>
									@endforeach
								@endif
							</div>
						</div>
					@endfor
				@else
				<div class="form-group row @if($errors->has('type')) has-error @endif">
					<label for="type_consumption1" class="control-label col-sm-2 col-md-2 col-lg-2">نوع الخام *</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<select id="type_consumption1" name="type[]" class="form-control type_consumption">
							<option value="0">أختار نوع الخام المستهلك</option>
							@foreach($store_types as $type)
								@if(old('type')==$type->name)
									<option value="{{$type->name}}" selected>{{$type->name}}</option>
								@else
									<option value="{{$type->name}}">{{$type->name}}</option>
								@endif
							@endforeach
						</select>
						@foreach($store_types as $type)
							<input type="hidden" name="{{$type->name}}" value="{{$type->unit}}">
						@endforeach
						@if($errors->has('type'))
							@foreach($errors->get('type') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
					<div class="col-sm-2 col-lg-2 col-md-2">
						<button type="button" class="btn btn-primary" id="add_new_consumption_input_group">أضافة أستهلاك</button>
					</div>
				</div>
				<div class="form-group row amount_cons @if(old('type')!=0 && old('type')!=null) display @endif @if($errors->has('amount')) has-error @endif" id="amount_cons1">
					<label for="amount1" class="control-label col-sm-2 col-md-2 col-lg-2">الكمية *</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<div class="input-group amount_group">
							<input type="text" name="amount[]" id="amount1" value="{{old('amount')}}" class="form-control amount" placeholder="أدخل الكمية" aria-describedby="basic-addon1">
							<span class="input-group-addon" id="basic-addon1"></span>
						</div>
						@if($errors->has('amount'))
							@foreach($errors->get('amount') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				@endif
				<div class="col-sm-2 col-md-2 col-lg-2 offset-sm-5 offset-md-5 offset-lg-5">
					<button class="btn btn-primary form-control" id="save_btn">حفظ</button>
				</div>
				<input type="hidden" name="_token" value="{{csrf_token()}}">
			</form>
			@else
			<div class="alert alert-warning">
				لا يوجد أنواع خامات , من فضلك أضف أنواع للخاماات <a href="{{ route('addstoretype') }}" class="btn btn-warning">أضافة نوع خام جديد</a>
			</div>
			@endif
		</div>
	</div>
</div>
@endsection
