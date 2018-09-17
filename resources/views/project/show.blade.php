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
			@if(session('error'))
				<div class="alert alert-danger">
					<strong>{{ session('error') }}</strong>
					<br>
				</div>
			@endif
			@if(session('success'))
				<div class="alert alert-success">
					<strong>{{ session('success') }}</strong>
					<br>
				</div>
			@endif
			@if(session('warning'))
				<div class="alert alert-warning">
					<strong>{{ session('warning') }}</strong>
					<br>
				</div>
			@endif
			<ul class="nav nav-tabs">
				<li class="active" id="main_nav_item" role="presentation"><a class="navigate_to_div" data-nav-path="#main" href="#">الرئيسية</a></li>
				<li id="production_nav_item" role="presentation"><a class="navigate_to_div" data-nav-path="#production" href="#">تقرير الانتاج</a></li>
				<li id="supplier_nav_item" role="presentation"><a class="navigate_to_div" data-nav-path="#supplier" href="#">الموردين</a></li>
				<li class="dropdown raw_nav_item" role="presentation">
					<a data-toggle="dropdown" class="dropdown-toggle" role="button" href="">مخازن <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="#" class="navigate_to_div" data-nav-path="#raw">تقرير بالخامات</a></li>
						<li><a href="{{ route('addstores',['cid'=>0,'pid'=>$project->id]) }}">أضافة خامات</a></li>
						<li><a href="{{ route('allstores',$project->id) }}">عرض جميع الخامات بالمشروع</a></li>
					</ul>
				</li>
				<li id="consumption_nav_item" role="presentation">
					<a class="navigate_to_div" data-nav-path="#consumption" role="button" href="#">الاستهلاك</a>
				</li>
				<li class="dropdown" role="presentation">
					<a data-toggle="dropdown" class="dropdown-toggle" role="button" href="">رسومات <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="{{ route('addgraphs',$project->id) }}">أضافة رسم</a></li>
						<li><a href="{{ route('allgraph',$project->id) }}">عرض جميع الرسومات</a></li>
					</ul>
				</li>
				<li  id="employee_nav_item" class="dropdown" role="presentation">
					<a data-toggle="dropdown" class="dropdown-toggle" role="button" href="">موظفيين <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="">تعيين موظف منتدب</a></li>
						<li><a href="" class="navigate_to_div" data-nav-path="#employee">الموظفين الحاليين</a></li>
						<li><a href="">جميع الموظفين الذين عملوا بالمشروع</a></li>
					</ul>
				</li>
				<li class="dropdown" role="presentation">
					<a data-toggle="dropdown" class="dropdown-toggle" role="button" href="">ورقيات <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="#">إضافة ورقية</a></li>
						<li><a href="#">جميع ورقيات المشروع</a></li>
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
			<section id="navigation">
				<div id="main" class="show">
					<table class="table table-striped mt-3">
						<thead>
						<tr>
							<th>أسم المشروع </th>
							<th>{{$project->name}}</th>
						</tr>
						</thead>
						<tr>
							<th>الرقم التعريفى</th>
							<td>{{implode('-',str_split($project->def_num,3))}}</td>
						</tr>
						<tr>
							<th>شارع</th>
							<td>{{$project->address}}</td>
						</tr>
						@if (!empty($project->village))
						<tr>
							<th>قرية</th>
							<td>{{$project->village}}</td>
						</tr>
						@endif
						@if (!empty($project->center))
						<tr>
							<th>مركز</th>
							<td>{{$project->center}}</td>
						</tr>
						@endif
						<tr>
							<th>مدينة</th>
							<td>{{$project->city}}</td>
						</tr>
						@if (!empty($project->extra_data))
						<tr>
							<th>بيانات أضافية</th>
							<td>{{$project->extra_data}}</td>
						</tr>
						@endif
						@if (!empty($project->model_used))
						<tr>
							<th>النموذج المستخدم</th>
							<td>{{$project->model_used}}</td>
						</tr>
						@endif
						<tr>
							<th>مدة التنفيذ (بالشهر)</th>
							<td>{{$project->implementing_period}}</td>
						</tr>
						<tr>
							<th>عدد الأدوار</th>
							<td>{{$project->floor_num}}</td>
						</tr>
						<tr>
							<th>السعر الكلى التقريبى للمشروع</th>
							<td>{{number_format($project->approximate_price)}} جنيه</td>
						</tr>
						@if ($org->type==1)
						<tr>
							<th>نسبة المقاول</th>
							<td>% {{$project->non_organization_payment}}</td>
						</tr>
						@endif
						@if (!empty($project->cash_box))
						<tr>
							<th>الصندوق</th>
							<td>{{number_format($project->cash_box)}} جنيه</td>
						</tr>
						@endif
						@if (!empty($project->loan))
						<tr>
							<th>قيمة القرض</th>
							<td>جنيه {{number_format($project->loan)}}</td>
						</tr>
						<tr>
							<th>نسبة الفائدة</th>
							<td>% {{$project->loan_interest_rate}}</td>
						</tr>
						<tr>
							<th>أسم البنك</th>
							<td>{{$project->bank}}</td>
						</tr>
						@endif
						<tr>
							<th>الحالة</th>
							@if ($project->done)
							<td>إنتهى</td>
							@elseif(strtotime($project->started_at)>time())
							<td>لم يبدأ</td>
							@else
							<td>بدأ</td>
							@endif
						</tr>
						<tr>
							<th>تاريخ استلام الموقع</th>
							<td>{{date("d/m/Y",strtotime($project->started_at))}}</td>
						</tr>
					</table>
				</div>
				<div id="raw" class="hide">
					<div class="div" style="height:500px; width: 100%;background:red"></div>
				</div>
				<div id="consumption" class="hide">
					<div class="div" style="height:500px; width: 100%;background:blue"></div>
				</div>
				<div id="employee" class="hide">
					<div class="div" style="height:500px; width: 100%;background:grey"></div>
				</div>
				<div id="production" class="hide">
					<div class="div" style="height:500px; width: 100%;background:aqua"></div>
				</div>
				<div id="supplier" class="hide">
					<div class="div" style="height:500px; width: 100%;background:pink"></div>
				</div>
			</section>
			<a href="{{ url('term/add',$project->id) }}" class="float btn btn-primary mb-3 width-100">
				أضافة بند
			</a>
			<a href="{{ url('term/all',$project->id) }}" class="float btn btn-primary mb-3 width-100">
				جميع البنود
			</a>
			<a href="#add_cash_box" class="float btn btn-primary mb-3 width-100 open_float_div">
			@if(!empty($project->cash_box))
				تعديل الصندوق
			@else
				إضافة الصندوق
			@endif
			</a>
			<a href="#add_loan" class="float btn btn-primary mb-3 width-100 open_float_div">
			@if(!empty($project->loan))
				تعديل القرض
			@else
				إضافة قرض
			@endif
			</a>
			{{-- <a href="{{ route('showprojectproduction',$project->id) }}" class="float btn btn-primary mb-3">
				أجمالى أنتاج المشروع
			</a> --}}
			<form method="post" action="{{route('endproject',['id'=>$project->id])}}" class="float">
				<button type="button" data-toggle="modal" data-target="#finish_project" class="btn width-100 btn-success mb-3">إنهاء المشروع</button>
				<div class="modal fade" id="finish_project" tabindex="-1" role="dialog">
					<div class="modal-dialog modal-sm">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title">هل تريد إنهاء المشروع  {{$project->name}}؟</h4>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">لا
								</button>
								<button class="float btn btn-success mb-3">
									إنهاء المشروع
								</button>
							</div>
						</div>
					</div>
				</div>
				<input type="hidden" name="_token" value="{{csrf_token()}}">
				<input type="hidden" name="_method" value="PUT">
			</form>
			<a href="{{ route('updateproject',$project->id) }}" class="float btn btn-default width-100 mb-3">
				تعديل
			</a>
			<form method="post" action="{{ route('deleteproject',$project->id) }}" class="float">
				<button type="button" data-toggle="modal" data-target="#delete" class="btn width-100 btn-danger mb-3">حذف</button>
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

