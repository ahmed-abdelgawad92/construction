@extends('layouts.master')
@section('title','أضافة ورقيات بمشروع '.$project->name)
@section('content')
<div class="content">
  <div class="panel panel-default">
     <div class="panel-heading">
       <h3>أضافة ورقيات بمشروع <a href="{{route("showproject",['id'=>$project->id])}}">{{$project->name}}</a></h3>
     </div>
     <div class="panel-body">
     @if(session("success"))
       <div class="alert alert-success">
         <strong>{{ session("success") }}</strong>
       </div>
     @endif
     @if(session("insert_error"))
       <div class="alert alert-danger">
         <strong>{{ session("insert_error") }}</strong>
       </div>
     @endif
     @if(session("info"))
       <div class="alert alert-info">
         <strong>{{ session("info") }}</strong>
       </div>
     @endif
       <form class="form-horizontal" id="add_paper" action="{{route("addpaper",['id'=>$project->id])}}" enctype="multipart/form-data" method="post">
         <div class="form-group row @if($errors->has("name")) has-error @endif">
            <label for="name" class="control-label col-sm-2 col-md-2 col-lg-2">أسم الورقية *</label>
            <div class="col-sm-8 col-md-8 col-lg-8">
              <input type="text" name="name" id="name" autocomplete="off" class="form-control" placeholder="أدخل أسم الورقية" value="{{old("name")}}">
              @if($errors->has("name"))
                @foreach($errors->get("name") as $error)
                  <span class="help-block">{{ $error }}</span>
                @endforeach
              @endif
            </div>
         </div>
         <div class="form-group row @if($errors->has("description")) has-error @endif">
            <label for="description" class="control-label col-sm-2 col-md-2 col-lg-2">وصف الورقية</label>
            <div class="col-sm-8 col-md-8 col-lg-8">
              <textarea name="description" id="description" autocomplete="off" class="form-control" placeholder="أدخل وصف الورقية">{{old("description")}}</textarea>
              @if($errors->has("description"))
                @foreach($errors->get("description") as $error)
                  <span class="help-block">{{ $error }}</span>
                @endforeach
              @endif
            </div>
          </div>
          <div class="form-group row @if($errors->has('path')) has-error @endif">
   					<label for="path" class="control-label col-sm-2 col-md-2 col-lg-2">أختار ملف الورقية *</label>
   					<div class="col-sm-8 col-md-8 col-lg-8">
   						<div class="input-group" id="path_group">
   						  <input type="text" class="form-control" id="file_name" value="{{old("path")}}"  placeholder="اختار ملف الورقية" aria-describedby="basic-addon2">
   						  <span class="input-group-addon" id="basic-addon2">اختار الملف</span>
   						</div>
   						<input type="file" name="path" id="path" value="{{old('path')}}" ondragleave="drop(event)" ondragover="drag(event)" class="form-control file">
   						@if($errors->has('path'))
   							@foreach($errors->get('path') as $error)
   								<span class="help-block">{{ $error }}</span>
   							@endforeach
   						@endif
   					</div>
   				</div>
          <div class="center">
           	<button class="btn btn-primary" id="save_btn">حفظ</button>
           </div>
           @csrf
       </form>
     </div>
  </div>
</div>
@endsection
