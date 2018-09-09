@extends('layouts.master')
@section('title','جميع التعاملات')
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>جميع تعاملات @if(isset($user)) الحساب {{$user->name}} @else النظام @endif </h3>
		</div>
		<div class="panel-body">
			@if(count($logs)>0)
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
					<tr>
						<th>#</th>
						<th>الحدث</th>
						<th>الجدول</th>
						<th>وقت الحدث</th>
						@if(!isset($user))
						<th>أسم المستخدم</th>
						@endif
					</tr>
					</thead>
					<tbody>
					<?php $count=1; ?>
						@foreach($logs as $log)
						<tr>
							<td>{{$count++}}</td>
							<td>
								<a href="">
								{{$log->action}}
								</a>
							</td>
							<td>
								@if($log->table=="project")
									المشروعات
								@elseif($log->table=="term")
									البنود
								@elseif($log->table=="transaction")
									المستخصات
								@elseif($log->table=="store")
									المخازن
								@elseif($log->table=="production")
									الأنتاج
								@elseif($log->table=="consumption")
									الأستهلاك
								@elseif($log->table=="note")
									الملاحيظ
								@elseif($log->table=="supplier")
									الموردون
								@elseif($log->table=="contractor")
									المقاولون
								@elseif($log->table=="expense")
									الأكراميات
								@elseif($log->table=="graph")
									الرسومات
								@elseif($log->table=="organization")
									العملاء
								@elseif($log->table=="store_type")
									أنواع الخامات
								@elseif($log->table=="term_type")
									أنواع البنود
								@elseif($log->table=="user")
									حسابات المستخدميين
								@elseif($log->table=="tax")
									الضرائب
								@elseif($log->table=="employee")
									الموظفيين
								@elseif($log->table=="advance")
									السلفات
								@endif
							</td>
							<td>{{$log->created_at->format('Y-m-d')}}</td>
							@if(!isset($user))
							<td><a href="{{ route('showuser',$log->user->id) }}"></a></td>
							@endif
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			{!!$logs->links()!!}
			@else
			<div class="alert alert-warning">
				لا يوجد تعاملات من هذا الحساب
			</div>
			@endif
		</div>
	</div>
</div>
@endsection
