@extends('layouts.master')
@section('title','جميع الخامات بالمخازن')
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>جميع الخامات الموجودة بالمشروع @if(isset($project)) <a href="{{ route('showproject', $project->id) }}">{{ $project->name }}</a> @endif
			</h3>
		</div>
		<div class="panel-body">
			<form method="post" action="{{ route('findallstores') }}">
				<div class="form-group row @if($errors->has('project_id')) has-error @endif">
					<label for="project_id" class="control-label col-sm-2 col-md-2 col-lg-2"></label>
					<div class="col-sm-7 col-md-7 col-lg-7">
						<select name="project_id" id="project_id" class="form-control">
							<option value="0">أختار مشروع</option>
							@foreach($projects as $p)
							<option value="{{$p->id}}">{{$p->name}}</option>
							@endforeach
						</select>
						@if($errors->has('project_id'))
							@foreach($errors->get('project_id') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
					<div class="col-sm-3 col-md-3 col-lg-3">
						<button class="btn btn-primary width-100" style="height:40px" id="save_btn">أذهب</button>
					</div>
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
				</div>
			</form>
			@if(Route::current()->getName()!='findstores')
			@if(count($stores)>0)
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>#</th>
							<th>نوع الخام</th>
							<th>الكمية المستهلكة</th>
							<th>الكمية الباقية</th>
							<th>الكمية الكلية</th>
							<th>المبلغ المدفوع</th>
							<th>المبلغ الباقى</th>
							<th>السعر الكلى</th>
						</tr>
					</thead>
					<tbody>
						<?php $count=1; $check=1;?>
						@foreach($stores as $store)
						<tr>
							<td>{{$count++}}</td>
							<td><a href="{{ route('showstore',["type"=>$store->type,"id"=>$project->id]) }}" data-toggle="tooltip" data-placement="top" title="جميع تفاصيل واردات ال{{$store->type}}">{{$store->type}}</a></td>
							@foreach($consumptions as $con)
							@if($con->type==$store->type)
							<?php $check++; ?>
							<td>{{Str::number_format($con->amount)}} {{$store->unit}}</td>
							<td>{{Str::number_format($store->amount-$con->amount)}} {{$store->unit}}</td>
							@endif
							@endforeach
							@if($check!=$count)
							<?php $check++; ?>
							<td>0</td>
							<td>{{Str::number_format($store->amount)}} {{$store->unit}}</td>
							@endif
							<td>{{Str::number_format($store->amount)}} {{$store->unit}}</td>
							<td>{{Str::number_format($store->amount_paid)}} جنيه</td>
							<td>{{Str::number_format($store->total_price-$store->amount_paid)}} جنيه</td>
							<td>{{Str::number_format($store->total_price)}} جنيه</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			@else
			<div class="alert alert-warning">
				لم يتم شراء خامات فى هذا المشروع <a href="{{ url('store/add',[0,$project->id]) }}" class="btn btn-warning">شراء خامات</a>
			</div>
			@endif
			@endif
		</div>
	</div>
</div>
@endsection
