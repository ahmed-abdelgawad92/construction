@extends('layouts.master')
@section('title','تفاصيل الأستقطاعات بالمشروع'.$project->name)
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>أجمالى الأستقطاعات بمشروع <a href="{{ route('showproject',$project->id) }}">{{$project->name}}</a></h3>
		</div>
		<div class="panel-body">
			@if(session('success'))
				<div class="alert alert-success">
					{{session('success')}}
				</div>
			@endif
			<div class="jumbotron">
				<h2 style="border-bottom: 1px solid #000; padding-bottom: 5px;">أجمالى الأستقطاعات</h2>
				<br><br>
				<div class="row">
					<div class="col-sm-12 col-md-4 col-lg-4 col-xs-12" style="margin-bottom: 10px;">
						<div class="circle-div">
							{{$total_term_value}} جنيه
						</div>
						<p style="text-align: center; margin-top: 8px;">أجمالى قيمة البنود</p>
					</div>
					<div class="col-sm-12 col-md-4 col-lg-4 col-xs-12" style="margin-bottom: 10px;">
						<div class="circle-div">
							{{ $total_tax }}%
						</div>
						<p style="text-align: center; margin-top: 8px;">أجمالى نسبة الأستقطاعات</p>
					</div>
					<div class="col-sm-12 col-md-4 col-lg-4 col-xs-12" style="margin-bottom: 10px;">
						<div class="circle-div">
							{{$total_tax_value}} جنيه
						</div>
						<p style="text-align: center; margin-top: 8px;">أجمالى قيمة الأستقطاعات</p>
					</div>
				</div>
			</div>
			@if(count($taxes)>0)
			<h4 style="border-bottom: 1px solid #eee; padding-bottom: 5px;">تفاصيل الأستقطاعات</h4>
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>#</th>
							<th>أسم الأستقطاع</th>
							<th>قيمة الأستقطاع</th>
							<th>تاريخ</th>
							<th style="width: 100px;">تعديل</th>
							<th style="width: 100px;">حذف</th>
						</tr>
					</thead>
					<tbody>
						<?php $count=1; ?>
						@foreach($taxes as $tax)
						<tr>
							<td>{{$count++}}</td>
							<td>{{$tax->name}}</td>
							<td>{{$tax->value.' '.$tax->getType()}}</td>
							<td>{{$tax->created_at->format('d/m/Y')}}</td>
							<td><a href="{{ route('updatetax',$tax->id) }}" class="btn btn-block btn-default">تعديل</a></td>
							<td>
							<form method="post" action="{{ route('deletetax',$tax->id) }}">
							<button type="button" data-toggle="modal" data-target="#delete{{$tax->id}}" class="btn btn-block btn-danger">
								حذف
							</button>
							<div class="modal fade" id="delete{{$tax->id}}" tabindex="-1" role="dialog">
								<div class="modal-dialog modal-sm">
									<div class="modal-content">
										<div class="modal-header">
											<h4 class="modal-title">هل تريد حذف هذه الأستقطاع؟</h4>
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
			<a class="btn btn-primary" href="{{ route('addtaxes',$project->id) }}">أضافة أستقطاع</a>
			@else
			<div class="alert alert-warning">
				لا يوجد أستقطاعات بهذا المشروع <a class="btn btn-warning" href="{{ route('addtaxes',$project->id) }}">أضافة أستقطاع</a>
			</div>
			@endif
		</div>
	</div>
</div>
@endsection
