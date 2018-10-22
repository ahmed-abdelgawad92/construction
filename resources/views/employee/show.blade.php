@extends('layouts.master')
@section('title','بيانات الموظف')
@section('content')
<div class="content">
<div class="row">
	<div class="col-md-8 col-lg-8 col-sm-8 col-sm-offset-2 col-md-offset-0 col-lg-offset-0">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>الموظف {{$employee->name}}</h3>
		</div>
		<div class="panel-body">
			@if(session('success'))
			<div class="alert alert-success">
				{{session('success')}}
			</div>
			@endif
			@if(session('delete_error'))
			<div class="alert alert-danger">
				{{session('delete_error')}}
			</div>
			@endif
			@if(session('update_error'))
			<div class="alert alert-danger">
				{{session('update_error')}}
			</div>
			@endif
			@if(count($errors)>0)
				<div class="alert alert-danger">
				خطأ
				<ul>
				@foreach($errors->all() as $error)
					<li>{{$error}}</li>
				@endforeach
				</ul>
				</div>
			@endif
			<div class="table-responsive">
				<table class="table table-striped">
					<tr><th class="min100">الوظيفة </th><td>{{$employee->job}}</td></tr>
					<tr><th class="min100">نوع الموظف </th><td>
					@if(Route::current()->getName()=='showcompanyemployee')
					موظف بالشركة
					@else
					منتدب
					@endif
					</td></tr>
					@if(Route::current()->getName()=='showcompanyemployee')
					<tr><th class="min100">الراتب </th><td>{{$employee->salary}} جنيه</td></tr>
					@endif
					@if(!empty($employee->address))
					<tr><th class="min100">الشارع </th><td>{{$employee->address}}</td></tr>
					@endif
					@if(!empty($employee->village))
					<tr><th class="min100">القرية </th><td>{{$employee->village}}</td></tr>
					@endif
					<tr><th class="min100">المدينة </th><td>{{$employee->city}}</td></tr>
					<tr><th class="min100">التليفون </th><td>{{$employee->phone}}</td></tr>
				</table>
			</div>
			@if(Route::current()->getName()=='showemployee')
			<a href="{{ route('assignjob',$employee->id) }}" class="m-top btn btn-primary float">توظيف</a>
			@endif
			@if(Route::current()->getName()=='showemployee')
			<a href="{{ route('addadvances',$employee->id) }}" class="m-top btn btn-primary float">أضافة سلفة</a>
			@elseif(Route::current()->getName()=='showcompanyemployee')
			<a href="{{ route('addcompanyadvances',$employee->id) }}" class="m-top btn btn-primary float">أضافة سلفة</a>
			@endif
			<a href="
			@if(Route::current()->getName()=='showemployee')
			{{ route('updateemployee',$employee->id) }}
			@elseif(Route::current()->getName()=='showcompanyemployee')
			{{ route('updatecompanyemployee',$employee->id) }}
			@endif
			" class="m-top btn btn-default float">تعديل</a>
			<form class="float" method="post" action="
			@if(Route::current()->getName()=='showemployee')
			{{ route('deleteemployee',$employee->id) }}
			@elseif(Route::current()->getName()=='showcompanyemployee')
			{{ route('deletecompanyemployee',$employee->id) }}
			@endif
			">
				<button type="button" data-toggle="modal" data-target="#delete" class="btn m-top btn-danger">حذف</button>
				<div class="modal fade" id="delete" tabindex="-1" role="dialog">
					<div class="modal-dialog modal-sm">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title">هل تريد حذف الموظف {{$employee->name}}؟</h4>
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
		</div>
	</div>
	</div>
	<div class="col-md-4 col-lg-4 col-sm-4">
	@if(Route::current()->getName()=='showemployee')
	<div class="panel panel-default">
		<div class="panel-heading navy-heading">
			<h3>المشاريع المنتدب بها</h3>
		</div>
		<div class="panel-body">
			@if(count($projects)>0)
			@foreach($projects as $project)
				<div class="bordered-right border-navy" style="padding:0 5px 5px 0">
					<h4 style="padding-right: 5px;">
						أسم المشروع : {{$project->name}}
					</h4>
					<p style="padding-right: 5px;">
						الراتب	: {{$project->pivot->salary}} جنيه<br>
						حالة الوظيفة : @if($project->pivot->done==1) أنتهت @else مستمر @endif <br>
						@if($project->pivot->done==1)
						تاريخ الانتهاء : {{$project->pivot->ended_at}}
						@endif
					</p>
					@if($project->pivot->done==0)
					<form method="post" class="float" class="form-inline" action="{{ route('updatesalary',['eid'=>$employee->id,'pid'=>$project->id]) }}" >
						<button type="button" data-toggle="modal" data-target="#update-salary{{$project->id}}" class="btn btn-default">تعديل الراتب
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
					<form method="post" class="form-inline" action="{{ route('endjob',['eid'=>$employee->id,'pid'=>$project->id]) }}" >
						<button type="button" data-toggle="modal" data-target="#endjob{{$project->id}}" class="btn btn-danger">إنهاء الوظيفة
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
					@endif
				</div>
			@endforeach
			<div class="center">
				<a href="{{ route('employeeprojects',$employee->id) }}"class="btn btn-default">
					جميع المشاريع المنتدب بها
				</a>
			</div>
			@else
				<div class="alert alert-warning">لا يوجد سلفات لهذا الموظف</div>
			@endif
		</div>
	</div>
	@endif
	<div class="panel panel-default">
		<div class="panel-heading navy-heading">
			<h3>السلفات</h3>
		</div>
		<div class="panel-body">
			@if(count($advances)>0)
			@foreach($advances as $advance)
				<div class="bordered-right border-navy" style="padding:0 5px 5px 0">
					<h4>
						قيمة السلفة : {{$advance->advance}}<br>
						الحالة	: @if($advance->active==1) تم تسديد السلفة @else لم تسدد @endif
					</h4>
					@if($advance->active==0)
					<form method="post" action="{{ route('repayadvance',$advance->id) }}">
					<button type="button" data-toggle="modal" data-target="#pay{{$advance->id}}" class="btn btn-primary" style="background: #2a3f54;">
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
					@endif
				</div>
			@endforeach
			<div class="center">
				@if(Route::current()->getName()=='showemployee')
				<a href="{{ route('showadvance',$employee->id) }}"class="btn btn-default">
					جميع سلفات الموظف
				</a>
				@elseif(Route::current()->getName()=='showcompanyemployee')
				<a href="{{ route('showcompanyadvance',$employee->id) }}"class="btn btn-default">
					جميع سلفات الموظف
				</a>
				@endif
			</div>
			@else
				<div class="alert alert-warning">لا يوجد سلفات لهذا الموظف</div>
			@endif
		</div>
	</div>
	</div>
</div>
</div>
@endsection
