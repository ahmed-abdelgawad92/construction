@extends('layouts.master')
@section('title','نتائج البحث عن '.$_GET['search'])
@section('content')
<div class="content">
   <div class="panel panel-default">
     <div class="panel-heading">
       <h3>نتائج البحث عن '{{$_GET['search']}}' فى {{$table}}</h3>
     </div>
     <div class="panel-body p-5">
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
     @if (count($records)>0)
       <div class="row">
       @foreach ($records as $record)
         {!!$record->getHtmlTemplate()!!}
       @endforeach
       </div>
       <div class="center mt-5">{!!$records->appends(request()->input())->links()!!}</div>
     @else
       <div class="alert alert-warning mt-5">
         <h3>لا توجد نتائج للبحث عن '{{$_GET['search']}}' فى '{{$table}}'</h3>
       </div>
     @endif
     </div>
  </div>
</div>
@endsection
