@extends('layouts.master')
@section('title','تعديل الأكرامية')
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>تعديل تفاصيل الأكرامية بمشروع <a href="{{ route('showproject',$expense->project->id) }}">{{$expense->project->name}}</a></h3>
		</div>
		<div class="panel-body">
			@if(session('update_error'))
				<div class="alert alert-danger">
					<strong>خطأ</strong>
					<br>
					<ul>
						<li>{{ session('update_error') }}</li>
					</ul>
				</div>
			@endif
			@if(session('info'))
				<div class="alert alert-info">
						<h5>{{ session('info') }}</h5>
				</div>
			@endif
			<form method="post" action="{{ route('updateexpense',$expense->id) }}" id="add_expense" class="form-horizontal">
				<div class="form-group row @if($errors->has('whom')) has-error @endif">
					<label for="whom" class="control-label col-sm-2 col-md-2 col-lg-2">وصف الأكرامية</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="text" name="whom" id="whom" value="{{$expense->whom}}" class="form-control" placeholder="أدخل وصف هذه الأكرامية">
						@if($errors->has('whom'))
							@foreach($errors->get('whom') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group row @if($errors->has('expense')) has-error @endif">
					<label for="expense" class="control-label col-sm-2 col-md-2 col-lg-2">قيمة الأكرامية</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<div class="input-group" id="expense_group">
						<input type="text" name="expense" id="expense" value="{{$expense->expense}}" class="form-control number" placeholder="أدخل قيمة الأكرامية">
						<span class="input-group-addon" id="basic-addon1">جنيه</span>
						</div>
						@if($errors->has('expense'))
							@foreach($errors->get('expense') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="col-sm-2 col-md-2 col-lg-2 offset-sm-5 offset-md-5 offset-lg-5">
					<button class="btn btn-primary form-control" id="save_btn">تعديل</button>
				</div>
				<input type="hidden" name="_token" value="{{csrf_token()}}">
				<input type="hidden" name="_method" value="PUT">
			</form>
		</div>
	</div>
</div>
@endsection
