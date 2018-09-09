@extends('layouts.master')
@section('title','أنواع الخامات')
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>جميع أنواع الخامات</h3>
		</div>
		<div class="panel-body">
			@if(session('delete_error'))
			<div class="alert alert-danger">
				{{session('delete_error')}}
			</div>
			@endif
			@if(session('success'))
			<div class="alert alert-success">
				{{session('success')}}
			</div>
			@endif
			@if(count($store_types)>0)
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>#</th>
							<th>نوع الخام</th>
							<th>الوحدة</th>
							<th>تعديل</th>
							<th style="width: 100px !important">حذف</th>
						</tr>
					</thead>
					<tbody>
						<?php $count=1; ?>
						@foreach($store_types as $type)
						<tr>
							<td>{{$count++}}</td>
							<td>{{$type->name}}</td>
							<td>{{$type->unit}}</td>
							<td style="width: 100px !important">
								<a href="{{ route('updatestoretype',$type->id) }}" class="btn btn-default btn-block">
									تعديل
								</a>
							</td>
							<td style="width: 100px !important">
							<form method="post" action="{{ route('deletestoretype',$type->id) }}">
								<button type="button" data-toggle="modal" data-target="#con{{$type->id}}" class="btn btn-danger btn-block">
								حذف
								</button>
								<div class="modal fade" id="con{{$type->id}}" tabindex="-1" role="dialog" aria-labelledby="con-title" style="white-space: normal !important">
									<div class="modal-dialog" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title" id="con-title">هل تريد حذف النوع {{$type->name}}</h4>
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
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			@else
			<div class="alert alert-warning" style="padding:10px;">
				لا يوجد أنواع للخامات , يرجى أضافة جميع أنواع الخامات
			</div>
			@endif
			<a href="{{ route('addstoretype') }}" class="btn btn-primary">أضافة نوع خام</a>
		</div>
	</div>
</div>
@endsection
