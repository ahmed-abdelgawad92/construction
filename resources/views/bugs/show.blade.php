@extends('layouts.bugs')
@section('title')
Ticket #{{$bug->id}}
@endsection
@section('content')
<div class="content">
   <div class="row">
      <div class="col-md-8">
         <div class="panel panel-default">
            <div class="panel-heading">
               <h3>Ticket #{{$bug->id}} {{$bug->title}} <span class="badge badge-secondary {{str_replace(' ','-',$bug->type)}}">{{$bug->type}}</span></h3>
            </div>
            <div class="panel-body">
               @if(session('insert_error'))
               <div class="alert alert-danger">
                  {{session('insert_error')}}
               </div>
               @endif
               <div class="card-text" style="text-align: justify">{{$bug->description}}</div>
               <br>
               <p><small class="text-muted">{{$bug->created_at}}</small></p><br>
               <form method="POST"
                  @if ($bug->state == 'not started' && auth()->user()->developer == 1)
                     action="{{route('startbug',['bug' => $bug])}}" 
                  @elseif($bug->state == 'started' && auth()->user()->developer == 1)
                     action="{{route('testbug', ['bug' => $bug])}}" 
                  @elseif($bug->state == 'test' && auth()->user()->developer == 0)
                     action="{{route('issuebug', ['bug' => $bug])}}"
                     action="{{route('finishbug', ['bug' => $bug])}}"
                  @elseif($bug->state == 'issues found' && auth()->user()->developer == 1)
                     action="{{route('redevelopbug', ['bug' => $bug])}}"
                  @endif
               >
                  @if (($bug->state == 'not started' || $bug->state == 'started') && auth()->user()->developer == 0)
                     <span class="btn btn-primary">waiting for development to be done</span>
                  @elseif ($bug->state == 'not started' && auth()->user()->developer == 1)
                     <button type="submit" class="btn btn-danger">start</button>
                  @elseif($bug->state == 'started' && auth()->user()->developer == 1)
                     <button type="submit" class="btn btn-success">test</button>
                  @elseif($bug->state == 'test' && auth()->user()->developer == 1)
                     <span class="btn btn-primary">waiting for feedback</span>
                  @elseif($bug->state == 'test' && auth()->user()->developer == 0)
                     <button type="submit" class="btn btn-danger">issues found</button>
                     <button type="submit" class="btn btn-success">close ticket</button>
                  @elseif($bug->state == 'issues found' && auth()->user()->developer == 1)
                     <button type="submit" class="btn btn-danger">start re-developing</button>
                  @endif
                  @csrf 
                  @method('PUT')
               </form>
            </div>
         </div>
      </div>
      <div class="col-md-4">
         <div class="panel panel-default">
            <div class="panel-heading">
               <h4>Attachments</h4>
            </div>
            <div class="panel-body">
            </div>
         </div>
      </div>
   </div>
</div>
@endsection