@extends('layouts.master')
@section('title','بيانات المورد')
@section('content')
<div class="content">
<div class="row">
	<div class="col-md-8 col-lg-8 col-sm-8 col-sm-offset-2 col-md-offset-0 col-lg-offset-0">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>المورد {{$supplier->name}} <span class="badge left">تقييم <br>
			<span class="glyphicon glyphicon-star star"></span>
			<span class="glyphicon glyphicon-star star"></span>
			<span class="glyphicon glyphicon-star star"></span>
			<span class="glyphicon glyphicon-star star"></span>
			<span class="glyphicon glyphicon-star star"></span>
			10</span></h3>
		</div>
		<div class="panel-body">
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
						<a href="#notes" aria-controls="notes" role="tab" data-toggle="tab">
							ملاحيظ
						</a>
					</li>
				</ul>
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane active" id="profile">
						<div class="table-responsive">
						<table class="table table-striped">
						<tr><th class="min100">نوع المورد </th><td>{{$supplier->type}}</td></tr>
						@if(!empty($supplier->address))
						<tr><th class="min100">الشارع </th><td>{{$supplier->address}}</td></tr>
						@endif
						@if(!empty($supplier->center))
						<tr><th class="min100">المركز </th><td>{{$supplier->center}}</td></tr>
						@endif
						<tr><th class="min100">المدينة </th><td>{{$supplier->city}}</td></tr>
						<tr><th class="min100">التليفون </th><td>{{$supplier->phone}}</td></tr>
						</table>
						</div>
					</div>
					<div role="tabpanel" class="tab-pane" id="notes">

					</div>
				</div>
			</div>
			<a href="" class="m-top btn btn-primary float">شراء خامات</a>
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
	<div class="col-md-4 col-lg-4 col-sm-4">
	<div class="panel panel-default">
		<div class="panel-heading navy-heading">
			<h3>الخامات الواردة</h3>
		</div>
		<div class="panel-body">
			@if(count($stores)>0)
			@foreach($stores as $store)
				<div class="bordered-right border-navy" style="padding:0 5px 5px 0">
					<a href="{{ route('showterm',$store->id) }}" class="whole">
					<h4>
						أسم المشروع : {{$store->project->name}}<br>
						كود البند	: {{$store->code}}<br>
					 	نوع البند	: {{$store->type}}
					</h4>
					<p>
						<span class="label label-default">بيان الأعمال</span>
						 {{$store->statement}}
					</p>
					</a>
				</div>
			@endforeach
			<div class="row item" style="text-align: center;">
				<a href="{{route('SuppliedStores',$supplier->id)}}"class="btn btn-default">
					جميع الخامات الواردة
				</a>
			</div>
			@else
				<div class="alert alert-warning">لا يوجد خامات واردة من هذا المورد</div>
			@endif
		</div>
	</div>
	</div>
</div>
</div>
@endsection
