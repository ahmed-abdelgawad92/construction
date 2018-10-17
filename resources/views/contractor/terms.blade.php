@extends('layouts.master')
@section('title','البنود المتعاقد عليها')
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>جميع البنود المتعاقد عليها مع المقاول <a href="{{ route('showcontractor',$contractor->id) }}">{{ $contractor->name }}</a></h3>
		</div>
		<div class="panel-body">
			@if(count($terms)>0)
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
					<tr>
						<th>#</th>
						<th>نوع البند</th>
						<th>كود البند</th>
						<th>بيان الأعمال</th>
						<th>الكمية</th>
						<th>قيمة الوحدة للمقاول</th>
						<th>أظهار العقد</th>
					</tr>
					</thead>
					<tbody>
					<?php $count=1; ?>
					@foreach($terms as $term)
					<tr>
						<td>{{ $count++ }}</td>
						<td>{{ $term->type }}</td>
						<td><a href="{{route('showterm',$term->id)}}">{{ $term->code }}</a></td>
						<td>{{ $term->statement }}</td>
						<td style="white-space:nowrap;">{{ Str::number_format($term->amount) }} {{ $term->unit }}</td>
						<td>{{ Str::number_format($term->contractor_unit_price($contractor->id)) }} جنيه</td>
						<td>
							<button type="button" data-toggle="modal" data-target="#con{{$term->id}}" class="btn btn-primary btn-block">
							أفتح العقد
							</button>
							<div class="modal fade" id="con{{$term->id}}" tabindex="-1" role="dialog" aria-labelledby="con-title" style="white-space: normal !important">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<h4 class="modal-title" id="con-title">نص العقد</h4>
										</div>
										<div class="modal-body">
											<p>{{ $term->contract($contractor->id)->contract_text??' لا يوجد نص لهذا العقد  '}}</p>
										</div>
										<div class="modal-footer">
									        <button type="button" class="btn btn-default" data-dismiss="modal">أغلق
									        </button>
									    </div>
									</div>
								</div>
							</div>
						</td>
					</tr>
					@endforeach
					</tbody>
				</table>
			</div>
			@else
			<div class="alert alert-warning">
				لا يوجد بنود متعاقد عليها
			</div>
			@endif
		</div>
	</div>
</div>
@endsection
