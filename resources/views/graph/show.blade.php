@extends('layouts.master')
@section('title','عرض الرسم')
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading navy-heading">
			<h3>عرض الرسم</h3>
		</div>
		<div class="panel-body">
			@if(session('success'))
				<div class="alert alert-success">
					{{session('success')}}
				</div>
			@endif
			<div class="well" style="overflow: auto;">
					<div style="font-size: 20px"><span class="label label-primary title">أسم المشروع</span> <a href="{{ route('showproject',$graph->project->id) }}">{{$graph->project->name}}</a> <a class="mr-5" href="{{ route('allgraph',$graph->project->id) }}">جميع رسومات المشروع</a></div>
					<div style="font-size: 20px"><span class="label label-primary title">أسم الرسم</span> {{$graph->name}}</div>
					<div style="font-size: 20px"><span class="label label-primary title">نوع الرسم</span> @if($graph->type==0) رسم أنشائى @else رسم معمارى @endif </div>
				<a href="{{ route('showPdf',$graph->path) }}" target="_blank" class="btn btn-primary float">أفتح الملف</a>
				<a href="{{ route('updategraph',$graph->id) }}" class="btn btn-default float">تعديل</a>
				<form class="float" method="post" action="{{ route('deletegraph',$graph->id) }}">
				<button class="btn btn-danger" type="button" data-toggle="modal" data-target="#delete">حذف
				</button>
				<div class="modal fade" id="delete" tabindex="-1" role="dialog">
					<div class="modal-dialog modal-sm">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title">هل تريد حذف هذا الرسم؟</h4>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">لا
								</button>
								<button class="btn btn-danger">نعم</button>
							</div>
						</div>
					</div>
				</div>
				<input type="hidden" name="_method" value="DELETE">
				<input type="hidden" name="_token" value="{{csrf_token()}}">
				</form>
			</div>
			<div class="embed-responsive embed-responsive-4by3">
			<iframe class="embed-responsive-item" src="{{ route('showPdf',$graph->path) }}"></iframe>
			</div>
		</div>
	</div>
</div>
@endsection
