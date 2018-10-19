@extends('layouts.master')
@section('title','ورقية '.$paper->name)
@section('content')
<div class="content">
   <div class="panel panel-default">
     <div class="panel-heading">
       <h3 class="overflow-hidden">
         الورقية {{$paper->name}} بمشروع <a href="{{route("showproject",['id'=>$project->id])}}">{{$project->name}}</a>
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
       @php
         $arr = explode(".",$paper->path);
         $ext = strtolower(array_pop($arr));
       @endphp
       @if($ext=="pdf")
       <div class="embed-responsive embed-responsive-4by3">
         <iframe class="embed-responsive-item" src="{{ route('showPaperPdf',['fileName'=>$paper->path,'ext'=>$ext]) }}"></iframe>
       </div>
       @else
       <div class="center">
         <img src="{{ route('showPaperPdf',['fileName'=>$paper->path,'ext'=>$ext]) }}" style="max-width:100%" alt="">
       </div>
       @endif
       <div class="center">
       @if (!empty($paper->description))
         <div class="description">❞{{$paper->description}}❝</div>
       @endif
         <br>
         <a href="{{route("allpaper",$project->id)}}" class="btn btn-primary">جميع ورقيات المشروع</a>
         <a href="{{route("updatepaper",$paper->id)}}" class="btn btn-default">تعديل</a>
         <form method="post" action="{{ route('deletepaper',$paper->id) }}" class="inline">
   				<button type="button" data-toggle="modal" data-target="#delete" class="btn width-100 btn-danger">حذف</button>
   				<div class="modal fade" id="delete" tabindex="-1" role="dialog">
   					<div class="modal-dialog modal-sm">
   						<div class="modal-content">
   							<div class="modal-header">
   								<h4 class="modal-title">هل تريد حذف الورقية {{$paper->name}}؟</h4>
   							</div>
   							<div class="modal-footer">
   								<button type="button" class="btn btn-default" data-dismiss="modal">لا
   								</button>
   								<button class="btn btn-danger">نعم</button>
   							</div>
   						</div>
   					</div>
   				</div>
   				<input type="hidden" name="_token" value="{{csrf_token()}}">
   				<input type="hidden" name="_method" value="DELETE">
   			</form>
       </div>
     </div>
  </div>
</div>
@endsection
