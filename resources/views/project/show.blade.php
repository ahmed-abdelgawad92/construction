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
				<li class="dropdown" id="raw_nav_item" role="presentation">
					<a data-toggle="dropdown" class="dropdown-toggle" role="button" href="">مخازن <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="#" class="navigate_to_div" data-nav-path="#raw">تقرير بالخامات و الاستهلاك</a></li>
						<li><a href="{{ route('addstores',['cid'=>0,'pid'=>$project->id]) }}">أضافة خامات</a></li>
						<li><a href="{{ route('allstores',$project->id) }}">عرض جميع الخامات بالمشروع</a></li>
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
					<a data-toggle="dropdown" class="dropdown-toggle" role="button" href="">ورقيات و رسومات <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="#">إضافة ورقية</a></li>
						<li><a href="#">جميع ورقيات المشروع</a></li>
						<li><a href="{{ route('addgraphs',$project->id) }}">أضافة رسم</a></li>
						<li><a href="{{ route('allgraph',$project->id) }}">عرض جميع الرسومات</a></li>
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
					@if (isset($stores) && count($stores)>0)
						<h4 style="border-bottom: 1px solid #eee; padding-bottom: 5px;">جميع الخامات الباقية و المستهلكة</h4>
						<div class="table-responsive">
							<table class="table table-bordered">
								<thead>
									<tr>
										<th>#</th>
										<th>النوع</th>
										<th>الوحدة</th>
										<th>اجمالي ما تم توريده</th>
										<th>اجمالي ما تم استخدامه</th>
										<th>الكمية الباقية</th>
									</tr>
								</thead>
								<tbody>
									@php $count=0;@endphp
									@foreach ($stores as $stock)
									<tr>
										<td>{{++$count}}</td>
										<td><a href="#">{{$stock->store_type}}</a></td>
										<td>{{$stock->unit}}</td>
										<td>{{$stock->store_amount??0}}</td>
										<td>{{$stock->consumed_amount??0}}</td>
										<td>{{($stock->store_amount-$stock->consumed_amount > 0)? $stock->store_amount-$stock->consumed_amount: 0}}</td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
						@else
							<div class="alert alert-warning mt-5">
								لا يوجد خامات او استهلاك بالمشروع
							</div>
						@endif
				</div>
				<div id="employee" class="hide">
					@if (count($employees)>0)
						@foreach ($employees as $employee)
						<div class="bordered-right border-primary mt-5">
							<div class="row">
							<div class="col-xs-9 col-sm-8 ">
								<table class="table table-striped borderless" style="margin-bottom:0 !important">
									<tr>
										<td>اسم الموظف</td>
										<td>{{$employee->name}}</td>
									</tr>
									<tr>
										<td>الوظيفة</td>
										<td>{{$employee->job}}</td>
									</tr>
									<tr>
										<td>المرتب</td>
										<td>{{$employee->pivot->salary}}</td>
									</tr>
									<tr>
										<td>تاريخ البدء</td>
										<td>{{date('d/m/Y',strtotime($employee->pivot->created_at))}}</td>
									</tr>
									<tr>
										<td>تاريخ انتهاء الوظيفة</td>
										<td> {{date('d/m/Y',strtotime($employee->pivot->ended_at))}}</td>
									</tr>
								</table>
							</div>
							<div class="col-xs-3 col-sm-4">
								<a href="#" class="btn btn-warning w-100 mt-3">انهاء الوظيفة</a>
								<a href="#" class="btn btn-warning w-100 mt-3">انهاء الوظيفة</a>
								<a href="#" class="btn btn-warning w-100 mt-3">انهاء الوظيفة</a>
							</div>
							</div>
						</div>
						@endforeach
					@else
						<div class="alert alert-warning mt-5">لا يوجد موظفيين منتدبين بهذا المشروع</div>
					@endif
				</div>
				<div id="production" class="hide">
					@if (isset($productions) && count($productions)>0)
						<div class="jumbotron mt-5">
							<h2 style="border-bottom: 1px solid #000; padding-bottom: 5px;">أجمالى أنتاج المشروع</h2>
							<br><br>
							<div class="row">
								<div class="col-sm-6 col-md-4 col-lg-4 col-xs-6" style="margin-bottom: 10px;">
								</div>
								<div class="col-sm-12 col-md-4 col-lg-4 col-xs-12" style="margin-bottom: 10px;">
									<div class="circle-div">
										{{ round($productionReport->rate,2) }}
									</div>
									<p style="text-align: center; margin-top: 8px;">متوسط تقييم الأنتاج</p>
								</div>
								<div class="col-sm-6 col-md-4 col-lg-4 col-xs-6" style="margin-bottom: 10px;">
								</div>
							</div>
							<h3 style="text-align: center;">النسبة المئوية لما تم أنتاجه من المشروع</h3>
							<div class="progress" style="margin-top: 15px">
								<div class="progress-bar" role="progressbar" aria-valuenow="" aria-valuemin="{{round(($productionReport->amount/$productionReport->total_amount)*100,2)}}" aria-valuemax="100" style="width: {{round(($productionReport->amount/$productionReport->total_amount)*100,2)}}%; min-width: 2em;">
									{{round(($productionReport->amount/$productionReport->total_amount)*100,2)}}%
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
									@php $count=0; @endphp
									@foreach ($productions as $production)
									<tr>
										<td>{{++$count}}</td>
										<td><a href="{{route('showterm',['id'=>$production->term_id])}}">{{$production->code}}</a></td>
										<td>{{$production->amount??0}} {{$production->unit}}</td>
										<td>
											@if (($production->term_amount - $production->amount)>0)
											{{($production->term_amount - $production->amount)}}
											@else
											0
											@endif
											{{$production->unit}}</td>
										<td>{{$production->term_amount}} {{$production->unit}}</td>
										<td>{{($production->amount/$production->term_amount)*100}} %</td>
										<td>{{round($production->rate,2)}}</td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					@else
						<div class="alert alert-warning mt-5">
							لا يوجد إنتاج بهذا المشروع حتى الان
						</div>
					@endif
				</div>
				<div id="supplier" class="hide">
					@if (isset($suppliers) && count($suppliers)>0)
						<h4 style="border-bottom: 1px solid #eee; padding-bottom: 5px;">جميع الموردين و الخامات التى وردوها</h4>
						<div class="table-responsive">
							<table class="table table-bordered">
								<thead>
									<tr>
										<th>#</th>
										<th>المورد</th>
										<th>نوع الخام</th>
										<th>الكمية الموردة</th>
										<th>سعر الوحدة</th>
										<th>السعر الكلي</th>
										<th>المبلغ المدفوع</th>
										<th>المبلغ الباقى</th>
										<th>تاريخ التوريد</th>
									</tr>
								</thead>
								<tbody>
									@php $count=0;@endphp
									@foreach ($suppliers as $supplier)
									<tr>
										<td>{{++$count}}</td>
										<td><a href="#">{{$supplier->name}}</a></td>
										<td>{{$supplier->type}}</td>
										<td>{{$supplier->amount}}</td>
										<td>{{$supplier->unit_price}}</td>
										<td>{{$supplier->total_price}}</td>
										<td>{{$supplier->paid}}</td>
										<td>{{$supplier->total_price-$supplier->paid}}</td>
										<td>{{date('d/m/Y',strtotime($supplier->created_at))}}</td>
									</tr>
									@endforeach
								</tbody>
							</table>
							@if($count==10)
							<caption><a href="">جميع الموردين لهذا المشروع و تقرير كامل بجميع المعاملات </a></caption>
							@endif
						</div>
					@else
						<div class="alert alert-warning mt-5">لم يتم توريد خامات إلى هذا المشروع حتى الان</div>
					@endif
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
				<a href="{{ route("notstartedterms",['id'=>$project->id]) }}" class="btn btn-default">
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
				<a href="#" class="btn btn-danger my-2 mx-1 width-100">تعطيل</a>
				<a href="#" class="btn btn-success my-2 mx-1 width-100">انهاء</a>
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
		<div class="panel-body">
			@if(count($disabledTerms)>0)
			@foreach($disabledTerms as $term)
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
				<a href="#" class="btn btn-success my-2 mx-1 width-100">تفعيل</a>
			</div>
			@endforeach
			<div class="row item" style="text-align: center;">
				<a href="{{ url('term/all/notStarted') }}" class="btn btn-default">
					جميع البنود المعطلة
				</a>
			</div>
			@else
				<div class="alert alert-warning">لا يوجد بنود معطلة</div>
			@endif
		</div>
	</div>
	<!--__________________/OffTerms______________________-->
	</div>
