@extends('layouts.master')
@section('title','أضف نسبة المستخلص')
@section('content')
<div class="content">
	<div class="panel panel-default">
	<div class="panel-heading">
		<h3>أضافة نسبة المقاول</h3>
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
	@if($success)
		<div class="alert alert-success">
			<strong>{{ $success }}</strong>
			<br>
		</div>
	@endif
	<form class="form-horizontal" method="post" action="{{ route('addnonorg',$project_id) }}" id="add_non_organization_payment">
		<div class="form-group row @if($errors->has('non_organization_payment')) has-error @endif">
			<label for="non_organization_payment" class="control-label col-sm-2 col-md-2 col-lg-2">نسبة المقاول</label>
			<div class="col-sm-8 col-md-8 col-lg-8">
				<input type="text" name="non_organization_payment" id="non_organization_payment" value="{{old('non_organization_payment')}}" class="form-control" placeholder="أدخل نسبة المقاول">
				@if($errors->has('non_organization_payment'))
					@foreach($errors->get('non_organization_payment') as $error)
						<span class="help-block">{{ $error }}</span>
					@endforeach
				@endif
			</div>
		</div>
		<div class="col-sm-2 col-md-2 col-lg-2 offset-sm-5 offset-md-5 offset-lg-5">
			<button class="btn btn-primary form-control">حفظ</button>
		</div>

		<input type="hidden" name="_method" value="PUT">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
	</form>
	</div>
</div>
@endsection
