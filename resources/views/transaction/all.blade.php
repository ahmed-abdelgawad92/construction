@extends('layouts.master')
@section('title')
@if(Route::current()->getName()=='alltransaction')
أجمالى مستخلصات المشروع
@elseif(Route::current()->getName()=='alltermtransaction')
أجمالى مستخلصات البند
@endif
@endsection
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			@if(Route::current()->getName()=='alltransaction')
			<h3>أجمالى مستخلصات مشروع <a href="{{ route('showproject',$project->id) }}">{{ $project->name }}</a></h3>
			@elseif(Route::current()->getName()=='alltermtransaction')
			<h3>أجمالى مستخلصات بند <a href="{{ route('showterm',$term->id) }}">{{ $term->code }}</a></h3>
			@endif
		</div>
		<div class="panel-body">
			@if(session('delete_error'))
			<div class="alert alert-danger">
				{{session('delete_error')}}
			</div>
			@endif
			@if(session('success'))
			<div class="alert alert-success">
				{{session('success')}}
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
			<div class="jumbotron">
				<h2 style="border-bottom: 1px solid #000; padding-bottom: 5px;">أجمالى المستخلصات الداخلة و الخارجة
				@if(Route::current()->getName()=='alltransaction')
				بالمشروع
				@elseif(Route::current()->getName()=='alltermtransaction')
				بالبند
				@endif
				</h2>
				<br><br>
				<div class="row">
					<div class="col-sm-6 col-md-4 col-lg-4 col-xs-12" style="margin-bottom: 10px;">
						<div class="circle-div">
							{{ $total_in }} جنيه
						</div>
						<p style="text-align: center; margin-top: 8px;">أجمالى المستخلصات</p>
					</div>
					<div class="col-sm-6 col-md-4 col-md-offset-4 col-lg-4 col-lg-offset-4 col-xs-12" style="margin-bottom: 10px;">
						<div class="circle-div">
							{{ $total_out }} جنيه
						</div>
						<p style="text-align: center; margin-top: 8px;">أجمالى المدفوع للمقاول</p>
					</div>
				</div>
			</div>
			@if(Route::current()->getName()=='alltransaction')
			@if(count($terms)>0)
			<h4 style="border-bottom: 1px solid #eee; padding-bottom: 5px;">تفاصيل المستخلصات الداخلة و الخارجة ( المدفوعة للمقاول )</h4>
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>#</th>
							<th>كود البند</th>
							<th>أسم المقاول</th>
							<th>نوع البند</th>
							<th>أجمالى كمية البند</th>
							<th>أجمالى كمية الأنتاج السابقة</th>
							<th>قيمة المستخلصات السابقة</th>
							<th>أجمالى المدفوع للمقاول</th>
						</tr>
					</thead>
					<tbody>
						<?php $count=1; ?>
						@foreach($terms as $term)
						<?php 
						$total_transaction=$term->transactions()->where('type','in')->sum('transactions.transaction'); 
						$total_contractor=$term->transactions()->where('type','out')->sum('transactions.transaction');
						?>
						<tr>
							<td>{{$count++}}</td>
							<td><a href="{{ route('alltermtransaction',$term->id) }}">{{$term->code}}</a></td>
							<td>{{$term->contractor->name}}</td>
							<td>{{$term->type}}</td>
							<td>{{$term->amount}} <span class="badge">{{$term->unit}}</span></td>
							<td>{{ $total_transaction/$term->value }} <span class="badge">{{$term->unit}}</span></td>
							<td>{{$total_transaction}} <span class="badge">جنيه</span></td>
							<td>{{$total_contractor }} <span class="badge">جنيه</span></td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			@else
			<div class="alert alert-warning">
				لا يوجد مستخلصات بهذا المشروع
			</div>
			@endif
			@elseif(Route::current()->getName()=='alltermtransaction')
			<h4 style="border-bottom: 1px solid #eee; padding-bottom: 5px;">تفاصيل المستخلصات الداخلة لهذا البند</h4>
			@if(count($transactions_in)>0)
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>#</th>
							<th>قيمة المستخلص</th>
							<th>الكمية</th>
							<th>تاريخ المستخلص</th>
							<th style="width: 100px;">تعديل</th>
							<th style="width: 100px;">حذف</th>
						</tr>
					</thead>
					<tbody>
						<?php $count=1; ?>
						@foreach($transactions_in as $transaction)
						<tr>
							<td>{{$count++}}</td>
							<td>{{$transaction->transaction}} <span class="badge">جنيه</span></td>
							<td>{{$transaction->transaction/$transaction->term->value}} <span class="badge">{{$transaction->term->unit}}</span></td>
							<td>{{$transaction->created_at->format('Y-m-d')}}</td>
							<td>
							<form method="post" action="{{ route('updatetransaction',$transaction->id) }}">
							<button type="button" data-toggle="modal" data-target="#update{{$transaction->id}}" class="btn btn-block btn-default">
								تعديل
							</button>
							<div class="modal fade" id="update{{$transaction->id}}" tabindex="-1" role="dialog">
								<div class="modal-dialog modal-sm">
									<div class="modal-content">
										<div class="modal-header">
											<h4 class="modal-title">هل تريد تعديل هذا المستخلص؟</h4>
										</div>
										<div class="modal-body">
											<div class="form-group">
												<label for="transaction" class="control-label">
													قيمة المستخلص
												</label>
												<input type="text" name="transaction" id="transaction" placeholder="أدخل قيمة المستخلص الجديدة" value="{{$transaction->transaction}}" class="form-control">
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
							</td>
							<td>
							<form method="post" action="{{ route('deletetransaction',$transaction->id) }}">
							<button type="button" data-toggle="modal" data-target="#delete{{$transaction->id}}" class="btn btn-block btn-danger">
								حذف
							</button>
							<div class="modal fade" id="delete{{$transaction->id}}" tabindex="-1" role="dialog">
								<div class="modal-dialog modal-sm">
									<div class="modal-content">
										<div class="modal-header">
											<h4 class="modal-title">هل تريد حذف هذا المستخلص؟</h4>
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
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			@else
			<div class="alert alert-warning">
				لا يوجد مستخلصات داخلة بهذا البند
			</div>
			@endif
			<h4 style="border-bottom: 1px solid #eee; padding-bottom: 5px;">تفاصيل المستخلصات الخارجة ( المدفوعة لمقاول البند )</h4>
			@if(count($transactions_out)>0)
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>#</th>
							<th>قيمة المستخلص</th>
							<th>تاريخ المستخلص</th>
							<th style="width: 100px;">تعديل</th>
							<th style="width: 100px;">حذف</th>
						</tr>
					</thead>
					<tbody>
						<?php $count=1; ?>
						@foreach($transactions_out as $transaction)
						<tr>
							<td>{{$count++}}</td>
							<td>{{$transaction->transaction}} <span class="badge">جنيه</span></td>
							<td>{{$transaction->created_at->format('Y-m-d')}}</td>
							<td>
							<form method="post" action="{{ route('updatetransaction',$transaction->id) }}">
							<button type="button" data-toggle="modal" data-target="#update{{$transaction->id}}" class="btn btn-block btn-default">
								تعديل
							</button>
							<div class="modal fade" id="update{{$transaction->id}}" tabindex="-1" role="dialog">
								<div class="modal-dialog modal-sm">
									<div class="modal-content">
										<div class="modal-header">
											<h4 class="modal-title">هل تريد تعديل هذا المستخلص؟</h4>
										</div>
										<div class="modal-body">
											<div class="form-group">
												<label for="transaction" class="control-label">
													قيمة المستخلص
												</label>
												<input type="text" name="transaction" id="transaction" placeholder="أدخل قيمة المستخلص الجديدة" value="{{$transaction->transaction}}" class="form-control">
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
							</td>
							<td>
							<form method="post" action="{{ route('deletetransaction',$transaction->id) }}">
							<button type="button" data-toggle="modal" data-target="#delete{{$transaction->id}}" class="btn btn-block btn-danger">
								حذف
							</button>
							<div class="modal fade" id="delete{{$transaction->id}}" tabindex="-1" role="dialog">
								<div class="modal-dialog modal-sm">
									<div class="modal-content">
										<div class="modal-header">
											<h4 class="modal-title">هل تريد حذف هذا المستخلص؟</h4>
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
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			@else
			<div class="alert alert-warning">
				لا يوجد مستخلصات خارجة بهذا البند
			</div>
			@endif
			@endif
		</div>
	</div>
</div>
@endsection