@extends('layouts.bugs')
@section('title')
All Tickets
@endsection
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
         <h3>All Tickets</h3>
      </div>
      <div class="panel-body" style="padding: 0 15px;">
         @if(session('insert_error'))
         <div class="alert alert-danger">
            {{session('insert_error')}}
         </div>
         @endif
         @if (count($bugs) > 0)
            @foreach ($bugs as $bug)
               <div class="card">
                  <div class="row no-gutters" style="display: flex; border-bottom: 1px solid #e7e7e7;">
                     <div class="col-md-1 {{str_replace(' ','-',$bug->state)}}"> </div>
                     <div class="col-md-11">
                        <div class="card-body">
                           <a href="{{route("showbug",['bug' => $bug])}}" style="text-decoration: none;">
                              <h5 class="card-title">Ticket #{{$bug->id}} {{$bug->title}}</h5>
                              <p class="card-text">
                                 {{substr($bug->description, 0, 200)}}@if(strlen($bug->description) > 200)...@endif
                              </p>
                              <h4><span class="badge badge-secondary {{str_replace(' ','-',$bug->type)}}">{{$bug->type}}</span></h4>
                              <p class="card-text"><small class="text-muted">{{$bug->created_at}}</small></p>
                           </a>
                        </div>
                     </div>
                  </div>
               </div>
            @endforeach
         @else
            <div class="alert alert-warning">There is no Tickets</div>
         @endif
      </div>
   </div>
</div>
@endsection