</div>
</div>

<div id="float_container">
	<div id="float_form_container">
		<form class="float_form" method="post" action="{{route('add_cash_box',['id'=>$project->id])}}" id="add_cash_box">
			<span class="close">&times;</span>
			<h3 class="center mb-5">إضافة قيمة الصندوق</h3>
			<div class="form-group @if($errors->has('cash_box')) has-error @endif">
				<label for="cash_box" class="control-label">صندوق المال</label>
				<div class="">
					<input type="text" name="cash_box" id="cash_box" value="{{$project->cash_box}}" class="form-control number" placeholder="أدخل رأس مال الصندوق">
					@if($errors->has('cash_box'))
						@foreach($errors->get('cash_box') as $error)
							<span class="help-block">{{ $error }}</span>
						@endforeach
					@endif
				</div>
			</div>
			<div class="center">
				<button class="btn btn-primary form-control" style="width:50%" id="save_btn">حفظ</button>
			</div>
			@csrf
			@method('PUT')
		</form>
		<form class="float_form" method="post" action="{{route('add_loan',['id'=>$project->id])}}" id="add_loan">
			<span class="close">&times;</span>
			<h3 class="center mb-5">إضافة قرض</h3>
			<div class="form-group @if($errors->has('loan')) has-error @endif">
				<label for="loan" class="control-label ">قيمة القرض</label>
				<div class="">
					<input type="text" name="loan" id="loan" value="{{$project->loan}}" class="form-control number" placeholder="أدخل قيمة القرض">
					@if($errors->has('loan'))
						@foreach($errors->get('loan') as $error)
							<span class="help-block">{{ $error }}</span>
						@endforeach
					@endif
				</div>
			</div>
			<div class="form-group @if($errors->has('loan_interest_rate')) has-error @endif">
				<label for="loan_interest_rate" class="control-label ">نسبة الفائدة</label>
				<div class="">
					<input type="text" name="loan_interest_rate" id="loan_interest_rate" value="{{$project->loan_interest_rate}}" class="form-control number" placeholder="أدخل نسبة فائدة القرض">
					@if($errors->has('loan_interest_rate'))
						@foreach($errors->get('loan_interest_rate') as $error)
							<span class="help-block">{{ $error }}</span>
						@endforeach
					@endif
				</div>
			</div>
			<div class="form-group @if($errors->has('bank')) has-error @endif">
				<label for="bank" class="control-label">أسم البنك</label>
				<div class="">
					<input type="text" name="bank" id="bank" value="{{$project->bank}}" class="form-control" placeholder="أدخل أسم البنك">
					@if($errors->has('bank'))
						@foreach($errors->get('bank') as $error)
							<span class="help-block">{{ $error }}</span>
						@endforeach
					@endif
				</div>
			</div>
			<div class="center">
				<button class="btn btn-primary form-control" style="width:50%" id="save_btn">حفظ</button>
			</div>
			@csrf
			@method('PUT')
		</form>
	</div>
</div>
@endsection
