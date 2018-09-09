@extends('layouts.master')
@section('title','جميع واردات المخازن')
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>جميع واردات المخازن من المقاول <a href="{{ route('showcontractor',$contractor->id) }}">{{$contractor->name}}</a></h3>
		</div>
		<div class="panel-body">
			@if(count($stores)>0)
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>#</th>
							<th>نوع المونة</th>
							<th>كمية</th>
							<th>وحدة</th>
							<th>القيمة</th>
							<th>أجمالى المدفوع</th>
							<th>أجمالى القيمة</th>
							<th>تباع لمشروع</th>
						</tr>
					</thead>
					<tbody>
					<?php $count=1;?>
					@foreach($stores as $store)
						<tr>
							<th>{{$count++}}</th>
							<th>{{$store->type}}</th>
							<th>{{$store->amount}}</th>
							<th>{{$store->unit}}</th>
							<th>{{$store->value}}</th>
							<th>{{$store->amount_paid}}</th>
							<th>{{$store->amount*$store->value}}</th>
							<th><a href="{{ route('showproject',$store->project->id) }}">{{$store->project->name}}</a></th>
						</tr>
					@endforeach
					</tbody>
				</table>
			</div>
			@else
			<div class="alert alert-warning">
				لا يوجد واردات
			</div>
			@endif
		</div>
	</div>
</div>
@endsection
