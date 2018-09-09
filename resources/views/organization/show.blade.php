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
			<p>التليفون : {{$org->phone}}</p>
			<p>نوع العميل : @if($org->type==0)عميل @else مقاول @endif</p>
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
			<div class="row item">
				<div class="col-md-4 col-lg-4 col-sm-4 col-xs-4">
					<img src="{{ asset('images/project2.png') }}" alt="" class="img-responsive">
				</div>
				<div class="col-md-8  col-lg-8  col-sm-8 col-xs-8">
					<h4>مشروعات هذه الهيئة مشروعات هذه الهيئة مشروعات هذه الهيئة</h4>
					<p>مشروعات هذه الهيئة مشروعات هذه الهيئة مشروعات هذه الهيئة</p>
					<a href="" class="btn btn-default btn-project">أفتح</a>
				</div>
			</div>
			<div class="row item">
				<div class="col-md-4 col-lg-4 col-sm-4 col-xs-4">
					<img src="{{ asset('images/project2.png') }}" alt="" class="img-responsive">
				</div>
				<div class="col-md-8  col-lg-8  col-sm-8 col-xs-8">
					<h4>مشروعات هذه الهيئة مشروعات هذه الهيئة مشروعات هذه الهيئة</h4>
					<p>مشروعات هذه الهيئة مشروعات هذه الهيئة مشروعات هذه الهيئة</p>
					<a href="" class="btn btn-default btn-project">أفتح</a>
				</div>
			</div>
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
				<img src="{{ asset('images/construction.jpg') }}" alt="">
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
	<a href="{{ url('project/add',$org->id) }}" class="btn btn-primary">أضافة مشروع</a>
	@else
	<h3 class="center">جميع مشروعات العميل {{$org->name}}</h3>
	<div class="alert alert-warning">لا يوجد مشروعات لهذا العميل<br> <a href="{{ url('project/add',$org->id) }}" class="btn btn-primary">أضافة مشروع</a></div>
	@endif
@endsection
