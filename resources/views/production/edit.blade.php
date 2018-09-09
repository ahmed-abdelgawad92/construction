@extends('layouts.master')
@section('title','تعديل أنتاج')
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>تعديل أنتاج للبند <a href="{{ route('showterm',$production->term_id) }}">{{$production->term->code}}</a> بمشروع <a href="{{ route('showproject',$production->term->project->id) }}">{{$production->term->project->name}}</a></h3>
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
			<form method="post" action="{{ route('updateproduction',$production->id) }}" class="form-horizontal">
				<div class="form-group @if($errors->has('amount')) has-error @endif">
					<label for="amount" class="control-label col-sm-2 col-md-2 col-lg-2">الكمية</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<div class="input-group">
							<input type="text" name="amount" id="amount" value="{{$production->amount}}" class="form-control" placeholder="أدخل الكمية" aria-describedby="basic-addon1">
							<span class="input-group-addon" id="basic-addon1">{{$production->term->unit}}</span>
						</div>
						@if($errors->has('amount'))
							@foreach($errors->get('amount') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group @if($errors->has('rate')) has-error @endif">
					<label for="rate" class="control-label col-sm-2 col-md-2 col-lg-2">تقييم الأنتاج</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<select name="rate" id="rate_prod" class="form-control">
							<option value="0">أدخل تقييم الأنتاج	</option>
							<option value="1" @if($production->rate==1) selected @endif >1</option>
							<option value="2" @if($production->rate==2) selected @endif >2</option>
							<option value="3" @if($production->rate==3) selected @endif >3</option>
							<option value="4" @if($production->rate==4) selected @endif >4</option>
							<option value="5" @if($production->rate==5) selected @endif >5</option>
							<option value="6" @if($production->rate==6) selected @endif >6</option>
							<option value="7" @if($production->rate==7) selected @endif >7</option>
							<option value="8" @if($production->rate==8) selected @endif >8</option>
							<option value="9" @if($production->rate==9) selected @endif >9</option>
							<option value="10" @if($production->rate==10) selected @endif >10</option>
						</select>
						@if($errors->has('rate'))
							@foreach($errors->get('rate') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group @if($production->rate<8) display @endif @if($errors->has('note')) has-error @endif " id="proNote">
					<label for="note" class="control-label col-sm-2 col-md-2 col-lg-2">ملحوظة</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<textarea name="note" id="note" class="form-control" placeholder="أكتب ملحوظة توضح لماذا تعطيه هذا التقييم السئ">{{$production->note}}</textarea>
						@if($errors->has('note'))
							@foreach($errors->get('note') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="col-sm-2 col-md-2 col-lg-2 col-sm-offset-5 col-md-offset-5 col-lg-offset-5">
					<button class="btn btn-primary form-control" id="save_btn">تعديل</button>
				</div>
				<input type="hidden" name="_token" value="{{csrf_token()}}">
				<input type="hidden" name="_method" value="PUT">
			</form>
		</div>
	</div>
</div>
@endsection
