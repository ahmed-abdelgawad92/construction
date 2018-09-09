@extends('layouts.master')
@section('title')
@if(Route::current()->getName()=='showtermconsumption')
جميع أستهلاك البند
@elseif(Route::current()->getName()=='showprojectconsumption')
جميع أستهلاك المشروع
@endif
@endsection
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>
				@if(Route::current()->getName()=='showtermconsumption')
				جميع أستهلاك البند <a href="{{ route('showterm',$term->id) }}">{{ $term->code }}</a> بمشروع <a href="{{ route('showproject',$term->project->id) }}">{{ $term->project->name }}</a>
				@elseif(Route::current()->getName()=='showprojectconsumption')
				جميع أستهلاك المشروع <a href="{{ route('showproject',$project->id) }}">{{ $project->name }}</a>
				@endif
			</h3>
		</div>
		<div class="panel-body">
			@if(count($consumptions)>0)
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>#</th>
							<th>نوع الخام</th>
							<th>الكمية المستهلكة</th>
							<th>الوحدة</th>
						</tr>
					</thead>
					<tbody>
						<?php $count=1; ?>
						@foreach($consumptions as $consumption)
						<tr>
							<th>{{$count++}}</th>
							<th><a href="
							@if(Route::current()->getName()=='showtermconsumption')
							{{ route('showtermconsumedraw',['id'=>$term->id,'type'=>$consumption->type]) }}
							@elseif(Route::current()->getName()=='showprojectconsumption')
							{{ route('showprojectconsumedraw',['id'=>$project->id,'type'=>$consumption->type]) }}
							@endif	
							">{{$consumption->type}}</a></th>
							<th>{{$consumption->amount}}</th>
							@foreach($store_types as $type)
							@if($type->name==$consumption->type)
							<th>{{$type->unit}}</th>
							@endif
							@endforeach
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			@if(Route::current()->getName()=='showtermconsumption')
			<a href="{{ route('addconsumption',$term->id) }}" class="btn btn-primary">أضافة أستهلاك</a>
			@endif
			@else
			<div class="alert alert-warning">
				@if(Route::current()->getName()=='showtermconsumption')
				لا يوجد أستهلاك فى هذا البند	
				@elseif(Route::current()->getName()=='showprojectconsumption')
				لا يوجد أستهلاك فى هذا المشروع
				@endif
			</div>
			@endif
		</div>
	</div>
</div>
@endsection