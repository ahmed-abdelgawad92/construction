@extends('layouts.master')
@section('title','تعديل نوع الخام')
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>تعديل نوع الخام {{$type->name}}</h3>
		</div>
		<div class="panel-body">
			<form method="post" action="{{ route('updatestoretype',$type->id) }}" class="form-horizontal" id="add_store_type">
				<div class="form-group row @if($errors->has('type')) has-error @endif ">
					<label for="type" class="control-label col-sm-2 col-md-2 col-lg-2">نوع الخام</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="text" name="type" id="type" class="form-control" placeholder="أدخل نوع الخام" value="{{$type->name}}">
						@if($errors->has('type'))
							@foreach($errors->get('type') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group row @if($errors->has('unit')) has-error @endif ">
					<label for="unit" class="control-label col-sm-2 col-md-2 col-lg-2">الوحدة</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="text" name="unit" id="unit" class="form-control" placeholder="أدخل الوحدة" value="{{$type->unit}}">
						@if($errors->has('unit'))
							@foreach($errors->get('unit') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="col-sm-2 col-md-2 col-lg-2 offset-sm-5 offset-md-5 offset-lg-5">
					<button class="btn btn-primary form-control" id="save_btn">تعديل</button>
				</div>
				<input type="hidden" name="_method" value="PUT">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
			</form>
		</div>
	</div>
</div>
@endsection
