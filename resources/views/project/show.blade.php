@extends('layouts.master')
@section('title','بيانات المشروع')
@section('content')
<div class="content">
<div class="row">
	<div class="col-md-8 col-lg-8 col-sm-8 col-sm-offset-2 col-md-offset-0 col-lg-offset-0">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>مشروع {{$project->name}}</h3>
			<h4>العميل <a href="{{ route('showorganization',$org->id) }}">{{$org->name}}</a></h4>
		</div>
		<div class="panel-body">
			@if(session('success'))
				<div class="alert alert-success">
					<strong>{{ session('success') }}</strong>
					<br>
				</div>
			@endif
			<ul class="nav nav-tabs">
				<li class="active" role="presentation"><a href="">الرئيسية</a></li>
				<li class="dropdown" role="presentation">
					<a data-toggle="dropdown" class="dropdown-toggle" role="button" href="">مخازن <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="{{ route('addstores',['cid'=>0,'pid'=>$project->id]) }}">أضافة خامات</a></li>
						<li><a href="{{ route('allstores',$project->id) }}">عرض جميع الخامات بالمشروع</a></li>
					</ul>
				</li>
				<li class="dropdown" role="presentation">
					<a data-toggle="dropdown" class="dropdown-toggle" role="button" href="">رسومات <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="{{ route('addgraphs',$project->id) }}">أضافة رسم</a></li>
						<li><a href="{{ route('allgraph',$project->id) }}">عرض جميع الرسومات</a></li>
					</ul>
				</li>
				<li class="dropdown" role="presentation">
					<a data-toggle="dropdown" class="dropdown-toggle" role="button" href="">موظفيين <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="">تعيين موظف منتدب</a></li>
						<li><a href="">عرض جميع الموظفين بالمشروع</a></li>
					</ul>
				</li>

				<li class="dropdown" role="presentation">
					<a data-toggle="dropdown" class="dropdown-toggle" role="button" href="">حسابات مالية <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li class="dropdown-header">مستخلصات</li>
						<li><a href="{{ route('createextractor',$project->id) }}">أضافة مستخلص</a></li>
						<li><a href="{{ route('alltransaction',$project->id) }}">عرض أجمالى المستخلصات</a></li>
						<li class="divider"></li>
						<li class="dropdown-header">ضرائب</li>
						<li><a href="{{ route('addtaxes',$project->id) }}">أضافة ضريبة</a></li>
						<li><a href="{{ route('showtax',$project->id) }}">عرض جميع الضرائب</a></li>
						<li class="divider"></li>
						<li class="dropdown-header">أكراميات</li>
						<li><a href="{{ route('addexpenses',$project->id) }}">أضافة أكرامية</a></li>
						<li><a href="{{ route('showexpense',$project->id) }}">عرض جميع الأكراميات</a></li>
						<li class="divider"></li>
						<li class="dropdown-header">سلفات</li>
						<li><a href="">أضافة سلفة</a></li>
						<li><a href="">عرض جميع السلفات</a></li>
					</ul>
				</li>
			</ul>
			<a href="{{ url('term/add',$project->id) }}" class="float btn btn-primary width-100">
				أضافة بند
			</a>
			<a href="{{ url('term/all',$project->id) }}" class="float btn btn-primary width-100">
				جميع البنود
			</a>
			<a href="{{ route('showprojectproduction',$project->id) }}" class="float btn btn-primary">
				أجمالى أنتاج المشروع
			</a>
			<a href="{{ route('updateproject',$project->id) }}" class="float btn btn-default width-100">
				تعديل
			</a>
			<form method="post" action="{{ route('deleteproject',$project->id) }}" class="float">
				<button type="button" data-toggle="modal" data-target="#delete" class="btn width-100 btn-danger">حذف</button>
				<div class="modal fade" id="delete" tabindex="-1" role="dialog">
					<div class="modal-dialog modal-sm">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title">هل تريد حذف المشروع {{$project->name}}</h4>
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
	<!--__________________NotStartedTerms______________________-->
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4>البنود التى لم تبدأ بعد</h4>
		</div>
		<div class="panel-body">
			@if(count($notStartedTerms)>0)
			@foreach($notStartedTerms as $term)
				<div class="bordered-right border-primary" style="padding:0 5px 5px 0">
					<a href="{{ route('showterm',$term->id) }}" class="whole">
					<h4>
						كود البند	: {{$term->code}}<br>
					 	نوع البند	: {{$term->type}}
					</h4>
					<p>
						<span class="label label-default">بيان الأعمال</span>
						 {{$term->statement}}
					</p>
					</a>
					<div style="text-align: center;">
					<a href="{{ route('startterm',$term->id) }}" class="btn btn-primary width-100">أبدأ</a>
					</div>
				</div>
			@endforeach
			<div class="row item" style="text-align: center;">
				<a href="{{ url('term/all/notStarted') }}" class="btn btn-default">
					جميع البنود التى لم تبدأ
				</a>
			</div>
			@else
				<div class="alert alert-warning">لا يوجد بنود</div>
			@endif
		</div>
	</div>
	<!--__________________/NotStartedTerms______________________-->
	<!--__________________DoneTerms______________________-->
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4>البنود المنتهية</h4>
		</div>
		<div class="panel-body">
			@if(count($doneTerms)>0)
			@foreach($doneTerms as $term)
			<div class="bordered-right border-primary">
				<a href="{{ route('showterm',$term->id) }}" class="whole">
				<h4>
					كود البند	: {{$term->code}}<br>
				 	نوع البند	: {{$term->type}}
				</h4>
				<p>
					<span class="label label-default">بيان الأعمال</span>
					 {{$term->statement}}
				</p>
				</a>
			</div>
			@endforeach
			<div class="row item" style="text-align: center;">
				<a href="{{ url('term/all/notStarted') }}" class="btn btn-default">
					جميع البنود التى لم تبدأ
				</a>
			</div>
			@else
				<div class="alert alert-warning">لا يوجد بنود منتهية</div>
			@endif
		</div>
	</div>
	<!--__________________/DoneTerms______________________-->
	</div>
	<div class="col-md-4 col-lg-4 col-sm-8 col-sm-offset-2 col-lg-offset-0 col-md-offset-0">
	<!--__________________StartedTerms______________________-->
	<div class="panel panel-default">
		<div class="panel-heading project-heading">
			<h4>البنود التى بدأت</h4>
		</div>
		<div class="panel-body">
			@if(count($startedTerms)>0)
			@foreach($startedTerms as $term)
			<div class="bordered-right">
				<a href="{{ route('showterm',$term->id) }}" class="whole">
				<h4>
					كود البند	: {{$term->code}}<br>
				 	نوع البند	: {{$term->type}}
				</h4>
				<p>
					<span class="label label-default">بيان الأعمال</span>
					 {{$term->statement}}
				</p>
				</a>
			</div>
			@endforeach
			<div class="row item" style="text-align: center;">
				<a href="{{ url('term/all/notStarted') }}" class="btn btn-default">
					جميع البنود التى بدأت
				</a>
			</div>
			@else
				<div class="alert alert-warning">لا يوجد بنود بدأت</div>
			@endif
		</div>
	</div>
	<!--__________________/StartedTerms______________________-->
	<!--__________________OffTerms______________________-->
	<div class="panel panel-default">
		<div class="panel-heading project-heading">
			<h4>البنود المعطلة</h4>
		</div>
	</div>
	<!--__________________/OffTerms______________________-->
	</div>
</div>
</div>
@endsection
