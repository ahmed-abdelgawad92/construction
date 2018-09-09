@extends('layouts.master')
@section('title')
@if(Route::current()->getName()=='showtermconsumedraw')
أجمالى أستهلاك ال{{$type}} بالبند
@elseif(Route::current()->getName()=='showprojectconsumedraw')
أجمالى أستهلاك ال{{$type}} بالمشروع
@endif
@endsection
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>
				@if(Route::current()->getName()=='showtermconsumedraw')
				أجمالى أستهلاك ال{{$type}} بالبند <a href="{{ route('showterm',$term->id) }}">{{ $term->code }}</a> بمشروع <a href="{{ route('showproject',$term->project->id) }}">{{ $term->project->name }}</a>
				@elseif(Route::current()->getName()=='showprojectconsumedraw')
				أجمالى أستهلاك ال{{$type}} بالمشروع <a href="{{ route('showproject',$project->id) }}">{{ $project->name }}</a>
				@endif
			</h3>
		</div>
		<div class="panel-body">
			@if(session('success'))
				<div class="alert alert-success">
					{{session('success')}}
				</div>
			@endif
			@if(count($consumptions)>0)
			<div class="jumbotron">
				<h2 style="border-bottom: 1px solid #000; padding-bottom: 5px;">أجمالى أستهلاك ال{{$type}}</h2>
				<br><br>
				<div class="row">
					<div class="col-sm-6 col-md-4 col-lg-4 col-xs-6" style="margin-bottom: 10px;">
					</div>
					<div class="col-sm-12 col-md-4 col-lg-4 col-xs-12" style="margin-bottom: 10px;">
						<div class="circle-div">
							{{ $total_amount }}
						</div>
						<p style="text-align: center; margin-top: 8px;">أجمالى الأستهلاك</p>
					</div>
					<div class="col-sm-6 col-md-4 col-lg-4 col-xs-6" style="margin-bottom: 10px;">
					</div>
				</div>
			</div>
			<h4 style="border-bottom: 1px solid #eee; padding-bottom: 5px;">تفاصيل الأستهلاك</h4>
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>#</th>
							@if(Route::current()->getName()=='showprojectconsumedraw')
							<th>كود البند</th>
							@endif
							<th>نوع الخام</th>
							<th>الكمية المستهلكة</th>
							<th>الوحدة</th>
							<th>تاريخ</th>
							@if(Route::current()->getName()=='showtermconsumedraw')
							<th style="max-width: 100px;">تعديل</th>
							<th>حذف</th>
							@endif
						</tr>
					</thead>
					<tbody>
						<?php $count=1; ?>
						@foreach($consumptions as $consumption)
						<tr>
							<th>{{$count++}}</th>
							@if(Route::current()->getName()=='showprojectconsumedraw')
							<th><a href="{{ route('showterm',$consumption->term_id) }}">{{$consumption->term->code}}</a></th>
							@endif
							<th>{{$consumption->type}}</th>
							<th>{{$consumption->amount}}</th>
							@foreach($store_types as $type)
							@if($type->name==$consumption->type)
							<th>{{$type->unit}}</th>
							@endif
							@endforeach
							<th>{{$consumption->created_at->format('Y-m-d')}}</th>
							@if(Route::current()->getName()=='showtermconsumedraw')
							<th><a href="{{ route('updateconsumption',$consumption->id) }}" class="btn btn-default btn-block">تعديل</a></th>
							<th>
								<form method="post" action="{{ route('deleteconsumption',$consumption->id) }}">
									<input type="hidden" name="_method" value="DELETE">
									<button type="button" data-toggle="modal" data-target="#delete{{$consumption->id}}" class="btn m-top btn-block btn-danger">حذف</button>
								<div class="modal fade" id="delete{{$consumption->id}}" tabindex="-1" role="dialog">
									<div class="modal-dialog modal-sm">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title">هل تريد حذف كمية الأستهلاك؟</h4>
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
								</form>
							</th>
							@endif
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			@if(Route::current()->getName()=='showtermconsumedraw')
			<a href="{{ route('addconsumption',$term->id) }}" class="btn btn-primary">أضافة أستهلاك</a>
			@endif
			@else
			<div class="alert alert-warning">
				@if(Route::current()->getName()=='showtermconsumedraw')
				لا يوجد أستهلاك لل{{$type}} فى هذا البند	
				@elseif(Route::current()->getName()=='showprojectconsumedraw')
				لا يوجد أستهلاك لل{{$type}} فى هذا المشروع
				@endif
			</div>
			@endif
		</div>
	</div>
</div>
@endsection