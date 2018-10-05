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
			<form method="post" action="{{ route('addstore') }}" class="form-horizontal">
				<div class="form-group row @if($errors->has('project_id')) has-error @endif">
					<label for="project_id" class="control-label col-sm-2 col-md-2 col-lg-2">أختار مشروع *</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<select name="project_id" id="project_id" class="form-control">
							@if(isset($project))
							<option value="{{$project->id}}">{{$project->name}} - {{$project->city}} - {{$project->def_num}}</option>
							@else
							<option value="0">أختار مشروع</option>
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
				@if(count($store_types)>0)
				<div class="form-group row @if($errors->has('type')) has-error @endif" id="store_select_input">
					<label for="type_supplier" class="control-label col-sm-2 col-md-2 col-lg-2">نوع الخام *</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<select id="type_supplier" name="type" class="form-control">
						<option value="0">أختار نوع الخام</option>
						@foreach($store_types as $type)
						@if(old('type')==$type->name)
						<option value="{{$type->name}}" selected>{{$type->name}}</option>
						@else
						<option value="{{$type->name}}">{{$type->name}}</option>
						@endif
						@endforeach
						</select>
						@foreach($store_types as $type)
						<input type="hidden" name="store_type[]" value="{{$type->name}}">
						<input type="hidden" name="{{$type->name}}" value="{{$type->unit}}">
						@endforeach
						@if($errors->has('type'))
							@foreach($errors->get('type') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="row mb-3">
					<div class="col-sm-2 col-md-2 col-lg-2"></div>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<a href="#" id="add_new_store_type">أضافة نوع خام جديد</a>
					</div>
				</div>
				@endif
				<div class="form-group row @if($errors->has("new_store_type")||$errors->has("new_store_type_unit")) has-error @elseif(count($store_types)>0) hide @endif" id="new_store_type_div">
				 	<label for="new_store_type" class="control-label col-sm-2 col-md-2 col-lg-2">نوع خام جديد *</label>
				 	<div class="col-sm-8 col-md-8 col-lg-8">
						<div class="w-100">
							<input type="text" @if(count($store_types)<1) name="new_store_type" @endif id="new_store_type" autocomplete="off" class="form-control input-right" placeholder="أدخل نوع خام جديد " value="{{old("new_store_type")}}">
							<input type="text" name="new_store_type_unit" id="new_store_type_unit" autocomplete="off" class="form-control input-left" placeholder="أدخل الوحدة" value="{{old("new_store_type_unit")}}">
						</div>
				 		@if($errors->has("new_store_type"))
				 			@foreach($errors->get("new_store_type") as $error)
				 				<span class="help-block">{{ $error }}</span>
				 			@endforeach
				 		@endif
				 		@if($errors->has("new_store_type_unit"))
				 			@foreach($errors->get("new_store_type_unit") as $error)
				 				<span class="help-block">{{ $error }}</span>
				 			@endforeach
				 		@endif
				 	</div>
				</div>
				<div class="form-group row @if($errors->has('amount')) has-error @else hide @endif" id="amount_div">
					<label for="amount" class="control-label col-sm-2 col-md-2 col-lg-2">الكمية *</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<div class="input-group">
							<input type="text" name="amount" id="amount" value="{{old('amount')}}" class="form-control" placeholder="أدخل الكمية" aria-describedby="basic-addon1">
							<span class="input-group-addon" id="basic-addon1"></span>
						</div>
						@if($errors->has('amount'))
							@foreach($errors->get('amount') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group row @if($errors->has("supplier_id")) has-error @endif">
					 <label for="supplier_id" class="control-label col-sm-2 col-md-2 col-lg-2">أختار المقاول المورد *</label>
					 <div class="col-sm-8 col-md-8 col-lg-8">
						 <div class="input-group">
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
				<div class="form-group row @if($errors->has('value')) has-error @endif">
					<label for="value" class="control-label col-sm-2 col-md-2 col-lg-2">قيمة الوحدة *</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<div class="input-group">
							<input type="text" name="value" id="value" class="form-control" placeholder="أدخل قيمة الوحدة" value="{{old('value')}}">
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
						<div class="input-group">
							<input type="text" name="amount_paid" id="amount_paid" class="form-control" placeholder="أدخل المبلغ المدفوع" value="{{old('amount_paid')}}">
							<span class="input-group-addon" id="basic-addon1">جنيه</span>
						</div>
						@if($errors->has('amount_paid'))
							@foreach($errors->get('amount_paid') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
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
	</div>
</div>
@endsection
