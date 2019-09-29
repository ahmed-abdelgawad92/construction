@extends('layouts.bugs')
@section('title')
Create a Ticket
@endsection
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
         <h3>Create a Ticket</h3>
      </div>
      <div class="panel-body">
         @if(session('insert_error'))
         <div class="alert alert-danger">
            {{session('insert_error')}}
         </div>
         @endif
         <form action="{{route('addbug')}}" method="POST" enctype="multipart/form-data">
            <div class="form-group @if($errors->has('title')) has-error @endif ">
               <label for="title">Title</label>
               <input id="title" class="form-control" type="text" name="title" value="{{old('title')}}" placeholder="Enter the title of the ticket">
               @if($errors->has('title'))
                  @foreach($errors->get('title') as $error)
                     <span class="help-block">{{ $error }}</span>
                  @endforeach
               @endif
            </div>
            <div class="form-group @if($errors->has('description')) has-error @endif ">
               <label for="description">Description</label>
               <textarea id="description" class="form-control" name="description" placeholder="Enter the description of the ticket" style="height:250px;">{{old('description')}}</textarea>
               @if($errors->has('description'))
                  @foreach($errors->get('description') as $error)
                     <span class="help-block">{{ $error }}</span>
                  @endforeach
               @endif
            </div>
            <div class="form-group @if($errors->has('type')) has-error @endif ">
               <label for="type">Type</label>
               <select id="type" class="form-control" name="type">
                  <option>choose the type of the ticket</option>
                  <option value="0" @if(old('type') === 0) selected @endif>Bug</option>
                  <option value="1" @if(old('type') === 1) selected @endif>New Feature</option>
               </select>
               @if($errors->has('type'))
                  @foreach($errors->get('type') as $error)
                     <span class="help-block">{{ $error }}</span>
                  @endforeach
               @endif
            </div>
            <div class="text-center">
               <button type="submit" class="btn btn-primary">create</button>
            </div>
            @csrf
         </form>
      </div>
   </div>
</div>
@endsection