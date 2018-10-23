@extends('layouts.master')
@section('title','جميع المشاريع المنتدب بها '.$employee->name)
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>جميع المشاريع المنتدب بها الموظف <a href="{{ route('showemployee',$employee->id) }}">{{$employee->name}}</a></h3>
		</div>
		<div class="panel-body">
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>#</th>
							<th>أسم المشروع</th>
							<th>الراتب</th>
							<th>تاريخ الأنتداب</th>
							<th>تاريخ أنتهاء العمل</th>
							<th>تعديل الراتب</th>
						</tr>
					</thead>
					<tbody>
						<?php $count=1; ?>
						@foreach($employee->projects()->orderBy('employee_project.created_at','desc')->get() as $project)
						<tr>
						<th>{{$count++}}</th>
						<th>
						<a href="{{ route('showproject',$project->id) }}">{{$project->name}}</a></th>
						<th>{{$project->pivot->salary}} جنيه</th>
						<th>{{date('d/m/Y',strtotime($project->pivot->created_at))}}</th>
						@if($project->pivot->done==0)
						<th>
						<form method="post" action="{{ route('endjob',['id'=>$project->pivot->id]) }}" >
							<button type="button" data-toggle="modal" data-target="#endjob{{$project->id}}" class="btn btn-danger btn-block">إنهاء الوظيفة
							</button>
							<div class="modal fade" id="endjob{{$project->id}}" tabindex="-1" role="dialog">
								<div class="modal-dialog modal-sm">
									<div class="modal-content">
										<div class="modal-header">
											<h4 class="modal-title">هل تريد إنهاء وظيفة الموظف {{$employee->name}} بمشروع {{$project->name}}؟</h4>
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
						</th>
						@else
						<th>{{date('d/m/Y',strtotime($project->pivot->ended_at))}}</th>
						@endif
						@if($project->pivot->done==0)
						<th>
						<form method="post" action="{{ route('updatesalary',['id'=>$project->pivot->id]) }}" id="edit_salary">
							<button type="button" data-toggle="modal" data-target="#update-salary{{$project->id}}" class="btn btn-default btn-block">تعديل الراتب
							</button>
							<div class="modal fade" id="update-salary{{$project->id}}" tabindex="-1" role="dialog">
								<div class="modal-dialog modal-sm">
									<div class="modal-content">
										<div class="modal-header">
											<h4 class="modal-title">تعديل الراتب</h4>
										</div>
										<div class="modal-body">
										<div class="form-group">
										<label for="salary" class="control-label">الراتب</label>
										<input type="text" name="salary" id="salary" value="{{old('salary')}}" class="form-control" placeholder="أدخل الراتب">
										</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">لا
											</button>
											<button class="btn btn-primary">تعديل</button>
										</div>
									</div>
								</div>
							</div>
							<input type="hidden" name="_token" value="{{csrf_token()}}">
							<input type="hidden" name="_method" value="PUT">
						</form>
						</th>
						@else
						<th>لا يمكن تعديل الراتب</th>
						@endif
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endsection
