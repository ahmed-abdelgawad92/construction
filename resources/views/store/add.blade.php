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
			@if(( (isset($projects)&&count($projects)>0) || isset($project)) && ((isset($suppliers)&&count($suppliers)>0)||isset($supplier)) && count($store_types)>0)
			<form method="post" action="{{ route('addstore') }}" class="form-horizontal">
				<div class="form-group row @if($errors->has('project_id')) has-error @endif">
					<label for="project_id" class="control-label col-sm-2 col-md-2 col-lg-2">أختار مشروع</label>
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
				<div class="form-group row @if($errors->has('type')) has-error @endif" id="store_select_input">
					<label for="type_supplier" class="control-label col-sm-2 col-md-2 col-lg-2">نوع الخام</label>
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
				<div class="form-group row @if($errors->has("new_store_type")) has-error @else hide @endif" id="new_store_type_div">
				 	<label for="new_store_type" class="control-label col-sm-2 col-md-2 col-lg-2">نوع خام جديد *</label>
				 	<div class="col-sm-8 col-md-8 col-lg-8">
				 		<input type="text" name="new_store_type" id="new_store_type" autocomplete="off" class="form-control" placeholder="أدخل نوع خام جديد " value="{{old("new_store_type")}}">
				 		@if($errors->has("new_store_type"))
				 			@foreach($errors->get("new_store_type") as $error)
				 				<span class="help-block">{{ $error }}</span>
				 			@endforeach
				 		@endif
				 	</div>
				</div>
				<div class="form-group row @if($errors->has('amount')) has-error @else hide @endif" id="amount_div">
					<label for="amount" class="control-label col-sm-2 col-md-2 col-lg-2">الكمية</label>
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
				<div class="form-group row @if($errors->has('supplier_id')) has-error @endif">
					<label for="supplier_id_choose" class="control-label col-sm-2 col-md-2 col-lg-2">أختار المقاول المورد</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<select  name="supplier_id" id="supplier_id_choose" class="form-control">
						@if(isset($supplier))
						<option value="{{$supplier->id}}">{{$supplier->name}}</option>
						@else
						<option value="0">أختار المورد</option>
						@foreach($suppliers as $supplier)
						<option value="{{$supplier->id}}"  @if(old('supplier_id')==$supplier->id) selected @endif >{{$supplier->name}}</option>
						@endforeach
						@endif
						</select>
						@foreach($suppliers as $supplier)
						<input type="hidden" name="{{$supplier->id}}" value="{{$supplier->type}}">
						@endforeach
						@if($errors->has('supplier_id'))
							@foreach($errors->get('supplier_id') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group row @if($errors->has('value')) has-error @endif">
					<label for="value" class="control-label col-sm-2 col-md-2 col-lg-2">القيمة</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="text" name="value" id="value" class="form-control" placeholder="أدخل القيمة" value="{{old('value')}}">
						@if($errors->has('value'))
							@foreach($errors->get('value') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group row @if($errors->has('amount_paid')) has-error @endif">
					<label for="amount_paid" class="control-label col-sm-2 col-md-2 col-lg-2">المبلغ المدفوع</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="text" name="amount_paid" id="amount_paid" class="form-control" placeholder="أدخل المبلغ المدفوع" value="{{old('amount_paid')}}">
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
@endsection
