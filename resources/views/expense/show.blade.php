@extends('layouts.master')
@section('title','تفاصيل الأكراميات بالمشروع')
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>أجمالى الأكراميات بمشروع <a href="{{ route('showproject',$project->id) }}">{{$project->name}}</a></h3>
		</div>
		<div class="panel-body">
			@if(session('success'))
				<div class="alert alert-success">
					{{session('success')}}
				</div>
			@endif
			<div class="jumbotron">
				<h2 style="border-bottom: 1px solid #000; padding-bottom: 5px;">أجمالى الأكراميات</h2>
				<br><br>
				<div class="row">
					<div class="col-sm-6 col-md-4 col-lg-4 col-xs-6" style="margin-bottom: 10px;">
					</div>
					<div class="col-sm-12 col-md-4 col-lg-4 col-xs-12" style="margin-bottom: 10px;">
						<div class="circle-div">
							{{ $total_expense }} جنيه
						</div>
						<p style="text-align: center; margin-top: 8px;">أجمالى الأكراميات</p>
					</div>
					<div class="col-sm-6 col-md-4 col-lg-4 col-xs-6" style="margin-bottom: 10px;">
					</div>
				</div>
			</div>
			@if(count($expenses)>0)
			<h4 style="border-bottom: 1px solid #eee; padding-bottom: 5px;">تفاصيل الأكراميات</h4>
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>#</th>
							<th>وصف الأكرامية</th>
							<th>قيمة الأكرامية</th>
							<th>تاريخ</th>
							<th style="width: 100px;">تعديل</th>
							<th style="width: 100px;">حذف</th>
						</tr>
					</thead>
					<tbody>
						<?php $count=1; ?>
						@foreach($expenses as $expense)
						<tr>
							<td>{{$count++}}</td>
							<td>{{$expense->whom}}</td>
							<td>{{$expense->expense}} جنيه</td>
							<td>{{date('d/m/Y',strtotime($expense->created_at))}}</td>
							<td><a href="{{ route('updateexpense',$expense->id) }}" class="btn btn-block btn-default">تعديل</a></td>
							<td>
							<form method="post" action="{{ route('deleteexpense',$expense->id) }}">
							<button type="button" data-toggle="modal" data-target="#delete{{$expense->id}}" class="btn btn-block btn-danger">
								حذف
							</button>
							<div class="modal fade" id="delete{{$expense->id}}" tabindex="-1" role="dialog">
								<div class="modal-dialog modal-sm">
									<div class="modal-content">
										<div class="modal-header">
											<h4 class="modal-title">هل تريد حذف هذه الأكرامية؟</h4>
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
			<a class="btn btn-primary" href="{{ route('addexpenses',$project->id) }}">أضافة أكرامية</a>
			@else
			<div class="alert alert-warning">
				لا يوجد أكراميات بهذا المشروع <a class="btn btn-warning" href="{{ route('addexpenses',$project->id) }}">أضافة أكرامية</a>
			</div>
			@endif
		</div>
	</div>
</div>
@endsection
