@extends('layouts.master')
@section('title','جميع البنود')
@section('content')
<div class="content">
	@if(session('delete_error'))
	<div class="alert alert-danger">
		<strong>خطأ</strong>
		<br>
		<ul>
			<li>{{ session('delete_error') }}</li>
		</ul>
	</div>
	@endif
	@if(session('success'))
		<div class="alert alert-success">
			<strong>تمت بنجاح</strong>
			<br>
			<ul>
				<li>{{ session('success') }}</li>
			</ul>
		</div>
	@endif
	<div class="panel panel-default">
		<div class="panel-heading">
		@if(Route::current()->getName()=='allterm')
			@if(isset($project))
			<h3>
			جميع بنود المشروع <a href="{{ route('showproject',$project->id) }}">{{$project->name}}</a>
			</h3>
			@else
			<h3>جميع بنود المتعاقد عليها</h3>
			@endif
		@elseif(Route::current()->getName()=='alltermstoaddproduction')
			<h3>أختار البند لكى تضيف أنتاج اليه</h3>
		@elseif(Route::current()->getName()=='alltermstoshowproduction')
			<h3>أختار بند لكى تعرض أجمالى الأنتاج به</h3>
		@elseif(Route::current()->getName()=='termconsumption')
			<h3>أختار بند لكى تعرض جميع الأستهلاك به</h3>
		@elseif(Route::current()->getName()=='showtermtoaddconsumption')
			<h3>أختار بند لكى تضيف أستهلاك اليه</h3>
		@endif
		</div>
		<div class="panel-body">
		@if(Auth::user()->type=='admin')
			@if(count($terms)>0)
			<div class="table-responsive">
			<table class="table table-bordered">
				<thead>
					<tr>
					<th>#</th>
					<th>نوع البند</th>
					<th>كود البند</th>
					<th>بيان الأعمال</th>
					<th>وحدة</th>
					<th>الكمية</th>
					<th>الفئة</th>
					<th>القيمة</th>
					@if(Route::current()->getName()=='allterm')
					<th>حالة التعاقد</th>
					@endif
					</tr>
				</thead>
				<tbody>
				<?php $count=1;?>
				@foreach($terms as $term)
					<tr>
						<td>{{$count++}}</td>
						<td>{{$term->type}}</td>
						@if(Route::current()->getName()=='allterm')
						<th><a href="{{ route('showterm',$term->id) }}">{{$term->code}}</a></th>
						@elseif(Route::current()->getName()=='alltermstoaddproduction')
						<th><a href="{{ route('addproduction',$term->id) }}">{{$term->code}}</a></th>
						@elseif(Route::current()->getName()=='alltermstoshowproduction')
						<th><a href="{{ route('showtermproduction',$term->id) }}">{{$term->code}}</a></th>
						@elseif(Route::current()->getName()=='termconsumption')
						<th><a href="{{ route('showtermconsumption',$term->id) }}">{{$term->code}}</a></th>
						@elseif(Route::current()->getName()=='showtermtoaddconsumption')
						<th><a href="{{ route('addconsumption',$term->id) }}">{{$term->code}}</a></th>
						@endif
						<td>{{$term->statement}}</td>
						<td>{{$term->unit}}</td>
						<td>{{$term->amount}}</td>
						<td>{{$term->value}}</td>
						<td>{{$term->value*$term->amount}}</td>
						@if(Route::current()->getName()=='allterm')
						<td>
							@if($term->contractor_id!=null)
								متعاقد
							@else
								لم يتم التعاقد
							@endif
						</td>
						@endif
					</tr>
				@endforeach
				</tbody>
			</table>
			</div>
			@else
			<div class="alert alert-warning">
				<p>لا يوجد بنود</p>
			</div>
			@endif
		@else
			@if(count($terms)>0)
			<table class="table table-bordered">
				<thead>
					<tr>
					<th>#</th>
					<th>نوع البند</th>
					<th>كود البند</th>
					<th>بيان الأعمال</th>
					<th>وحدة</th>
					<th>الكمية</th>
					</tr>
				</thead>
				<tbody>
				<?php $count=1;?>
				@foreach($terms as $term)
					<tr>
						<td>{{$count++}}</td>
						<td>{{$term->type}}</td>
						<th>{{$term->code}}</th>
						<td><a href="{{ route('showterm',$term->id) }}">{{$term->statement}}</a></td>
						<td>{{$term->unit}}</td>
						<td>{{$term->amount}}</td>
					</tr>
				@endforeach
				</tbody>
			</table>
			@else
			<div class="alert alert-warning">
				<p>لا يوجد بنود</p>
			</div>
			@endif
		@endif
		</div>
	</div>
</div>
@endsection
