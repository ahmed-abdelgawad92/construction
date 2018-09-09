@extends('layouts.master')
@section('title','أجمالى أنتاج المشروع')
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>أجمالى الأنتاج بالمشروع <a href="{{ route('showproject',$project->id) }}">{{ $project->name }}</a></h3>
		</div>
		<div class="panel-body">
			@if(count($productions)>0)
			<div class="jumbotron">
				<h2 style="border-bottom: 1px solid #000; padding-bottom: 5px;">أجمالى أنتاج المشروع</h2>
				<br><br>
				<div class="row">
					<div class="col-sm-6 col-md-4 col-lg-4 col-xs-6" style="margin-bottom: 10px;">
					</div>
					<div class="col-sm-12 col-md-4 col-lg-4 col-xs-12" style="margin-bottom: 10px;">
						<div class="circle-div">
							{{ round($avg_rate,2) }}
						</div>
						<p style="text-align: center; margin-top: 8px;">متوسط تقييم الأنتاج</p>
					</div>
					<div class="col-sm-6 col-md-4 col-lg-4 col-xs-6" style="margin-bottom: 10px;">
					</div>
				</div>
				<h3 style="text-align: center;">النسبة المئوية لما تم أنتاجه من المشروع</h3>
				<div class="progress" style="margin-top: 15px">
					<div class="progress-bar" role="progressbar" aria-valuenow="" aria-valuemin="{{round($total_production/$total_amount*100,2)}}" aria-valuemax="100" style="width: {{round($total_production/$total_amount*100,2)}}%; min-width: 2em;">
					   {{round($total_production/$total_amount*100,2)}}%
					</div>
				</div>
			</div>
			<h4 style="border-bottom: 1px solid #eee; padding-bottom: 5px;">تفاصيل الأنتاج</h4>
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>#</th>
							<th>كود البند</th>
							<th>الكمية المنتجة</th>
							<th>الكمية الباقية</th>
							<th>أجمالى البند</th>
							<th>نسبة الأنتاج بالبند</th>
							<th>التقييم</th>
						</tr>
					</thead>
					<tbody>
						<?php $count=1; ?>
						@foreach($productions as $production)
						<tr>
						<td>{{$count++}}</td>
						<td>
							<a href="{{ route('showtermproduction',$production->term_id) }}">
								{{$production->code}}
							</a>
						</td>
						<td>{{$production->amount}}</td>
						<td>{{ $production->total_amount-$production->amount }}</td>
						<td>{{ $production->total_amount }}</td>
						<td>{{($production->amount/$production->total_amount) * 100}}%</td>
						<td style="color: #337ab7;">
							@for($i=$production->rate;$i>0;$i--)
							@if($i<1)
							<span class="glyphicon glyphicon-star half" aria-hidden="true"></span>
							@else
							<span class="glyphicon glyphicon-star" aria-hidden="true"></span>
							@endif
							@endfor
							<span class="badge">{{round($production->rate,2)}}</span>
						</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			@else
			<div class="alert alert-warning">لا يوجد أنتاج لهذا المشروع</div>
			@endif
		</div>
	</div>
</div>
@endsection
