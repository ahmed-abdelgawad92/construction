@extends('layouts.master')
@section('title','جميع الملحوظات')
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>
        جميع الملاحظات <a href="{{route('showterm',['id'=>$term->id])}}">بالبند {{$term->code}}</a> بمشروع <a href="{{route('showproject',['id'=>$term->project_id])}}">{{$term->project->name}}</a>
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
      <div class="row my-5">
        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 my-5">
          <img src="{{asset('images/plus.png')}}" class="w-100 open_float_div" href="#add_note" id="plus_img">
        </div>
        @foreach ($notes as $note)
        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 my-5">
  				<div class="note">
  					<h3 class="center">{{$note->title}}</h3>
  					{!!nl2br(htmlspecialchars($note->note))!!}
  					<div class="note-time">{{date("d/m/Y",strtotime($note->created_at))}}</div>
  					<div class="note_control">
  						<a href="{{route("deletenote",['id'=>$note->id])}}" class="note_delete"><span class="glyphicon glyphicon-trash"></span></a>
  						<a href="{{route("updatenote",['id'=>$note->id])}}" class="note_update"><span class="glyphicon glyphicon-edit"></span></a>
  					</div>
  				</div>
  			</div>
        @endforeach
      </div>
    </div>
  </div>
</div>
<div id="float_container">
	<div id="float_form_container">
		<span class="close">&times;</span>
		<form action="{{route('addnote',['id'=>$term->id])}}" method="post" class="float_form" id="add_note">
			<div class="form-group @if($errors->has('title')) has-error @endif">
				<label for="title" class="control-label">عنوان</label>
				<div>
					<input type="text" name="title" id="title" class="form-control" placeholder="أدخل العنوان" value="{{old('title')}}"/>
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
					<textarea name="note" id="note" class="form-control note" placeholder="أكتب ملحوظة">{{old('note')}}</textarea>
					@if($errors->has('note'))
						@foreach($errors->get('note') as $error)
							<span class="help-block">{{ $error }}</span>
						@endforeach
					@endif
				</div>
			</div>
			<button class="btn btn-primary form-control" id="save_btn">حفظ</button>
			<input type="hidden" name="_token" value="{{csrf_token()}}">
		</form>
		<div class="float_form" id="delete_note">
			<h4>هل تريد فعلا حذف هذه الملحوظة؟</h4>
			<button class="btn btn-default btn-close">لا</button>
			<a href="" class="btn btn-danger">نعم</a>
		</div>
	</div>
</div>
@endsection
