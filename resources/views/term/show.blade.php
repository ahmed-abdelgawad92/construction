@extends('layouts.master')
@section('title','بيانات البند')
@section('content')
@if($term->deleted==0)
<div class="content">
	<div class="col-md-8 col-lg-8 col-sm-8 col-sm-offset-2 col-md-offset-0 col-lg-offset-0">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>بند تابع للمشروع <a href="{{route('showproject',$term->project->id)}}">{{$term->project->name}}</a> </h3>
		</div>
		<div class="panel-body">
			@if(session('success'))
				<div class="alert alert-success">
					<strong>{{ session('success') }}</strong>
					<br>
				</div>
			@endif
			<div class="row mb-1">
				<div class="col-xs-4 col-sm-3 col-md-2"><h4 class="box-heading-right">كود البند </h4></div>
				<div class="col-xs-8 col-sm-9 col-md-10"><h4 class="box-heading text-right">{{$term->code}}</h4></div>
			</div>
			<div class="row mb-1">
				<div class="col-xs-4 col-sm-3 col-md-2"><h4 class="box-heading-right">نوع البند </h4></div>
				<div class="col-xs-8 col-sm-9 col-md-10"><h4 class="box-heading text-right">{{$term->type}}</h4></div>
			</div>
			<div class="row mb-1">
				<div class="col-xs-4 col-sm-3 col-md-2"><h4 class="box-heading-right">بيان الأعمال </h4></div>
				<div class="col-xs-8 col-sm-9 col-md-10"><h4 class="box-heading text-right">{{$term->statement}}</h4></div>
			</div>
			<div class="row mb-1">
				<div class="col-xs-4 col-sm-3 col-md-2"><h4 class="box-heading-right">الوحدة </h4></div>
				<div class="col-xs-8 col-sm-9 col-md-10"><h4 class="box-heading text-right">{{$term->unit}}</h4></div>
			</div>
			<div class="row mb-1">
				<div class="col-xs-4 col-sm-3 col-md-2"><h4 class="box-heading-right">الكمية </h4></div>
				<div class="col-xs-8 col-sm-9 col-md-10"><h4 class="box-heading text-right">{{$term->amount}}</h4></div>
			</div>
			<div class="row mb-1">
				<div class="col-xs-4 col-sm-3 col-md-2"><h4 class="box-heading-right">الفئة </h4></div>
				<div class="col-xs-8 col-sm-9 col-md-10"><h4 class="box-heading text-right">{{$term->value}}</h4></div>
			</div>
			<div class="row mb-3">
				<div class="col-xs-4 col-sm-3 col-md-2"><h4 class="box-heading-right">الجملة</h4></div>
				<div class="col-xs-8 col-sm-9 col-md-10"><h4 class="box-heading text-right">{{$term->amount*$term->value}}</div>
			</div>
			<a href="{{route('addcontract',$term->id)}}" class="float btn btn-dark">عقد البند</a>
			<a href="{{route('addproduction',$term->id)}}" class="float btn btn-primary">أضافة أنتاج</a>
			<a href="{{route('addconsumption',$term->id)}}" class="float btn btn-primary">أضافة أستهلاك</a>
			<a href="{{route('addconsumption',$term->id)}}" class="float btn btn-primary">أضافة ملحوظة</a>
			<a href="{{route('updateterm',$term->id)}}" class="float btn btn-default">تعديل</a>
			<button type="button" data-toggle="modal" data-target="#delete" class="btn btn-danger float">حذف</button>
				<div class="modal fade" id="delete" tabindex="-1" role="dialog">
					<div class="modal-dialog modal-sm">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title">هل تريد حذف هذا البند {{$term->code}}</h4>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">لا
								</button>
								<a href="{{route('deleteterm',$term->id)}}" class="btn btn-danger">نعم</a>
							</div>
						</div>
					</div>
				</div>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>جميع المقاولين المتعاقد معهم في هذا البند</h3>
		</div>
		<div class="panel-body">
		@if (count($contracts)>0)
				<div class="row">
				@foreach ($contracts as $contract)
				<div class="col-md-12 col-lg-6">
				<div class="card mt-2">
				<div class="row">
					<div class="col-xs-4 col-sm-4 col-md-3 col-lg-4">
						<a href="#"><img src="{{asset('images/contractor.png')}}" class="w-100 contractor-img" alt=""></a>
					</div>
					<div class="col-xs-8 col-sm-8 col-md-9 col-lg-8">
							<div class="mb-2">
								<span class="label label-default">اسم المقاول</span>
								{{$contract->contractor->name}}
							</div>
							<div class="mb-2">
								<span class="label label-default">تليفون</span>
								{{$contract->contractor->phone}}
							</div>
							<div class="mb-2">
								<span class="label label-default">نوع المقاول</span>
								{{$contract->contractor->type}}
							</div>
							<div class="mb-2">
								<span class="label label-default">سعر الوحدة</span>
								{{$contract->unit_price}} جنيه
							</div>
							<div class="mb-2">
								<span class="label label-default">تاريخ بداية العقد</span>
								{{date('d/m/Y',strtotime($contract->started_at))}}
							</div>
						@if ($contract->ended_at!=null)
							<div class="mb-2">
								<span class="label label-default">تاريخ نهاية العقد</span>
								{{date('d/m/Y',strtotime($contract->ended_at))}}
							</div>
						@endif
					</div>
				</div>
				<div class="center mt-3">
					<button class="btn btn-dark show_contract" data-contract="{{nl2br($contract->contract_text)}}">أفتح العقد</button>
					<a href="{{route('updatecontract',['id'=>$term->id])}}" class="btn btn-default">تعديل العقد</a>
					<a href="{{route('endcontract',['id'=>$term->id])}}" class="btn btn-success">انهاءالعقد</a>
				</div>
				</div>
				</div>
				@endforeach
				</div>
		@else
			<div class="alert alert-warning">لا يوجد عقود مع مقاولين لهذا البند حتى الان <a href="{{route('addcontract',$term->id)}}" class="btn btn-warning">عقد البند</a></div>
		@endif
		</div>
	</div>
	</div>
	<div class="col-md-4 col-lg-4 col-sm-4">
	<div class="panel panel-default">
		<div class="panel-heading project-heading">
			<h3>أخر أنتاج لهذا البند</h3>
		</div>
		<div class="panel-body">
			@if(count($productions)>0)
			@foreach($productions as $production)
				<div class="bordered-right whole" title="أضغط للتعديل">
					<a href="{{route('updateproduction',['id'=>$production->id])}}" class="no-underline">
					<h4>تقييم
						@for ($i=0; $i < 10; $i++)
							@if ($i<$production->rate)
							<span class="glyphicon glyphicon-star"></span>
							@else
							<span class="glyphicon glyphicon-star-empty"></span>
							@endif
						@endfor
					</h4>
					<p>
						<span class="label label-default">كمية الأنتاج</span>
						 {{$production->amount}}
						@if($production->note!=null)
						<br>
						<span class="label label-default">ملحوظة</span>
						 {{$production->note}}
						@endif
						<br>
						<span class="label label-default">تاريخ الأنتاج</span>
						 {{date("d/m/Y",strtotime($production->created_at))}}
					</p>
					</a>
				</div>
			@endforeach
			<div class="row item" style="text-align: center;">
				<a href="{{ route('showtermproduction',$term->id) }}" class="btn btn-default">
					مجموع الأنتاج بالبند
				</a>
			</div>
			@else
				<div class="alert alert-warning">لا يوجد أنتاج <a href="{{ route('addproduction',$term->id) }}" class="btn btn-warning">أضافة أنتاج</a></div>
			@endif
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading project-heading">
			<h3>أخر أستهلاك لهذا البند</h3>
		</div>
		<div class="panel-body">
			@if(count($consumptions)>0)
			@foreach($consumptions as $consumption)
				<div class="bordered-right whole">
					<p>
						<span class="label label-default">نوع الخام</span>
						 {{$consumption->type}}
						<br>
						<span class="label label-default">كمية الأستهلاك</span>
						 {{$consumption->amount}}
						<br>
						<span class="label label-default">تاريخ الأستهلاك</span>
						 {{date("d/m/Y",strtotime($consumption->created_at))}}
					</p>
				</div>
			@endforeach
			<div class="row item" style="text-align: center;">
				<a href="{{ route('showtermproduction',$term->id) }}" class="btn btn-default">
					مجموع الأستهلاك بالبند
				</a>
			</div>
			@else
				<div class="alert alert-warning">لا يوجد أستهلاك <a href="{{ route('addconsumption',$term->id) }}" class="btn btn-warning">أضافة أستهلاك</a></div>
			@endif
		</div>
	</div>
	<div class="panel panel-warning">
		<div class="panel-heading">
			<h3>أخر ملاحظات مدونة بهذا البند</h3>
		</div>
		<div class="panel-body">
			@if(count($notes)>0)
			@foreach($notes as $note)
			<div class="row mb-4">
			<div class="col-xs-12">
				<div class="note">
					{{$note->note}}
					<div class="note-time">{{date("d/m/Y",strtotime($note->created_at))}}</div>
				</div>
			</div>
			</div>
			@endforeach
			<div class="row item" style="text-align: center;">
				<a href="{{ route('showtermproduction',$term->id) }}" class="btn btn-warning">
					جميع الاحظات بالبند
				</a>
			</div>
			@else
				<div class="alert alert-warning">لا يوجد ملاحظات <a href="{{ route('addconsumption',$term->id) }}" class="btn btn-warning">أضافة ملاحظة</a></div>
			@endif
		</div>
	</div>
	</div>
</div>
<div id="float_container">
	<div id="float_form_container">
		<span class="close">&times;</span>
		<h3 class="center">عقد كتابى بين المقاول و الشركة</h3>
		<p id="contract_term"></p>
	</div>
</div>
@else
<div class="alert alert-info mt-5 center">
	<img src="{{asset("images/deleted.png")}}" style="width:40%;" alt="">
	<h3 class="center">هذا  البند تم حذفه</h3>
	<a href="{{route('restoreterm',['id'=>$term->id])}}" class="btn btn-primary">استرجاع البند المحذوف</a>
</div>
@endif
@endsection
