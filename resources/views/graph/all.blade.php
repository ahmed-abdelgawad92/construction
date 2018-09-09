@extends('layouts.master')
@section('title','جميع رسومات المشروع')
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>جميع رسومات مشروع <a href="{{ route('showproject',$project->id) }}">{{$project->name}}</a></h3>
		</div>
		<div class="panel-body">
			<h3 style="border:1px solid #eee; border-right: 8px solid #2a3f54; padding: 10px; border-radius: 4px;">جميع الرسومات الأنشائية</h3>
			<div class="row">
			<?php $count=0; ?>
			@foreach($graphs as $graph)
			@if($graph->type==0)
			<?php $count++; ?>
			<div class="col-md-3 col-lg-3 col-xs-6 col-sm-3">
				<a href="{{ route('showgraph',$graph->id) }}">
				<img title="{{$graph->name}}" src="{{ asset('images/pdf.png') }}" style="width: 100%" class="img-responsive icon">
				</a>
				<p class="icon-name">{{$graph->name}}</p>
			</div>
			@if($count%4==0)
			<div class='clearfix visible-lg-block'></div>
			<div class='clearfix visible-md-block'></div>
			<div class='clearfix visible-sm-block'></div>
			@elseif($count%2==0)
			<div class='clearfix visible-xs-block'></div>
			@endif
			@endif
			@endforeach
			</div>
			@if($count==0)
			<div class="alert alert-warning alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				لا يوجد رسومات أنشائية بهذا المشروع <a href="{{ route('addgraphs',$project->id) }}" class="btn btn-warning">أضافة رسم</a>
			</div>
			@endif
			<h3 style="border:1px solid #eee; border-right: 8px solid #2a3f54; padding: 10px; border-radius: 4px;">جميع الرسومات المعمارية</h3>
			<div class="row">
			<?php $count=0; ?>
			@foreach($graphs as $graph)
			@if($graph->type==1)
			<?php $count++; ?>
			<div class="col-md-3 col-lg-3 col-xs-6 col-sm-3">
				<a href="{{ route('showgraph',$graph->id) }}">
				<img title="{{$graph->name}}" src="{{ asset('images/pdf.png') }}" class="img-responsive icon">
				</a>
				<p class="icon-name">{{$graph->name}}</p>
			</div>
			@if($count%4==0)
			<div class='clearfix visible-lg-block'></div>
			<div class='clearfix visible-md-block'></div>
			<div class='clearfix visible-sm-block'></div>
			@elseif($count%2==0)
			<div class='clearfix visible-xs-block'></div>
			@endif
			@endif
			@endforeach
			</div>
			@if($count==0)
			<div class="alert alert-warning alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				لا يوجد رسومات معمارية بهذا المشروع <a href="{{ route('addgraphs',$project->id) }}" class="btn btn-warning">أضافة رسم</a>
			</div>
			@endif
		</div>
	</div>
</div>
@endsection
