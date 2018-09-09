@extends('layouts.master')
@section('title')
@if(Route::current()->getName()=='allproject')
جميع المشروعات
@elseif(Route::current()->getName()=='allstartedproject')
جميع المشروعات الحالية
@elseif(Route::current()->getName()=='alldoneproject')
جميع المشروعات المنتهية
@elseif(Route::current()->getName()=='allnotstartedproject')
جميع المشروعات التى لم تبدأ
@endif
@endsection
@section('content')
<div class="content">
	<div class="panel panel-default">
	<div class="panel-heading">
		<h3>
		@if(Route::current()->getName()=='allproject')
		جميع المشروعات
		@elseif(Route::current()->getName()=='allstartedproject')
		جميع المشروعات الحالية
		@elseif(Route::current()->getName()=='alldoneproject')
		جميع المشروعات المنتهية
		@elseif(Route::current()->getName()=='allnotstartedproject')
		جميع المشروعات التى لم تبدأ
		@endif
		</h3>
	</div>
	<div class="panel-body">
	@if(session('update_error'))
	<div class="alert alert-danger">
		<strong>خطأ</strong>
		<br>
		<ul>
			<li>{{ session('update_error') }}</li>
		</ul>
	</div>
	@endif
	@if(session('success'))
		<div class="alert alert-success">
			<strong>تمت بنجاح</strong>
			<br>
			<ul>
				<li>{{ session('success') }}</li>
			</ul>
		</div>
	@endif
	@if(count($projects)>0)
	<div class="table-responsive">
	<table class="table table-bordered">
		<thead>
			<tr>
				<th>#</th>
				<th>أسم المشروع</th>
				<th>أسم العميل</th>
				<!-- <th>الشارع</th>
				<th>القرية</th>
				<th>المركز</th> -->
				<th>المدينة</th>
				<th>نوع العميل</th>
				<!-- <th>عدد الفصول</th>
				<th>النموذج المستخدم</th>
				<th>عدة التنفيذ</th>
				<th>عدد الأدوار</th> -->
				<th>ميعاد استلام الموقع</th>
				<th>مدة التنفيذ</th>
				@if(Route::current()->getName()=='allnotstartedproject')
				<th>أبدأ ألأن</th>
				@elseif(Route::current()->getName()=='allstartedproject')
				<th>تسلييم المشروع</th>
				@endif
			</tr>
		</thead>
		<tbody>
			<?php $count=1; ?>
			@foreach($projects as $project)
				<tr>
					<th>{{$count}}</th>
					<th>
						<a href="{{ route('showproject',$project->id) }}">
						{{$project->name}}
						</a>
					</th>
					<th>
						<a href="{{ route('showorganization',$project->organization->id) }}">
						{{$project->organization->name}}
						</a>
					</th>
					<th>{{$project->city}}</th>
					<th>
						@if($project->organization->type==1)
							مقاول
						@elseif($project->organization->type==0)
							عميل
						@endif
					</th>
					@if($project->started_at==null)
					<th>لم يتم تحديد التاريخ</th>
					@else
					<th>{{$project->started_at->format('Y-m-d')}}</th>
					@endif
					<th>{{$project->implementing_period}} شهور</th>
					@if(Route::current()->getName()=='allnotstartedproject')
					<th>
						<form method="post" action="{{ route('startproject',$project->id) }}">
							<button type="button" data-toggle="modal" data-target="#update{{$project->id}}" class="btn btn-primary btn-block">أبدأ ألأن</button>
							<div class="modal fade" id="update{{$project->id}}" tabindex="-1" role="dialog">
								<div class="modal-dialog modal-sm">
									<div class="modal-content">
										<div class="modal-header">
											<h4 class="modal-title">هل تريد بدأ هذا المشروع فعلا</h4>
										</div>
										<div class="modal-body">
											<p>هذا يعنى أن تاريخ أستلام الموقع لهذا المشروع سيصبح تاريخ اليوم</p>
										</div>
										<div class="modal-footer">
									        <button type="button" class="btn btn-default" data-dismiss="modal">لا
									        </button>
									        <button class="btn btn-primary">نعم
									        </button>
									    </div>
									</div>
								</div>
							</div>
							<input type="hidden" name="_method" value="PUT">
    						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						</form>
					</th>
					@elseif(Route::current()->getName()=='allstartedproject')
					<th>
						<form method="post" action="{{ route('endproject',$project->id) }}">
							<button type="button" data-toggle="modal" data-target="#update{{$project->id}}" class="btn btn-primary btn-block">أنهاء ألأن</button>
							<div class="modal fade" id="update{{$project->id}}" tabindex="-1" role="dialog">
								<div class="modal-dialog modal-sm">
									<div class="modal-content">
										<div class="modal-header">
											<h4 class="modal-title">هل تريد أنهاء هذا المشروع فعلا</h4>
										</div>
										<div class="modal-body">
											<p>هذا يعنى أنك أنتهيت من تسليم المشروع ألأن</p>
										</div>
										<div class="modal-footer">
									        <button type="button" class="btn btn-default" data-dismiss="modal">لا
									        </button>
									        <button class="btn btn-primary">نعم
									        </button>
									    </div>
									</div>
								</div>
							</div>
							<input type="hidden" name="_method" value="PUT">
    						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						</form>
					</th>
					@endif
				</tr>
				<?php $count++; ?>
			@endforeach
		</tbody>
	</table>
	</div>
	@else
	<div class="alert alert-warning">لا يوجد مشروعات </div>
	@endif
	<a href="{{ route('addproject') }}" class="btn btn-primary">أضافة مشروع</a>
	</div>
	</div>
</div>
@endsection