@extends('layouts.master')
@section('title','عقد بند')
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>عقد بند ({{ $term->code }})</h3>
		</div>
		<div class="panel-body">
			@if(session('success'))
				<div class="alert alert-success">
					<strong>{{ session('success') }}</strong>
					<br>
				</div>
			@endif
			@if(session('insert_error'))
				<div class="alert alert-danger">
					<strong>{{ session('insert_error') }}</strong>
					<br>
				</div>
			@endif
			@if(count($contractors)>0)
			<form method="post" action="{{route('termcontract',$term->id)}}" class="form-horizontal">
				<div class="form-group @if($errors->has('contractor_id')) has-error @endif">
					<label for="contractor_id" class="control-label col-sm-2 col-md-2 col-lg-2">نص العقد</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<select name="contractor_id" id="contractor_id" class="form-control">
							<option value="">أختار مقاول</option>
							@foreach($contractors as $contractor)
							<option value="{{$contractor->id}}">{{$contractor->name}}</option>
							@endforeach
						</select>
						@if($errors->has('contractor_id'))
							@foreach($errors->get('contractor_id') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group @if($errors->has('contractor_unit_price')) has-error @endif">
					<label for="contractor_unit_price" class="control-label col-sm-2 col-md-2 col-lg-2">سعر الوحدة للمقاول</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="text" name="contractor_unit_price" id="contractor_unit_price" class="form-control" placeholder="أدخل سعر الوحدة للمقاول">
						@if($errors->has('contractor_unit_price'))
							@foreach($errors->get('contractor_unit_price') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group @if($errors->has('contract_text')) has-error @endif">
					<label for="contract_text" class="control-label col-sm-2 col-md-2 col-lg-2">نص العقد</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<textarea name="contract_text" id="contract_text" class="text form-control" placeholder="أدخل نص العقد"></textarea>
						@if($errors->has('contract_text'))
							@foreach($errors->get('contract_text') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group @if($errors->has('started_at')) has-error @endif">
					<label for="started_at" class="control-label col-sm-2 col-md-2 col-lg-2">تاريخ بداية تنفيذ البند</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="text" name="started_at" id="started_at" value="{{old('started_at')}}" class="form-control" placeholder="أدخل تاريخ بداية تنفيذ البند">
						@if($errors->has('started_at'))
							@foreach($errors->get('started_at') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="col-sm-2 col-md-2 col-lg-2 col-sm-offset-5 col-md-offset-5 col-lg-offset-5">
					<button class="btn btn-primary form-control" id="save_btn">حفظ</button>
				</div>
				<input type="hidden" name="_method" value="PUT">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
			</form>
			@else
			<div class="aler alert-warning">
				<div style="padding:10px;">لا يوجد مقاوليين يحملون نفس نوع البند
				<a href="{{ route('addcontractor') }}" class="btn btn-warning">أضافة مقاول</a>
				</div>
			</div>
			@endif
		</div>
	</div>
</div>
@endsection
