@extends('layouts.master')
@section('title','جميع الورقيات بمشروع '.$project->name)
@section('content')
<div class="content">
   <div class="panel panel-default">
     <div class="panel-heading">
       <h3 class="overflow-hidden">جميع الورقيات بمشروع <a href="{{route("showproject",['id'=>$project->id])}}">{{$project->name}}</a>
         <a href="{{route("addpaper",['id'=>$project->id])}}" class="btn btn-primary left">أضافة ورقيات</a>
       </h3>
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
     @if (count($papers)>0)
       <div class="row">
         @foreach ($papers as $paper)
         <div class="col-12 col-sm-4 col-md-3 col-lg-2">
           <div style="position:relative;">
             <a href="{{route("showpaper",['id'=>$paper->id])}}" title="{{$paper->description}}" data-toggle="tooltip" data-placement="bottom">
               <img src="{{asset("images/file.png")}}" class="w-100" alt="{{$paper->name}}" alt="{{$paper->name}}">
               @php
               $arr=explode('.',$paper->path);
               @endphp
             </a>
             <span class="file_type">{{array_pop($arr)}}</span>
             <h4 class="center">{{$paper->name}}</h4>
           </div>
         </div>
         @endforeach
       </div>
     @else
       <div class="alert alert-warning">لا يوجد ورقيات بهذا المشروع <a href="{{route("addpaper",['id'=>$project->id])}}" class="btn btn-warning">أضافة ورقية</a></div>
     @endif
     </div>
  </div>
</div>
@endsection
