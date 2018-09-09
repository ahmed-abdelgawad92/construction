@extends('layouts.master')
@section('title','أجمالى أنتاج البند')
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>أجمالى الأنتاج و تفاصيله فى البند <a href="{{ route('showterm',$term->id) }}">{{$term->code}}</a> بالمشروع <a href="{{ route('showproject',$term->project->id) }}">{{ $term->project->name }}</a></h3>
		</div>
		<div class="panel-body">
			@if(session('success'))
				<div class="alert alert-success">
					<strong>تمت بنجاح</strong>
					<br>
					<ul>
						<li>{{ session('success') }}</li>
					</ul>
				</div>
			@endif
			@if(session('delete_error'))
				<div class="alert alert-danger">
					{{ session('delete_error') }}
				</div>
			@endif
			@if(count($productions)>0)
			<div class="jumbotron">

				<h2 style="border-bottom: 1px solid #000; padding-bottom: 5px;">أجمالى أنتاج البند</h2>
				<br><br>
				<div class="row">
					<div class="col-sm-6 col-md-4 col-lg-4 col-xs-6" style="margin-bottom: 10px;">
						<div class="circle-div">
							{{$total_production}}  {{$term->unit}}
						</div>
						<p style="text-align: center; margin-top: 8px;">أجمالى الأنتاج</p>
					</div>
					<div class="col-sm-6 col-md-4 col-lg-4 col-xs-6" style="margin-bottom: 10px;">
						<div class="circle-div">
							{{$remain_amount}} {{$term->unit}}
						</div>
						<p style="text-align: center; margin-top: 8px;">الكمية الباقية</p>
					</div>
					<div class="col-sm-6 col-md-4 col-lg-4 col-xs-6" style="margin-bottom: 10px;">
						<div class="circle-div">
							{{round($avg_rate,2)}}
						</div>
						<p style="text-align: center; margin-top: 8px;">متوسط تقييم الأنتاج</p>
					</div>
				</div>
				<h3 style="text-align: center;">النسبة المئوية لما تم أنتاجه من البند</h3>
				<div class="progress" style="margin-top: 15px">
					<div class="progress-bar" role="progressbar" aria-valuenow="{{$productionPercent}}" aria-valuemin="{{$productionPercent}}" aria-valuemax="100" style="width: {{$productionPercent}}%; min-width: 2em;">
					    {{$productionPercent}}%
					</div>
				</div>
				@if($productionPercent>=80)
				<div style="text-align: center;">
					<p style="text-align: center;">يمكنك ألأن أنهاء البند , لقد تم أكثر من 80% من البند</p>
					<a href="{{ route('endterm',$term->id) }}" class="btn btn-primary">أنهاء البند</a>
				</div>
				@endif
			</div>
			<h4 style="border-bottom: 1px solid #eee; padding-bottom: 5px;">تفاصيل الأنتاج</h4>
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>#</th>
							<th>الكمية المنتجة</th>
							<th>التقييم</th>
							<th>تاريخ الأنتاج</th>
							<th>ملحوظة</th>
							<th style="width: 100px !important">تعديل</th>
							<th style="width: 100px !important">حذف</th>
						</tr>
					</thead>
					<tbody>
						<?php $count=1; ?>
						@foreach($productions as $production)
						<tr>
						<td>{{$count++}}</td>
						<td>{{$production->amount}}</td>
						<td style="color: #337ab7;">
							<?php $stars=10-$production->rate; ?>
							@while($production->rate!=0)
							<span class="glyphicon glyphicon-star" aria-hidden="true"></span>
							<?php $production->rate--; ?>
							@endwhile
							@while($stars!=0)
							<span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span>
							<?php $stars--; ?>
							@endwhile
						</td>
						<td>{{$production->created_at->format('Y-m-d')}}</td>
						<td style="width: 100px !important">
							@if(empty($production->note))
							لا يوجد ملحوظة
							@else
							<button type="button" data-toggle="modal" data-target="#note{{$count}}" class="btn btn-primary btn-block">
							أقرا الملحوظة
							</button>
							<div class="modal fade" id="note{{$count}}" tabindex="-1" role="dialog" aria-labelledby="note-title" style="white-space: normal !important">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<h4 class="modal-title" id="note-title">نص الملحوظة</h4>
										</div>
										<div class="modal-body">
											<p>{{ $production->note }}</p>
										</div>
										<div class="modal-footer">
									        <button type="button" class="btn btn-default" data-dismiss="modal">أغلق
									        </button>
									    </div>
									</div>
								</div>
							</div>
							@endif
						</td>
						<td style="width: 100px !important"><a href="{{ route('updateproduction',$production->id) }}" class="btn btn-default btn-block">تعديل</a></td>
						<td style="width: 100px !important">
							<form method="post" action="{{ route('deleteproduction',$production->id) }}">
								<button type="button" data-toggle="modal" data-target="#delete{{$count}}" class="btn btn-danger btn-block">حذف</button>
								<div class="modal fade" id="delete{{$count}}" tabindex="-1" role="dialog">
									<div class="modal-dialog modal-sm">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title">هل تريد حذف كمية الأنتاج هذه من البند؟</h4>
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
			<div class="alert alert-warning">لا يوجد أنتاج لهذا البند</div>
			@endif
			<a href="{{ route('addproduction',$term->id) }}" class="btn btn-primary">أضافة أنتاج</a>
			<a href="{{ route('showprojectproduction',$term->project_id) }}" class="btn btn-primary">أجمالى أنتاج المشروع</a>
		</div>
	</div>
</div>
@endsection
