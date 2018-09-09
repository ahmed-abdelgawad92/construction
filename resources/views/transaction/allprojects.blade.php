@extends('layouts.master')
@section('title','أختار مشروع')
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
		<h3>أختار مشروع
		@if(Route::current()->getName()=='chooseprojectextractor')
		لأنشاء مستخلص
		@elseif(Route::current()->getName()=='chooseprojecttransaction')
		لأظهار أجمالى المعاملات و تفاصيلها
		@endif
		</h3>
		</div>
		<div class="panel-body">
			@if(count($projects)>0)
			@foreach($projects as $project)
			<a href="
			@if(Route::current()->getName()=='chooseprojectextractor')
			{{ route('createextractor',$project->id) }}
			@elseif(Route::current()->getName()=='chooseprojecttransaction')
			{{ route('alltransaction',$project->id) }}
			@endif
			 " class="list-hover">
				<div class="row item">

				<div class="col-md-2 col-lg-2 col-sm-2 col-xs-2">
					<img src="{{ asset('images/project2.png') }}" alt="" class="img-responsive">
				</div>
				<div class="col-md-10  col-lg-10  col-sm-10 col-xs-10">
					<h4>مشروع {{$project->name}}</h4>
					<p>العنوان : {{$project->address}} , {{$project->center}} , {{$project->city}} </p>
				</div>
			</div>
			</a>

			@endforeach
			@else
			<div class="alert alert-warning">لا يوجد مشروعات حالية</div>
			@endif
		</div>
	</div>
</div>
@endsection
