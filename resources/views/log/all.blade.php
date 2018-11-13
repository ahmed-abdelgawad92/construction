@extends('layouts.master')
@section('title','جميع التعاملات')
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>جميع تعاملات @if(isset($user)) الحساب <a href="{{route('showuser',['id'=>$user->id])}}">{{$user->name}}</a> @else النظام @endif </h3>
		</div>
		<div class="panel-body">
			@if(count($logs)>0)
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
					<tr>
						<th>#</th>
						@if(!isset($user))
							<th>أسم المستخدم</th>
						@endif
						<th>الحدث</th>
						<th>الجدول</th>
						<th>الفعل</th>
						<th style="white-space: nowrap;">وقت الحدث</th>
					</tr>
					</thead>
					<tbody>
					<?php $count= (($_GET['page']??1)-1) * 30 + 1; ?>
						@foreach($logs as $log)
						<tr>
							<td>{{$count++}}</td>
							@if(!isset($user))
								<td><a href="{{ route('showuser',$log->user_id) }}">{{$log->user->username}}</a></td>
							@endif
							<td>
								{{$log->getAction()}}
							</td>
							<td>
								@if($log->table_name == "projects")
									المشروعات
								@elseif($log->table_name == "terms")
									البنود
								@elseif($log->table_name == "transactions")
									المستخصات
								@elseif($log->table_name == "stores")
									المخازن
								@elseif($log->table_name == "productions")
									الأنتاج
								@elseif($log->table_name == "consumptions")
									الأستهلاك
								@elseif($log->table_name == "notes")
									الملاحيظ
								@elseif($log->table_name == "contracts")
									العقود
								@elseif($log->table_name == "suppliers")
									الموردون
								@elseif($log->table_name == "contractors")
									المقاولون
								@elseif($log->table_name == "expenses")
									الأكراميات
								@elseif($log->table_name == "graphs")
									الرسومات
								@elseif($log->table_name == "organizations")
									العملاء
								@elseif($log->table_name == "store_types")
									أنواع الخامات
								@elseif($log->table_name == "term_types")
									أنواع البنود
								@elseif($log->table_name == "users")
									حسابات المستخدميين
								@elseif($log->table_name == "taxes")
									الضرائب
								@elseif($log->table_name == "employees")
									الموظفيين
								@elseif($log->table_name == "advances")
									السلفات
								@elseif($log->table_name == "payments")
									المصروفات
								@endif
							</td>
							<td>{{$log->description}}<br>{!!$log->getAffectedRow() ? $log->getAffectedRow()->extractLogLink() : null !!}</td>
							<td>{{$log->created_at->format('يوم d/m/Y الساعة H:i')}}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			<div class="center">{!!$logs->links()!!}</div>
			@else
			<div class="alert alert-warning">
				لا يوجد تعاملات من هذا الحساب
			</div>
			@endif
		</div>
	</div>
</div>
@endsection
