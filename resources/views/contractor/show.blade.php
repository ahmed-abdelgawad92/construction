@extends('layouts.master')
@section('title','بيانات المقاول '.$contractor->name)
@section('content')
<div class="content">
<div class="row">
	<div class="col-md-8 col-lg-8 col-sm-8 col-sm-offset-2 col-md-offset-0 col-lg-offset-0">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="overflow-hidden">
				<img src="{{asset("images/contractor.png")}}" class="width-50 ml-3" alt="">
				المقاول {{$contractor->name}}
			@if($rate[0]->length>0)
				@php
					$rate_avg = ($rate[0]->rate/2)+0;
				@endphp
				<div class="badge left"><h5 class="center">تقييم {{Str::number_format($rate_avg)}}/5</h5>
				@for ($i=0; $i < 5; $i++)
					@if ($i<=$rate_avg)
						@if ($rate_avg - $i < 1 && $rate_avg - $i > 0)
						<span class="glyphicon glyphicon-star half"></span>
						@elseif($rate_avg - $i == 0)
						<span class="glyphicon glyphicon-star empty"></span>
						@else
						<span class="glyphicon glyphicon-star"></span>
						@endif
					@else
						<span class="glyphicon glyphicon-star empty"></span>
					@endif
				@endfor
			</div>
			@else
				<span class="badge left">لا يوجد تقييم</span>
			@endif
			</h3>
		</div>
		<div class="panel-body">
			<div>
				<ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="active">
						<a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">
							بيانات المقاول
						</a>
					</li>
					<li role="presentation">
						<a href="#notes" aria-controls="notes" role="tab" data-toggle="tab">
							ملاحيظ
						</a>
					</li>
				</ul>
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane active pt-3" id="profile">
						@if(session('success'))
						<div class="alert alert-success">
							{{session('success')}}
						</div>
						@endif
						<div class="table-responsive">
						<table class="table table-striped">
						<tr><th class="min100">نوع المقاول </th><td>{{str_replace(","," , ",$contractor->type)}}</td></tr>
						@if(!empty($contractor->address))
						<tr><th class="min100">الشارع </th><td>{{$contractor->address}}</td></tr>
						@endif
						@if(!empty($contractor->center))
						<tr><th class="min100">المركز </th><td>{{$contractor->center}}</td></tr>
						@endif
						<tr><th class="min100">المدينة </th><td>{{$contractor->city}}</td></tr>
						<tr><th class="min100">التليفون </th><td>{{str_replace(","," , ",$contractor->phone)}}</td></tr>
						</table>
						</div>
					</div>
					<div role="tabpanel" class="tab-pane pt-3" id="notes">
						@if (count($productionNotes)>0)
						@foreach ($productionNotes as $productionNote)
							<div class="bordered-right border-navy">
								<a href="" class="whole">
									<h4 id="callout-progress-csp">{{date("d/m/Y",strtotime($productionNote->date))}}</h4>
									<p>{!!nl2br(htmlspecialchars($productionNote->note))!!}</p>
								</a>
							</div>
						@endforeach
						@else
							<div class="alert alert-warning">لا يوجد ملحوظات</div>
						@endif
					</div>
				</div>
			</div>
			<a href="" class="m-top btn btn-primary float">عقد بند</a>
			<a href="{{ route('updatecontractor',$contractor->id) }}" class="m-top btn btn-default float">تعديل</a>
			<form class="float" method="post" action="{{ route('deletecontractor',$contractor->id) }}">
				<button type="button" data-toggle="modal" data-target="#delete" class="btn m-top btn-danger">حذف</button>
				<div class="modal fade" id="delete" tabindex="-1" role="dialog">
					<div class="modal-dialog modal-sm">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title">هل تريد حذف المقاول {{$contractor->name}}</h4>
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
	<div class="panel panel-default">
		<div class="panel-heading navy-heading">
			<h3>البنود المتعاقد عليها</h3>
		</div>
		<div class="panel-body">
			@if(count($contracts)>0)
			@foreach($contracts as $contract)
				<div class="bordered-right border-navy">
					<a href="{{ route('showterm',$contract->term_id) }}" class="whole">
					<h4>
						أسم المشروع : {{$contract->term->project->name}}<br>
						كود البند	: {{$contract->term->code}}<br>
					 	نوع البند	: {{$contract->term->type}}
					</h4>
					<p>
						<span class="label label-default">بيان الأعمال</span>
						 {{$contract->term->statement}}
					</p>
					</a>
					<a href="{{route('addcontracttransaction',['id'=>$contract->id])}}" class="btn btn-primary mr-3 mb-3">أضافة معاملة مالية</a>
					<a href="{{route('allcontracttransaction',['id'=>$contract->id])}}" class="btn btn-primary mb-3">جميع المبالغ المدفوعة</a>
				</div>
			@endforeach
			<div class="center mt-3">
				<a href="{{route('ContractedTerms',$contractor->id)}}"class="btn btn-default">
					جميع البنود المتعاقد عليها
				</a>
			</div>
			@else
				<div class="alert alert-warning">لا يوجد بنود متعاقد عليها مع هذا المقاول</div>
			@endif
		</div>
	</div>
	</div>
	<div class="col-md-4 col-lg-4 col-sm-4">
	<div class="panel panel-default">
		<div class="panel-heading navy-heading">
			<h3>اخر ثلاث أنتاجات</h3>
		</div>
		<div class="panel-body">
			@if(count($productions)>0)
			@foreach($productions as $production)
				<div class="bordered-right border-navy whole" title="أذهب إلى صفحة البند">
					<a href="{{route('showterm',['id'=>$production->term()->id])}}" class="no-underline">
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
						<span class="label label-default">أسم المشروع</span>
						{{$production->term()->project->name}}
						<br>
						<span class="label label-default">كود البند</span>
						{{$production->term()->code}}
						<br>
						<span class="label label-default">كمية الأنتاج</span>
						 {{$production->amount}} {{$production->term()->unit}}
						<br>
						<span class="label label-default">تاريخ الأنتاج</span>
						 {{date("d/m/Y",strtotime($production->created_at))}}
						 @if($production->note!=null)
							 <br>
							 <span class="label label-default">ملحوظة</span>
							 {{$production->note}}
						 @endif
					</p>
					</a>
				</div>
			@endforeach
			<div class="center">
				<a href="{{route("contractorproductions",['id'=>$contractor->id])}}" class="btn btn-default">
					جميع الأنتاج الذي قام به المقاول
				</a>
			</div>
			@else
				<div class="alert alert-warning">لا يوجد أنتاج حتى آلان لهذا المقاول</div>
			@endif
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading navy-heading">
			<h3>اخر ثلاث عقود</h3>
		</div>
		<div class="panel-body">
			@if(count($contracts)>0)
			@foreach($contracts as $contract)
				<div class="bordered-right border-navy whole" title="أذهب إلى صفحة البند">
					<a href="{{route('showterm',['id'=>$contract->term->id])}}" class="no-underline">
					<p>
						<span class="label label-default">أسم المشروع</span>
						{{$contract->term->project->name}}
						<br>
						<span class="label label-default">كود البند</span>
						{{$contract->term->code}}
						<br>
						<span class="label label-default">نوع البند</span>
						 {{$contract->amount}} {{$contract->term->unit}}
						<br>
						<span class="label label-default">تاريخ  بداية العقد</span>
						 {{date("d/m/Y",strtotime($contract->started_at))}}
						 @if($contract->ended_at!=null)
							 <br>
							 <span class="label label-default">تاريخ نهاية العقد</span>
							 {{date("d/m/Y",strtotime($contract->ended_at))}}
						 @endif
						 @if($contract->contract_text!=null)
							 <br>
							 <span class="label label-default">نص العقد</span>
							 {!!nl2br(htmlspecialchars($contract->contract_text))!!}
						 @endif
					</p>
					</a>
					<a href="{{route('addcontracttransaction',['id'=>$contract->id])}}" class="btn btn-primary">أضافة معاملة مالية</a>
					<a href="{{route('allcontracttransaction',['id'=>$contract->id])}}" class="btn btn-primary">جميع المبالغ المدفوعة</a>
				</div>
			@endforeach
			<div class="center">
				<a href="{{route('ContractedTerms',$contractor->id)}}" class="btn btn-default">
					جميع البنود المتعاقد عليها
				</a>
			</div>
			@else
				<div class="alert alert-warning">لا يوجد بنود متعاقد عليها مع هذا المقاول</div>
			@endif
		</div>
	</div>
	</div>
</div>
</div>
@endsection
