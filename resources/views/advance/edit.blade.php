@extends('layouts.master')
@section('title','تعديل سلفة')
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>تعديل سلفة الموظف @if($advance->employee!=null) <a href="{{ route('showadvance',$advance->employee->id) }}">{{$advance->employee->name}}</a> @elseif($advance->company_employee!=null) <a href="{{ route('showcompanyadvance',$advance->company_employee->id) }}">{{$advance->company_employee->name}}</a> @endif </h3>
		</div>
		<div class="panel-body">
			@if(session('update_error'))
			<div class="alert alert-danger">
				{{session('update_error')}}
			</div>
			@endif
			@if(session('success'))
			<div class="alert alert-success">
				{{session('success')}}
			</div>
			@endif
			<form method="post" action="{{ route('updateadvance',$advance->id) }}" class="form-horizontal">
				<div class="form-group row @if($errors->has('advance')) has-error @endif">
					<label for="advance" class="control-label col-sm-2 col-md-2 col-lg-2">السلفة</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<div class="input-group">
						<input type="text" name="advance" id="advance" value="{{$advance->advance}}" class="form-control" placeholder="أدخل قيمة السلفة">
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
					<button class="btn btn-primary form-control" id="save_btn">تعديل</button>
				</div>
				<input type="hidden" name="_method" value="PUT">
				<input type="hidden" name="_token" value="{{csrf_token()}}">
			</form>
		</div>
	</div>
</div>
@endsection
