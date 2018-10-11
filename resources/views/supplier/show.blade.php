@extends('layouts.master')
@section('title','بيانات المورد')
@section('content')
<div class="content">
<div class="row">
	<div class="col-md-8 col-lg-8 col-sm-12 col-md-offset-0 col-lg-offset-0">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>المورد {{$supplier->name}}</h3>
		</div>
		<div class="panel-body">
			@if(session('insert_error'))
			<div class="alert alert-danger">
				{{session('insert_error')}}
			</div>
			@endif
			@if(session('success'))
			<div class="alert alert-success">
				{{session('success')}}
			</div>
			@endif
			<div>
				<ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="active">
						<a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">
							بيانات المورد
						</a>
					</li>
					<li role="presentation">
						<a href="#payments" aria-controls="payments" role="tab" data-toggle="tab" onclick="adjustCircles()">
							الحساب المالى
						</a>
					</li>
				</ul>
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane my-5 active" id="profile">
						<div class="table-responsive">
						<table class="table table-striped">
						<tr><th class="min100">نوع المورد </th><td>{{str_replace(","," , ",$supplier->type)}}</td></tr>
						@if(!empty($supplier->address))
						<tr><th class="min100">الشارع </th><td>{{$supplier->address}}</td></tr>
						@endif
						@if(!empty($supplier->center))
						<tr><th class="min100">المركز </th><td>{{$supplier->center}}</td></tr>
						@endif
						<tr><th class="min100">المدينة </th><td>{{$supplier->city}}</td></tr>
						<tr><th class="min100">التليفون </th><td>{{str_replace(","," , ",$supplier->phone)}}</td></tr>
						</table>
						</div>
					</div>
					<div role="tabpanel" class="tab-pane my-5" id="payments">
						<div class="jumbotron">
							<h2 style="border-bottom: 1px solid #000; padding-bottom: 5px;">أجمالى حساب المورد منذ أنشائه</h2>
							<br><br>
							<div class="row">
								<div class="col-sm-12 col-md-10 col-lg-6 col-xl-4 offset-md-1 offset-lg-0" style="margin-bottom: 10px;">
									<div class="circle-div">
										{{ Str::number_format(round($allRaws[0]->value,2)) }} جنيه
									</div>
									<p style="text-align: center; margin-top: 8px;">سعر جملة الواردات</p>
								</div>
								<div class="col-sm-12 col-md-10 col-lg-6 col-xl-4 offset-md-1 offset-lg-0" style="margin-bottom: 10px;">
									<div class="circle-div">
										{{Str::number_format(round($allRaws[0]->paid,2))}} جنيه
									</div>
									<p style="text-align: center; margin-top: 8px;">أجمالى المدفوع</p>
								</div>
								<div class="col-sm-12 col-md-10 col-lg-6 col-xl-4 offset-md-1 offset-lg-0" style="margin-bottom: 10px;">
									<div class="circle-div">
										{{ Str::number_format(round($allRaws[0]->value - $allRaws[0]->paid,2)) }} جنيه
									</div>
									<p style="text-align: center; margin-top: 8px;">أجمالى المبلغ الباقى للمورد</p>
								</div>
							</div>
						</div>
						@if($lastTenRaws[0]->value!=$allRaws[0]->value)
						<div class="jumbotron">
							<h2 style="border-bottom: 1px solid #000; padding-bottom: 5px;">أجمالى حساب أخر عشر واردات فقط</h2>
							<br><br>
							<div class="row">
								<div class="col-sm-12 col-md-10 col-lg-6 col-xl-4 offset-md-1 offset-lg-0" style="margin-bottom: 10px;">
									<div class="circle-div">
										{{ Str::number_format(round($lastTenRaws[0]->value,2)) }} جنيه
									</div>
									<p style="text-align: center; margin-top: 8px;">سعر جملة الواردات</p>
								</div>
								<div class="col-sm-12 col-md-10 col-lg-6 col-xl-4 offset-md-1 offset-lg-0" style="margin-bottom: 10px;">
									<div class="circle-div">
										{{Str::number_format(round($lastTenRaws[0]->paid,2))}} جنيه
									</div>
									<p style="text-align: center; margin-top: 8px;">أجمالى المدفوع</p>
								</div>
								<div class="col-sm-12 col-md-10 col-lg-6 col-xl-4 offset-md-1 offset-lg-0" style="margin-bottom: 10px;">
									<div class="circle-div">
										{{ Str::number_format(round($lastTenRaws[0]->value - $lastTenRaws[0]->paid,2)) }} جنيه
									</div>
									<p style="text-align: center; margin-top: 8px;">أجمالى المبلغ الباقى للمورد</p>
								</div>
							</div>
						</div>
						@endif
						<table class="table table-bordered">
							<thead>
								<th>#</th>
								<th>النوع</th>
								<th>الكمية</th>
								<th>الجملة</th>
								<th>المدفوع</th>
								<th>الباقي</th>
							</thead>
							<tbody>
								@php
									$count = 1;
								@endphp
								@foreach ($raws as $raw)
									<tr>
										<th>{{$count++}}</th>
										<th>{{$raw->type}}</th>
										<th>{{Str::number_format($raw->amount)." ".$raw->unit}}</th>
										<th>{{Str::number_format($raw->value)}} جنيه</th>
										<th>{{Str::number_format($raw->paid)}} جنيه</th>
										<th>{{Str::number_format($raw->value-$raw->paid)}} جنيه</th>
									</tr>
								@endforeach
							</tbody>
						</table>
						<div class="center"><a href="{{route('SuppliedStores',$supplier->id)}}" class="btn btn-primary">جميع الخامات الواردة بالتفاصيل</a></div>
					</div>
				</div>
			</div>
			<a href="{{route("addstores",['cid'=>$supplier->id])}}" class="m-top btn btn-primary float">شراء خامات</a>
			<a href="{{ route('updatesupplier',$supplier->id) }}" class="m-top btn btn-default float">تعديل</a>
			<form class="float" method="post" action="{{ route('deletesupplier',$supplier->id) }}">
				<button type="button" data-toggle="modal" data-target="#delete" class="btn m-top btn-danger">حذف</button>
				<div class="modal fade" id="delete" tabindex="-1" role="dialog">
					<div class="modal-dialog modal-sm">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title">هل تريد حذف المورد {{$supplier->name}}؟</h4>
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
	<div class="col-md-4 col-lg-4 col-sm-12">
	<div class="panel panel-default">
		<div class="panel-heading navy-heading">
			<h3>الخامات الواردة</h3>
		</div>
		<div class="panel-body">
			@if(count($stores)>0)
			@foreach($stores as $store)
				<div class="bordered-right border-navy">
					<a href="{{ route('showproject',$store->project->id) }}" class="whole" title="أفتح المشروع">
					<h4>
						أسم المشروع : {{$store->project->name}}<br>
						الكمية	: {{Str::number_format($store->amount)}} {{$store->unit}}<br>
					 	نوع الخام	: {{$store->type}}
					</h4>
					</a>
				</div>
			@endforeach
			<div class="center">
				<a href="{{route('SuppliedStores',$supplier->id)}}"class="btn btn-default">
					جميع الخامات الواردة
				</a>
			</div>
			@else
				<div class="alert alert-warning">لا يوجد خامات واردة من هذا المورد</div>
			@endif
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading navy-heading">
			<h3>المبالغ المدفوعة</h3>
		</div>
		<div class="panel-body">
			@if(count($payments)>0)
			@foreach($payments as $payment)
				<div class="bordered-right border-navy">
					<h4 class="pr-3"><span class="label label-default">المبلغ المدفوع</span>  {{Str::number_format($payment->payment_amount)}} جنيه <span class="label label-default">نوع الدفع</span>  @if($payment->payment_type) قرض @else صندوق @endif</h4>
					<h4 class="pr-3"><span class="label label-default">تاريخ الدفع</span>  {{date("d/m/Y",strtotime($payment->created_at))}} <span class="label label-default">نوع الخام</span>  {{$payment->type}} </h4>
					<h4 class="pr-3"><span class="label label-default">أسم المشروع</span>  <a href="{{route("showproject",['id'=>$payment->project_id])}}">{{$payment->name}}</a></h4>
				</div>
			@endforeach
			<div class="center">
				<a href="{{route('allsupplierPayments',$supplier->id)}}"class="btn btn-default">
					جميع المبالغ المدفوعة
				</a>
			</div>
			@else
				<div class="alert alert-warning">لا يوجد مبالغ مدفوعة إلى هذا المورد حتى الان</div>
			@endif
		</div>
	</div>
	</div>
</div>
</div>
@endsection
