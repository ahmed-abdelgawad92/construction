@extends('layouts.master')
@section('title','شراء خامات')
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>تعديل كمية أو سعر ال{{$store->type}} المورد من <a href="{{route("showsupplier",['id'=>$store->supplier_id])}}">{{$store->supplier->name}}</a> إلى مشروع <a href="{{route("showproject",['id'=>$store->project_id])}}">{{$store->project->name}}</a></h3>
		</div>
		<div class="panel-body">
			@if (count($errors) > 0)
				<div class="alert alert-danger">
					<strong>خطأ</strong>
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
					<h4>{{ session('info') }}</h4>
				</div>
			@endif
			<form method="post" action="{{ route('updatestore',['id'=>$store->id]) }}" class="form-horizontal" id="add_store">
				<div class="form-group row @if($errors->has('amount')) has-error @endif">
					<label for="amount" class="control-label col-sm-2 col-md-2 col-lg-2">الكمية</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="text" name="amount" id="amount" class="form-control" placeholder="أدخل الكمية" value="{{$store->amount}}">
						@if($errors->has('amount'))
							@foreach($errors->get('amount') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group row @if($errors->has('value')) has-error @endif">
					<label for="value" class="control-label col-sm-2 col-md-2 col-lg-2">القيمة</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="text" name="value" id="value" class="form-control" placeholder="أدخل القيمة" value="{{$store->value}}">
						@if($errors->has('value'))
							@foreach($errors->get('value') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="col-sm-2 col-md-2 col-lg-2 offset-sm-5 offset-md-5 offset-lg-5">
					<button class="btn btn-primary form-control" id="save_btn">حفظ</button>
				</div>
				<input type="hidden" name="_token" value="{{csrf_token()}}">
				@method("PUT")
			</form>
		</div>
	</div>
</div>
@endsection
