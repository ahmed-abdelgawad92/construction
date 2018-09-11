@extends('layouts.master')
@section('title','بيانات العميل')
@section('content')
<div class="content">
<div class="row">
	<div class="col-md-8 col-lg-8 col-sm-8 col-sm-offset-2 col-md-offset-0 col-lg-offset-0">
	<div class="panel panel-default">
		<div class="panel-heading navy-heading">
			<h4>العميل {{$org->name}}</h4>
		</div>
		<div class="panel-body">
			@if(session('success'))
				<div class="alert alert-success">
					<strong>{{ session('success') }}</strong>
					<br>
				</div>
			@endif
			<p>الشارع : {{$org->address}}</p>
			<p>المركز : {{$org->center}}</p>
			<p>المدينة : {{$org->city}}</p>
			@php
				$phones = explode(";",$org->phone);
			@endphp
			@foreach ($phones as $phone)
			<p>التليفون : {{$phone}}</p>
			@endforeach
			<p>نوع العميل : @if($org->type==0)عميل @else مقاول @endif</p>
			<a href="{{ url('project/add',$org->id) }}" class="btn float btn-navy">أضافة مشروع</a>
			<a href="{{ route('updateorganization',$org->id) }}" class="btn width-100 float btn-default">تعديل</a>
			<form class="float" method="post" action="{{ route('deleteorganization',$org->id) }}">
				<button type="button" data-toggle="modal" data-target="#delete" class="btn width-100 btn-danger">حذف</button>
				<div class="modal fade" id="delete" tabindex="-1" role="dialog">
					<div class="modal-dialog modal-sm">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title">هل تريد حذف العميل {{$org->name}}</h4>
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
	<div class="col-md-4 col-lg-4 col-sm-8 col-sm-offset-2 col-lg-offset-0 col-md-offset-0">
	<div class="panel panel-default">
		<div class="panel-heading project-heading">
			<h4>المشروعات الحالية</h4>
		</div>
		<div class="panel-body">
			@if (count($current_projects)>0)
				@foreach ($current_projects as $proj)
					<div class="row item">
						<div class="col-md-4 col-lg-4 col-sm-4 col-xs-4">
							<a href="{{route('showproject',['id'=>$proj->id])}}"><img src="{{ asset('images/project_img.PNG') }}" alt="" class="img-responsive img-rounded"></a>
						</div>
						<div class="col-md-8  col-lg-8  col-sm-8 col-xs-8">
							<h3>{{$proj->name}}</h3>
							<p>الرقم التعريفي : {{$proj->def_num}}</p>
							<p>المدينة : {{$proj->city}}</p>
						</div>
						<div class="col-xs-12 mt-4">
							<a href="{{route('showproject',['id'=>$proj->id])}}" class="btn btn-default btn-project">أفتح</a>
							@if ($proj->started_at==null)
							<a href="" class="btn btn-default btn-project">ابدأ التنفيذ</a>
							@else
							<a href="" class="btn btn-default btn-success">إنهاء التنفيذ</a>
							@endif
						</div>
					</div>
				@endforeach
			@else
				<div class="alert alert-warning">لا يوجد مشروعات حالية لهذا العميل<br> <a href="{{ url('project/add',$org->id) }}" class="btn btn-warning">أضافة مشروع</a></div>
			@endif
		</div>
	</div>
	</div>
</div>
</div>
	@if(count($projects)>0)
	<div class="row">
		<h3 class="center">جميع مشروعات العميل {{$org->name}}</h3>
		<?php $count=0; ?>
		@foreach($projects as $project)
		<?php $count++; ?>
		<div class="col-sm-6 col-md-4 col-lg-3">
			<div class="thumbnail">
				<a href="{{ route('showproject',$project->id) }}"><img src="{{ asset('images/construction.jpg') }}" class="w-100" alt=""></a>
				<div class="caption center">
					<h4 class="center">{{$project->name}}</h4>
					<p class="center"><strong>المدينة :</strong> {{$project->city}}</p>
					<a href="{{ route('showproject',$project->id) }}" class="btn btn-default">أفتح</a>
				</div>
			</div>
		</div>
		@if($count%2==0)
		<div class='clearfix visible-sm-block'></div>
		@elseif($count%3==0)
		<div class='clearfix visible-md-block'></div>
		@elseif($count%4==0)
		<div class='clearfix visible-lg-block'></div>
		@endif
		@endforeach
	</div>
	@else
	<h3 class="center">جميع مشروعات العميل {{$org->name}}</h3>
	<div class="alert alert-warning">لا يوجد مشروعات لهذا العميل<br> <a href="{{ url('project/add',$org->id) }}" class="btn btn-warning">أضافة مشروع</a></div>
	@endif
@endsection
