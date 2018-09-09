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
			<form method="post" action="{{ route('findallstores') }}" class="form-horizontal">
				<div class="form-group @if($errors->has('project_id')) has-error @endif">
					<label for="project_id" class="control-label col-sm-2 col-md-2 col-lg-2">أختار مشروع</label>
					<div class="col-sm-6 col-md-6 col-lg-6">
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
					<div class="col-sm-2 col-md-2 col-lg-2">
						<button class="btn btn-primary form-control" id="save_btn">أذهب</button>
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
							<th>الوحدة</th>
							<th>القيمة المدفوعة</th>
						</tr>
					</thead>
					<tbody>
						<?php $count=1; $check=1;?>
						@foreach($stores as $store)
						<tr>
							<td>{{$count++}}</td>
							<td><a href="{{ route('showstore',$store->type) }}">{{$store->type}}</a></td>
							@foreach($consumptions as $con)
							@if($con->type==$store->type)
							<?php $check++; ?>
							<td>{{$con->amount}}</td>
							<td>{{$store->amount-$con->amount}}</td>
							@endif
							@endforeach
							@if($check!=$count)
							<?php $check++; ?>
							<td>0</td>
							<td>{{$store->amount}}</td>
							@endif
							<td>{{$store->amount}}</td>
							@foreach($store_types as $type)
							@if($type->name==$store->type)
							<td>{{$type->unit}}</td>
							@endif
							@endforeach
							<td>{{$store->amount_paid}}</td>
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
