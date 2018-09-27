@extends('layouts.master')
@section('title','تعديل الملحوظة')
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>
        تعديل ملاحظة <a href="{{route('showterm',['id'=>$term->id])}}">بالبند {{$term->code}}</a> بمشروع <a href="{{route('showproject',['id'=>$term->project_id])}}">{{$term->project->name}}</a>
      </h3>
		</div>
		<div class="panel-body pb-5">
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
			@if(session('info'))
				<div class="alert alert-info">
					<strong>{{ session('info') }}</strong>
					<br>
				</div>
			@endif
      <form action="{{route('updatenote',['id'=>$note->id])}}" method="post" id="add_note">
  			<div class="form-group @if($errors->has('title')) has-error @endif">
  				<label for="title" class="control-label">عنوان</label>
  				<div>
  					<input type="text" name="title" id="title" class="form-control" placeholder="أدخل العنوان" value="{{$note->title}}"/>
  					@if($errors->has('title'))
  						@foreach($errors->get('title') as $error)
  							<span class="help-block">{{ $error }}</span>
  						@endforeach
  					@endif
  				</div>
  			</div>
  			<div class="form-group @if($errors->has('note')) has-error @endif">
  				<label for="note" class="control-label">ملحوظة</label>
  				<div>
  					<textarea name="note" id="note" class="form-control note" placeholder="أكتب ملحوظة">{{$note->note}}</textarea>
  					@if($errors->has('note'))
  						@foreach($errors->get('note') as $error)
  							<span class="help-block">{{ $error }}</span>
  						@endforeach
  					@endif
  				</div>
  			</div>
        <div class="center">
          <button class="btn btn-default" id="save_btn">نعديل</button>
        </div>
  			<input type="hidden" name="_token" value="{{csrf_token()}}">
        @method('PUT')
  		</form>
    </div>
  </div>
</div>
@endsection
