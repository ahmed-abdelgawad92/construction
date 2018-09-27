@extends('layouts.master')
@section('title','أضافة سلفة')
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>أضافة سلفة @if(Route::current()->getName()=='addcompanyadvances')
						  لموظف بالشركة
						  @elseif(Route::current()->getName()=='addadvances')
						  لموظف منتدب
						  @endif</h3>
		</div>
		<div class="panel-body">
			@if(session('insert_error'))
			<div class="alert alert-danger">
				{{session('insert_error')}}
			</div>
			@endif
			<form method="post"
					@if(Route::current()->getName()=='addcompanyadvances')
					action="{{ route('addcompanyadvance') }}"
					@elseif(Route::current()->getName()=='addadvances')
					action="{{ route('addadvance') }}"
					@endif
					class="form-horizontal">
				<div class="form-group row @if($errors->has('employee_id')) has-error @endif ">
					<label for="type_employee" class="control-label col-sm-2 col-md-2 col-lg-2">الموظف</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<select name="employee_id" id="employee_id" class="form-control">
							@if(isset($employee))
							<option value="{{$employee->id}}">{{$employee->name}}</option>
							@else
							<option value="">أختار الموظف</option>
							@foreach($employees as $employee)
							<option value="{{$employee->id}}" @if(old('employee_id')==$employee->id) selected @endif >{{$employee->name}}</option>
							@endforeach
							@endif
						</select>
						@if($errors->has('employee_id'))
							@foreach($errors->get('employee_id') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group row @if($errors->has('advance')) has-error @endif">
					<label for="advance" class="control-label col-sm-2 col-md-2 col-lg-2">السلفة</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<div class="input-group">
						<input type="text" name="advance" id="advance" value="{{old('advance')}}" class="form-control" placeholder="أدخل قيمة السلفة">
						<span class="input-group-addon" id="basic-addon1">جنيه</span>
						</div>
						@if($errors->has('advance'))
							@foreach($errors->get('advance') as $error)
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
		</div>
	</div>
</div>
@endsection
