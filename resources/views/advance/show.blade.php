@extends('layouts.master')
@section('title','جميع سلفات الموظف')
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>جميع سلفات الموظف
			@if(Route::current()->getName()=='showcompanyadvance')
			<a href="{{ route('showcompanyemployee',$employee->id) }}">{{$employee->name}}</a>
			@elseif(Route::current()->getName()=='showadvance')
			<a href="{{ route('showemployee',$employee->id) }}">{{$employee->name}}</a>
			@endif
			</h3>
		</div>
		<div class="panel-body">
			@if(session('update_error'))
				<div class="alert alert-danger">
					{{session('update_error')}}
				</div>
			@endif
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
			<div class="jumbotron">
				<h2 style="border-bottom: 1px solid #000; padding-bottom: 5px;">أجمالى السلفات</h2>
				<br><br>
				<div class="row">
					<div class="col-sm-6 col-md-4 col-lg-4 col-xs-12" style="margin-bottom: 10px;">
						<div class="circle-div">
							{{ $total_advance }} جنيه
						</div>
						<p style="text-align: center; margin-top: 8px;">أجمالى السلفات</p>
					</div>
					<div class="col-sm-6 col-md-4 col-lg-4 col-xs-12" style="margin-bottom: 10px;">
						<div class="circle-div">
							{{ $total_advance_unpaid }} جنيه
						</div>
						<p style="text-align: center; margin-top: 8px;">أجمالى الغير مدفوع</p>
					</div>
					<div class="col-sm-6 col-md-4 col-lg-4 col-xs-12" style="margin-bottom: 10px;">
						<div class="circle-div">
							{{ $total_advance-$total_advance_unpaid }} جنيه
						</div>
						<p style="text-align: center; margin-top: 8px;">أجمالى المدفوع</p>
					</div>
				</div>
			</div>
			@if(count($advances)>0)
			<h4 style="border-bottom: 1px solid #eee; padding-bottom: 5px;">تفاصيل السلفات</h4>
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>#</th>
							<th>قيمة السلفة</th>
							<th>حالة السلفة</th>
							<th>تاريخ السلفة</th>
							<th style="width: 100px;">دفع/تاريخ الدفع</th>
							<th style="width: 100px;">تعديل</th>
							<th style="width: 100px;">حذف</th>
						</tr>
					</thead>
					<tbody>
						<?php $count=1; ?>
						@foreach($advances as $advance)
						<tr>
							<td>{{$count++}}</td>
							<td>{{$advance->advance}} جنيه</td>
							<td>@if($advance->active==0) لم تدفع بعد @else تم دفع السلفة @endif </td>
							<td>{{$advance->created_at->format('Y-m-d')}}</td>
							<td>
							@if($advance->active==0)
							<form method="post" action="{{ route('repayadvance',$advance->id) }}">
							<button type="button" data-toggle="modal" data-target="#pay{{$advance->id}}" class="btn btn-block btn-primary">
								رد السلفة
							</button>
							<div class="modal fade" id="pay{{$advance->id}}" tabindex="-1" role="dialog">
								<div class="modal-dialog modal-sm">
									<div class="modal-content">
										<div class="modal-header">
											<h4 class="modal-title">هل تريد رد هذه السلفة؟</h4>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">لا
											</button>
											<button class="btn btn-primary">نعم</button>
										</div>
									</div>
								</div>
							</div>
							<input type="hidden" name="_token" value="{{csrf_token()}}">
							<input type="hidden" name="_method" value="PUT">
							</form>
							@else
							{{$advance->payment_at->format('Y-m-d')}}
							@endif
							</td>
							<td><a href="{{ route('updateadvance',$advance->id) }}" class="btn btn-block btn-default">تعديل</a></td>
							<td>
							<form method="post" action="{{ route('deleteadvance',$advance->id) }}">
							<button type="button" data-toggle="modal" data-target="#delete{{$advance->id}}" class="btn btn-block btn-danger">
								حذف
							</button>
							<div class="modal fade" id="delete{{$advance->id}}" tabindex="-1" role="dialog">
								<div class="modal-dialog modal-sm">
									<div class="modal-content">
										<div class="modal-header">
											<h4 class="modal-title">هل تريد حذف هذه السلفة؟</h4>
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
			@if(Route::current()->getName()=='showadvance')
			<a class="btn btn-primary" href="{{ route('addadvances',$employee->id) }}">أضافة سلفة</a>
			@elseif(Route::current()->getName()=='showcompanyadvance')
			<a class="btn btn-primary" href="{{ route('addcompanyadvances',$employee->id) }}">أضافة سلفة</a>
			@endif
			@else
			<div class="alert alert-warning">
				لا يوجد سلفات لهذا الموظف
				@if(Route::current()->getName()=='showadvance')
				<a class="btn btn-warning" href="{{ route('addadvances',$employee->id) }}">أضافة سلفة</a>
				@elseif(Route::current()->getName()=='showcompanyadvance')
				<a class="btn btn-warning" href="{{ route('addcompanyadvances',$employee->id) }}">أضافة سلفة</a>
				@endif
			</div>
			@endif
		</div>
	</div>
</div>
@endsection
