@extends('layouts.master')
@section('title')
@if(isset($project))
جميع الموظفيين بالمشروع
@else
جميع الموظفيين
@endif
@endsection
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			@if(Route::current()->getName()=='allemployee')
			<h3>جميع الموظفيين @if(isset($project)) المنتدبيين بمشروع <a href="{{ route('showproject',$project->id) }}">{{$project->name}}</a> @else المنتدبيين @endif </h3>
			@else
			<h3>جميع موظفيين الشركة</h3>
			@endif
		</div>
		<div class="panel-body">
			@if(session('success'))
			<div class="alert alert-success">
				{{session('success')}}
			</div>
			@endif
			@if(session('update_error'))
			<div class="alert alert-danger">
				{{session('update_error')}}
			</div>
			@endif 
			@if($today->day==1)
			<div class="alert alert-info alert-dismissible">
				<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
				اليوم عليك دفع رواتب الموظفيين
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			@endif
			@if($tomorrow->day==1)
			<div class="alert alert-info alert-dismissible">
				<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
				غداً عليك دفع رواتب الموظفيين
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			@endif
			@if($in2Days->day==1)
			<div class="alert alert-info alert-dismissible">
				<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
				بعد يومين عليك دفع رواتب الموظفيين
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			@endif
			@if(count($employees)>0)
			@if(Route::current()->getName()=='allemployee')
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>#</th>
							<th>أسم الموظف</th>
							<th>المسمى الوظيفى</th>
							<th>المدينة</th>
							@if(isset($project))
							<th>الراتب</th>
							<th>أنهاء عمله</th>
							@else
							<th>حالة العمل</th>
							<th>توظيف</th>
							@endif
						</tr>
					</thead>
					<tbody>
						<?php $count=1; ?>
						@foreach($employees as $employee)
						<tr>
							<th>{{$count++}}</th>
							<th><a href="{{ route('showemployee',$employee->id) }}">{{$employee->name}}</a></th>
							<th>{{$employee->job}}</th>
							<th>{{$employee->city}}</th>
							@if(isset($project))
							<th>{{$employee->pivot->salary}} جنيه</th>
							<th>
							@if($employee->pivot->done==0)
							<form method="post" action="{{ route('endjob',['eid'=>$employee->id,'pid'=>$project->id]) }}" >
								<button type="button" data-toggle="modal" data-target="#delete{{$employee->id}}" class="btn btn-danger btn-block">إنهاء
								</button>
								<div class="modal fade" id="delete{{$employee->id}}" tabindex="-1" role="dialog">
									<div class="modal-dialog modal-sm">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title">هل تريد إنهاء وظيفة الموظف {{$employee->name}}؟</h4>
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
								<input type="hidden" name="_method" value="PUT">
							</form>
							@else
							انتهى العمل
							@endif
							</th>
							@else
								@if($employee->projects()->wherePivot('done',0)->count()>0)
								<th>يعمل</th>
								<th>يعمل بالفعل</th>
								@else
								<th>لا يعمل</th>
								<th><a href="{{ route('assignjob',$employee->id) }}" class="btn btn-primary btn-block">توظيف</a></th>
								@endif
							@endif
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			@else
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>#</th>
							<th>أسم الموظف</th>
							<th>المسمى الوظيفى</th>
							<th>المدينة</th>
							<th>الراتب</th>
						</tr>
					</thead>
					<tbody>
						<?php $count=1; ?>
						@foreach($employees as $employee)
						<tr>
							<th>{{$count++}}</th>
							<th><a href="{{ route('showcompanyemployee',$employee->id) }}">{{$employee->name}}</a></th>
							<th>{{$employee->job}}</th>
							<th>{{$employee->city}}</th>
							<th>{{$employee->salary}} جنيه</th>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			@endif
			<a href="{{ route('addemployee') }}" class="btn btn-primary">أضافة موظف</a>
			@else
			<div class="alert alert-warning">
				لا يوجد موظفيين @if(isset($project)) بهذا المشروع <a href="{{ route('allemployee') }}" class="btn btn-warning">تعيين موظف</a> @else <a href="{{ route('addemployee') }}" class="btn btn-warning">أضافة موظف</a> @endif
			</div>
			@endif
		</div>
	</div>
</div>
@endsection