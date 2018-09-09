@extends('layouts.master')
@section('title','بيانات البند')
@section('content')
<div class="content">
	<div class="col-md-8 col-lg-8 col-sm-8 col-sm-offset-2 col-md-offset-0 col-lg-offset-0">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>بند تابع للمشروع <a href="{{route('showproject',$term->project->id)}}">{{$term->project->name}}</a> </h3>
		</div>
		<div class="panel-body">
			@if(session('success'))
				<div class="alert alert-success">
					<strong>{{ session('success') }}</strong>
					<br>
				</div>
			@endif
			<table class="table table-striped">
				<tr><th style="min-width: 100px;">كود البند </th><td>{{$term->code}}</td></tr>
				<tr><th style="min-width: 100px;">نوع البند </th><td>{{$term->type}}</td></tr>
				<tr><th style="min-width: 100px;">بيان الأعمال </th><td>{{$term->statement}}</td></tr>
				<tr><th style="min-width: 100px;">الوحدة </th><td>{{$term->unit}}</td></tr>
				<tr><th style="min-width: 100px;">الكمية </th><td>{{$term->amount}}</td></tr>
				<tr><th style="min-width: 100px;">الفئة </th><td>{{$term->value}}</td></tr>
				<tr><th style="min-width: 100px;">الجملة </th><td>{{$term->amount*$term->value}}</td></tr>
			</table>
			<a href="{{route('termcontract',$term->id)}}" class="float btn btn-primary">عقد البند</a>
			<a href="{{route('addproduction',$term->id)}}" class="float btn btn-primary">أضافة أنتاج</a>
			<a href="{{route('updateterm',$term->id)}}" class="float btn btn-default">تعديل</a>
			<form action="{{route('deleteterm',$term->id)}}" class="float">
				<button type="button" data-toggle="modal" data-target="#delete" class="btn btn-danger">حذف</button>
				<div class="modal fade" id="delete" tabindex="-1" role="dialog">
					<div class="modal-dialog modal-sm">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title">هل تريد حذف هذا البند {{$term->code}}</h4>
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
	<div class="col-md-4 col-lg-4 col-sm-4">
	<div class="panel panel-default">
		<div class="panel-heading project-heading">
			<h3>أخر أنتاج لهذا البند</h3>
		</div>
		<div class="panel-body">
			@if(count($productions)>0)
			@foreach($productions as $production)
				<div class="bordered-right whole">
					<h4>تقييم {{$production->rate}}</h4>
					<p>
						<span class="label label-default">كمية الأنتاج</span>
						 {{$production->amount}}
						@if($production->note!=null)
						<br>
						<span class="label label-default">ملحوظة</span>
						 {{$production->note}}
						@endif
						<br>
						<span class="label label-default">تاريخ الأنتاج</span>
						 {{$production->created_at->format('Y-m-d')}}
					</p>
				</div>
			@endforeach
			<div class="row item" style="text-align: center;">
				<a href="{{ route('showtermproduction',$term->id) }}" class="btn btn-default">
					مجموع الأنتاج
				</a>
			</div>
			@else
				<div class="alert alert-warning">لا يوجد أنتاج <a href="{{ route('addproduction',$term->id) }}" class="btn btn-warning">أضافة أنتاج</a></div>
			@endif
		</div>
	</div>
	</div>
</div>
@endsection