<div id="float_container">
	<div id="float_form_container">
		<form class="form-horizontal float_form" method="post" action="{{route('add_cash_box',['id'=>$project->id])}}" id="add_cash_box">
			<span class="close">&times;</span>
			<h3 class="center mb-5">إضافة قيمة الصندوق</h3>
			<div class="form-group @if($errors->has('cash_box')) has-error @endif">
				<label for="cash_box" class="control-label col-sm-3 col-md-2 col-lg-2">صندوق المال</label>
				<div class="col-sm-9 col-md-10 col-lg-10">
					<input type="text" name="cash_box" id="cash_box" value="{{$project->cash_box}}" class="form-control" placeholder="أدخل رأس مال الصندوق">
					@if($errors->has('cash_box'))
						@foreach($errors->get('cash_box') as $error)
							<span class="help-block">{{ $error }}</span>
						@endforeach
					@endif
				</div>
			</div>
			<div class="col-sm-2 col-md-2 col-lg-2 col-sm-offset-5 col-md-offset-5 col-lg-offset-5">
				<button class="btn btn-primary form-control" id="save_btn">حفظ</button>
			</div>
			@csrf
			@method('PUT')
		</form>
		<form class="form-horizontal float_form" method="post" action="{{route('add_loan',['id'=>$project->id])}}" id="add_loan">
			<span class="close">&times;</span>
			<h3 class="center mb-5">إضافة قرض</h3>
			<div class="form-group @if($errors->has('loan')) has-error @endif">
				<label for="loan" class="control-label col-sm-3 col-md-2 col-lg-2">قيمة القرض</label>
				<div class="col-sm-9 col-md-10 col-lg-10">
					<input type="text" name="loan" id="loan" value="{{$project->loan}}" class="form-control" placeholder="أدخل قيمة القرض">
					@if($errors->has('loan'))
						@foreach($errors->get('loan') as $error)
							<span class="help-block">{{ $error }}</span>
						@endforeach
					@endif
				</div>
			</div>
			<div class="form-group @if($errors->has('loan_interest_rate')) has-error @endif">
				<label for="loan_interest_rate" class="control-label col-sm-3 col-md-2 col-lg-2">نسبة الفائدة</label>
				<div class="col-sm-9 col-md-10 col-lg-10">
					<input type="text" name="loan_interest_rate" id="loan_interest_rate" value="{{$project->loan_interest_rate}}" class="form-control" placeholder="أدخل نسبة فائدة القرض">
					@if($errors->has('loan_interest_rate'))
						@foreach($errors->get('loan_interest_rate') as $error)
							<span class="help-block">{{ $error }}</span>
						@endforeach
					@endif
				</div>
			</div>
			<div class="form-group @if($errors->has('bank')) has-error @endif">
				<label for="bank" class="control-label col-sm-3 col-md-2 col-lg-2">أسم البنك</label>
				<div class="col-sm-9 col-md-10 col-lg-10">
					<input type="text" name="bank" id="bank" value="{{$project->bank}}" class="form-control" placeholder="أدخل أسم البنك">
					@if($errors->has('bank'))
						@foreach($errors->get('bank') as $error)
							<span class="help-block">{{ $error }}</span>
						@endforeach
					@endif
				</div>
			</div>
			<div class="col-sm-2 col-md-2 col-lg-2 col-sm-offset-5 col-md-offset-5 col-lg-offset-5">
				<button class="btn btn-primary form-control" id="save_btn">حفظ</button>
			</div>
			@csrf
			@method('PUT')
		</form>
	</div>
</div>
@endsection
