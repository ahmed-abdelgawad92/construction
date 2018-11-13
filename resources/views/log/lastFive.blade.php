@extends('layouts.master')
@section('title','جميع التعاملات')
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>جميع تعاملات النظام</h3>
		</div>
		<div class="panel-body">
			<div class="row">
				@foreach($logs as $key => $values)
				<div class="col-sm-6 col-md-4 col-lg-3 mb-5">
				<div class="panel panel-default"  style="padding-bottom:30px; position:relative; height:100%;">
					<div class="panel-heading">
						<h3>جميع التعاملات بجدول
							@if($key == "projects")
								المشروعات
							@elseif($key == "terms")
								البنود
							@elseif($key == "transactions")
								المستخصات
							@elseif($key == "stores")
								المخازن
							@elseif($key == "productions")
								الأنتاج
							@elseif($key == "consumptions")
								الأستهلاك
							@elseif($key == "notes")
								الملاحيظ
							@elseif($key == "contracts")
								العقود
							@elseif($key == "suppliers")
								الموردون
							@elseif($key == "contractors")
								المقاولون
							@elseif($key == "expenses")
								الأكراميات
							@elseif($key == "graphs")
								الرسومات
							@elseif($key == "organizations")
								العملاء
							@elseif($key == "company_employees")
								موظفيين الشركة
							@elseif($key == "employees")
								الموظفيين المنتدبين
							@elseif($key == "store_types")
								أنواع الخامات
							@elseif($key == "term_types")
								أنواع البنود
							@elseif($key == "users")
								حسابات المستخدميين
							@elseif($key == "taxes")
								الضرائب
							@elseif($key == "employees")
								الموظفيين
							@elseif($key == "advances")
								السلفات
							@elseif($key == "papers")
								الورقيات
							@elseif($key == "payments")
								المصروفات
							@endif
						</h3>
					</div>
					<div class="panel-body">
						@if(count($values)>0)
							@foreach ($values as $log)
								<div class="bordered-right border-primary p-3">
									<p>نوع العملية: {{$log->getAction()}}</p>
									<p>وصف العملية: {{$log->description}}</p>
									<p>{!!$log->getAffectedRow() ? $log->getAffectedRow()->extractLogLink() : null !!}</p>
								</div>
							@endforeach
							<div class="center" style="position:absolute; bottom:20px; left:0; right:0;">
								<a href="{{route('alllogstable',['table'=>$key])}}" class="btn btn-default">المزيد من التعاملات بالجدول</a>
							</div>
						@else
						<div class="alert alert-warning">لا يوجد تعاملات لهذا الجدول على النظام حنى الان</div>
						@endif
					</div>
				</div>
				</div>
				@endforeach
		</div>
		</div>
	</div>
</div>
@endsection
