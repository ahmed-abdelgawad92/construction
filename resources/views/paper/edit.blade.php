@extends('layouts.master')
@section('title','تعديل ورقيات بمشروع '.$project->name)
@section('content')
<div class="content">
  <div class="panel panel-default">
   <div class="panel-heading">
     <h3>تعديل الورقية <a href="{{route("showpaper",$paper->id)}}">{{$paper->name}}</a> بمشروع <a href="{{route("showproject",['id'=>$project->id])}}">{{$project->name}}</a></h3>
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
     <form class="form-horizontal" id="add_paper" action="{{route("updatepaper",['id'=>$paper->id])}}" enctype="multipart/form-data" method="post">
       <div class="form-group row @if($errors->has("name")) has-error @endif">
          <label for="name" class="control-label col-sm-2 col-md-2 col-lg-2">أسم الورقية *</label>
          <div class="col-sm-8 col-md-8 col-lg-8">
            <input type="text" name="name" id="name" autocomplete="off" class="form-control" placeholder="أدخل أسم الورقية" value="{{$paper->name}}">
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
            <textarea name="description" id="description" autocomplete="off" class="form-control" placeholder="أدخل وصف الورقية">{{$paper->description}}</textarea>
            @if($errors->has("description"))
              @foreach($errors->get("description") as $error)
                <span class="help-block">{{ $error }}</span>
              @endforeach
            @endif
          </div>
        </div>
        <div class="center">
          <button class="btn btn-primary" id="save_btn">تعديل</button>
         </div>
         @csrf
         @method("PUT")
     </form>
   </div>
  </div>
</div>
@endsection
