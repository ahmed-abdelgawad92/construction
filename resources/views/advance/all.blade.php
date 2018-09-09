@extends('layouts.master')
@section('title','جميع السلفات')
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>جميع السلفات</h3>
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
			<h4 style="border-bottom: 1px solid #eee; padding-bottom: 5px;">تفاصيل السلفات الغير مدفوعة</h4>
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>#</th>
							<th>أسم الموظف</th>
							<th>نوع الموظف</th>
							<th>قيمة السلفة</th>
							<th>تاريخ السلفة</th>
							<th style="width: 100px;">دفع</th>
							<th style="width: 100px;">تعديل</th>
							<th style="width: 100px;">حذف</th>
						</tr>
					</thead>
					<tbody>
						<?php $count=1; ?>
						@foreach($advances as $advance)
						<tr>
							<td>{{$count++}}</td>
							@if($advance->employee!=null)
							<td><a href="{{ route('showadvance',$advance->employee->id) }}">{{$advance->employee->name}}</a></td>
							@elseif($advance->company_employee!=null)
							<td><a href="{{ route('showcompanyadvance',$advance->company_employee->id) }}">{{$advance->company_employee->name}}</a></td>
							@endif
							@if($advance->company_employee!=null)
							<td>موظف بالشركة</td>
							@elseif($advance->employee!=null)
							<td>موظف منتدب</td>
							@endif
							<td>{{$advance->advance}} جنيه</td>
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
							تم دفعها
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
			@else
			<div class="alert alert-warning">
				لا يوجد سلفات
			</div>
			@endif
		</div>
	</div>
</div>
@endsection
