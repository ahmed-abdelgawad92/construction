@extends('layouts.master')
@section('title','شراء خامات')
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>أضافة خامات الى المخازن @if(isset($project)) بمشروع <a href="{{ route('showproject',$project->id) }}">{{$project->name}}</a> @endif </h3>
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
			@if(( (isset($projects)&&count($projects)>0) || isset($project)) && ((isset($suppliers)&&count($suppliers)>0)||isset($supplier)))
			<form method="post" action="{{ route('addstore') }}" class="form-horizontal" id="add_store">
				<div class="form-group row @if($errors->has('project_id')) has-error @endif">
					<label for="project_id" class="control-label col-sm-2 col-md-2 col-lg-2">أختار مشروع *</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<select name="project_id" id="project_id" class="form-control">
							@if(isset($project))
							<option value="{{$project->id}}">{{$project->name}} - {{$project->city}} - {{$project->def_num}}</option>
							@else
							<option value="">أختار مشروع</option>
							@foreach($projects as $project)
							<option value="{{$project->id}}" @if(old('project_id')==$project->id) selected @endif >{{$project->name}} - {{$project->city}} - {{$project->def_num}}</option>
							@endforeach
							@endif
						</select>
						@if($errors->has('project_id'))
							@foreach($errors->get('project_id') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group row @if($errors->has('type')) has-error @endif" id="store_select_input">
					<label for="type_supplier" class="control-label col-sm-2 col-md-2 col-lg-2">نوع الخام *</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="text" id="type_supplier" name="type" autocomplete="off" class="form-control" value="{{old("type")}}" placeholder="أدخل نوع الخام"/>
						<div role="listbox" class="select_container" id="type_options">
						@php
							$count= 0;
						@endphp
						@foreach($store_types as $type)
							<div role="option" tabindex="{{$count++}}" class="select_option">{{$type->name}}</div>
							<input type="hidden" name="store_type[]" value="{{$type->name}}">
							<input type="hidden" name="{{$type->name}}" value="{{$type->unit}}">
						@endforeach
						</div>
						@if($errors->has('type'))
							@foreach($errors->get('type') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group row @if($errors->has('amount')) has-error @elseif(count($errors) > 0) @else hide @endif" id="amount_div">
					<label for="amount" class="control-label col-sm-2 col-md-2 col-lg-2">الكمية *</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<div class="input-group" id="amount_group">
							<input type="text" name="amount" id="amount" value="{{old('amount')}}" class="form-control" placeholder="أدخل الكمية" aria-describedby="basic-addon1">
							<span class="input-group-addon" id="basic-addon1">@if(old("type")){{$store_types->where("name",old("type"))->first()->unit ?? " لا يوجد"}}@else لا يوجد @endif</span>
						</div>
						@if($errors->has('amount'))
							@foreach($errors->get('amount') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				@if (isset($supplier))
				<div class="form-group row @if($errors->has("supplier_id")) has-error @endif">
					<label for="supplier_id" class="control-label col-sm-2 col-md-2 col-lg-2">أختار المقاول المورد *</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<div class="input-group" id="supplier_id_group">
							<input type="text"  autocomplete="off" class="form-control readonly" readonly  placeholder="أختار المقاول المورد" value="{{$supplier->name}} - {{$supplier->city}} - {{str_replace(","," , ",$supplier->phone)}} ({{str_replace(","," , ",$supplier->type)}})">
							<input type="hidden" name="supplier_id" id="supplier_id" value="{{$supplier->id}}">
							<span class="input-group-addon" id="basic-addon1">أختار</span>
						</div>
						@if($errors->has("supplier_id"))
							@foreach($errors->get("supplier_id") as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				@else
				<div class="form-group row @if($errors->has("supplier_id")) has-error @endif">
					<label for="supplier_id" class="control-label col-sm-2 col-md-2 col-lg-2">أختار المقاول المورد *</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<div class="input-group"  id="supplier_id_group">
							<input type="text" name="supplier_details" id="show_supplier_details" autocomplete="off" class="form-control readonly" readonly  placeholder="أختار المقاول المورد" value="{{old("supplier_details")}}">
							<input type="hidden" name="supplier_id" id="supplier_id" value="{{old("supplier_id")}}">
							<span class="input-group-addon" id="basic-addon1">أختار</span>
						</div>
						@if($errors->has("supplier_id"))
							@foreach($errors->get("supplier_id") as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				@endif
				<div class="form-group row @if($errors->has('value')) has-error @endif">
					<label for="value" class="control-label col-sm-2 col-md-2 col-lg-2">قيمة الوحدة *</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<div class="input-group"  id="value_group">
							<input type="text" name="value" id="value" class="form-control" autocomplete="off" placeholder="أدخل قيمة الوحدة" value="{{old('value')}}">
							<span class="input-group-addon" id="basic-addon1">جنيه</span>
						</div>
						@if($errors->has('value'))
							@foreach($errors->get('value') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group row @if($errors->has('amount_paid')) has-error @endif">
					<label for="amount_paid" class="control-label col-sm-2 col-md-2 col-lg-2">المبلغ المدفوع *</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<div class="input-group"  id="amount_paid_group">
							<input type="text" name="amount_paid" id="amount_paid" autocomplete="off" class="form-control" placeholder="أدخل المبلغ المدفوع" value="{{old('amount_paid')}}">
							<span class="input-group-addon" id="basic-addon1">جنيه</span>
						</div>
						@if($errors->has('amount_paid'))
							@foreach($errors->get('amount_paid') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				@if((isset($project)&&$project->loan!=null)||isset($projects))
				<div class="form-group row @if($errors->has('payment_type')) has-error @endif">
					<label for="payment_type" class="control-label col-sm-2 col-md-2 col-lg-2">طريقة الدفع</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<label><input type="radio" name="payment_type" id="payment_type" value="0" @if(!old("paymen_type")||old("paymen_type")==0) checked @endif> صندوق</label>
						<label><input type="radio" name="payment_type" id="payment_type" value="1" @if(old("paymen_type")==1) checked @endif> قرض</label>
						@if($errors->has('payment_type'))
							@foreach($errors->get('payment_type') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				@else
				<input type="hidden" name="payment_type" value="0">
				@endif
				<div class="col-sm-2 col-md-2 col-lg-2 offset-sm-5 offset-md-5 offset-lg-5">
					<button class="btn btn-primary form-control" id="save_btn">حفظ</button>
				</div>
				<input type="hidden" name="tid" value="@if(isset($tid)){{$tid}}@endif">
				<input type="hidden" name="_token" value="{{csrf_token()}}">
			</form>
			@else
			<div class="alert alert-warning">
				<p><strong>تحذير</strong></p>
				<div>
					@if(count($suppliers)==0)
					<p>لابد من وجود مورديين
					<a href="{{ route('addsupplier') }}" class="btn btn-warning">أضافة مورد</a>
					</p>
					@endif
					@if(count($projects)==0)
					<p>لابد من وجود مشروعات
					<a href="{{ route('addproject') }}" class="btn btn-warning">أضافة مشروع</a>
					</p>
					@endif
					@if(count($store_types)==0)
					<p>لابد من وجود أنواع الخامات
					<a href="{{ route('addstoretype') }}" class="btn btn-warning">أضافة نوع خام</a></p>
					@endif
				</div>
			</div>
			@endif
		</div>
	</div>
</div>
<div id="float_container">
	<div id="float_form_container">
		<span class="close">&times;</span>
		@if(isset($suppliers))
		<h3 class="center">أختار المورد</h3>
		<div id="supplier_container">
	    <div class="category">
	      <h5 class="category">جميع أنواع الموردين</h5>
	    </div>
	    <div>
	    @foreach ($suppliers as $supplier)
	    <div class="supplier_select" data-id="{{$supplier->id}}" data-name="{{$supplier->name}}" data-type="{{str_replace(","," , ",$supplier->type)}}" data-phone="{{str_replace(","," , ",$supplier->phone)}}" data-city="{{$supplier->city}}">
	      <div class="row">
	        <div class="col-2">
	          <img src="{{asset("images/contractor.png")}}" class="w-100" alt="">
	        </div>
	        <div class="col-10">
	          <h4>{{$supplier->name}}</h4>
	          {{str_replace(","," , ",$supplier->phone)}}&nbsp;&nbsp;&nbsp;&nbsp;
	          {{$supplier->city}}&nbsp;&nbsp;&nbsp;&nbsp;
	          ({{str_replace(","," , ",$supplier->type)}})
	        </div>
	      </div>
	    </div>
	    @endforeach
	    </div>
		</div>
	@endif
	</div>
</div>
@endsection
