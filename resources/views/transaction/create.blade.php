@extends('layouts.master')
@section('title','أنشاء مستخلص')
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>أنشاء مستخلص تجريبى للمشروع <a href="{{ route('showproject',$project->id) }}">{{$project->name}}</a><a data-html="true" tabindex="0" id="info" role="button" data-toggle="popover" data-trigger="focus" title="معلومات يجب معرفتها للتعامل مع المستخلص بهذا النظام" data-content="1.ضع علامة صح على البنود التى تم محاسبتك على أنتاجها الحالى, ,ان تم محاسبتك على أنتاج أقل أو أكثر مما موجود بالنظام ,فقط أدخل الكمية التى تم محاسبتك عليها فى مدخل الكمية الحالية الخاص بالبند.<br>2.القيم السالبة بالكمية المنتجة الحالية تعنى أنك قد أخذت مستحقات أكثر مما أنتجت بالبند.<br>3.أذا كانت القيمة صفر أذاً أنت أخذت مستحقات كل ما أنتجت بالبند. <br>4.أذا كانت القيمة أكثر من الصفر أذاً الكمية الحالية المنتجة لم تتم محاسبتك عليها. <br>5.أذا أردت أظهار القيم المنتجة المبنية على ما تم أخذ مستحقاته أضغط على زر ضبط قيم المستخلص, هذا فى حالة أنك تريد أن ترى المستخلص بوجهة نظر العميل أو الهيئة المختصة. <br>6.ما يتم أظهاره هنا هو قيمة المستخلص على حسب أنتاجك, و هذا يفسر القيم السالبة التى شرحناها.<br>7.لا تقلق من القيم السالبة أذا أردت حفظ المستخلص كما تم أصداره من النظام ,سيتم تجاهلها برمجياً, لذا فقط ضع علامة صح على الكل و النظام سيحفظ المستخلص.<br><br>8.المستخلص يُظهر فقط البنودالتى بدأت."><span class="glyphicon glyphicon-info-sign"></span></a></h3>
		</div>
		<div class="panel-body">
			@if(session('empty_error'))
			<div class="alert alert-danger">
				{{session('empty_error')}}
			</div>
			@endif
			@if(session('insert_error'))
			<div class="alert alert-danger">
				{{session('insert_error')}}
			</div>
			@endif
			@if(session('type_error'))
			<div class="alert alert-danger">
				{{session('type_error')}}
			</div>
			@endif
			@if(session('error_100'))
			<div class="alert alert-danger">
				{{session('error_100')}}
			</div>
			@endif
			@if(session('success'))
			<div class="alert alert-success">
				{{session('success')}}
			</div>
			@endif
			@if(count($terms)>0)
			<form method="post" action="{{ route('saveextractor',$project->id) }}" id="add_transaction">
			<div class="table-responsive">
				<h3 class="print-title" style="text-align: center;">طلب مستخلص للمشروع {{$project->name}}</h3>
				<table class="table table-bordered" id="printTable">
					<thead id="pageHeader">
						<tr>
						<th class="all_checkbox">الكل</th>
						<th rowspan="2">#</th>
						<th rowspan="2">كود البند</th>
						<th rowspan="2">الوحدة</th>
						<th rowspan="2">الفئة</th>
						<th rowspan="2">الكمية</th>
						<th colspan="3" style="text-align: center;">الكميات المنتجة</th>
						<th colspan="3" style="text-align: center;">الأستقطاع
							<a data-html="true" tabindex="0" role="button" data-toggle="popover" data-trigger="focus" title="كيفية تغيير قيم الأستقطاعات " data-content="تستطيع تغيير قيمة الاأستقطاع أو نسبة الأستقطاع من خلال الضغط على القيمة المراد تغييرها , سيظهر لك حينها مكان لتغيير قيمة أى من قيمة أو نسبة الأستقطاع.">
								<span class="glyphicon glyphicon-info-sign"></span>
							</a>
						</th>
						</tr>
						<tr>
							<th class="all_checkbox"><input type="checkbox" name="checkall" id="checkall" value="1"></th>
							<th>السابقة</th>
							<th>الحالية</th>
							<th>الجملة</th>
							<th style="width:120px;">نسبة</th>
							<th style="width:180px;">قيمة</th>
							<th>الصافى</th>
						</tr>
					</thead>
					<tbody>
					<?php $count=1; $c=1; ?>
						@foreach($terms as $term)
						<?php
							$total_production=$term->productions()->sum('productions.amount');
							$prev_production=$term->transactions()->sum('transaction')/$term->value;
							$current_production=$total_production-$prev_production;
						?>
						<tr>
						<th class="all_checkbox"><input type="checkbox" @if(old('checked.'.$c)) checked	@endif class="term" name="checked[{{$c}}]" value="{{$c}}"></th>
						<th>{{$count++}}</th>
						<th><a href="{{ route('showterm',$term->id) }}">{{$term->code}}</a>
						<input type="hidden" name="term[{{$c}}][id]" value="{{$term->id}}">
						</th>
						<th>{{$term->unit}}</th>
						<th class="value_per_unit">{{$term->value}}</th>
						<th>{{Str::number_format($term->amount)}}</th>
						<th class="prev_amount" data-amount="{{$prev_production}}">{{ Str::number_format($prev_production) }}</th>
						<th style="width: 100px;" class=" @if(session('current_amount'.$c)==1) has-error @endif ">
						<input type="text" style="width:100px;" id="current_amount_{{$c}}" autocomplete="off" name="term[{{$c}}][current_amount]" class="form-control number @if(session('current_amount'.$c)==1) has-error @endif current_amount" value="{{ old('term.'.$c.'.current_amount')??$current_production}}">
						</th>
						<th class="total_production" data-total="{{$total_production}}" data-total-price="{{$total_production*$term->value}}">{{Str::number_format($total_production)}}</th>
						<th class="deduction_percent" data-term="term[{{$c}}][deduction_percent]" data-value="{{$term->deduction_percent}}"  data-value-id="{{$c}}">
							@if (old('term.'.$c.'.deduction_percent'))
								<div class="input-group @if(session('deduction_percent'.$c)==1) has-error @endif" >
									<input type="text" name="term[{{$c}}][deduction_percent]" autocomplete="off" class="form-control number imp-width-100" value="{{old('term.'.$c.'.deduction_percent')??$term->deduction_percent}}" id="deduction_percent_{{$c}}">
									<span class="input-group-addon" style="font-size:20px; font-weight:100; ">&#37;</span>
								</div>
							@else
								{{$term->deduction_percent}} &#37;
							@endif
						</th>
						@php
							$total_value=$total_production*$term->value;
							$deduction_value=($term->deduction_percent/100)*$total_value;
						@endphp
						<th class="deduction_value" data-term="term[{{$c}}][deduction_value]"  data-value="{{$deduction_value}}" data-value-id="{{$c}}">
							@if (old('term.'.$c.'.deduction_value'))
								<div class="input-group @if(session('deduction_value'.$c)==1) has-error @endif" >
									<input type="text" name="term[{{$c}}][deduction_value]" autocomplete="off" class="form-control number" value="{{old('term.'.$c.'.deduction_value')}}" id="deduction_value_{{$c}}">
									<span class="input-group-addon" style="font-size:20px; font-weight:100; ">جنيه</span>
								</div>
							@else
								{{Str::number_format($deduction_value)}} جنيه
							@endif
						</th>
						<th class="price_after_deduction">{{Str::number_format($total_value-$deduction_value)}} جنيه</th>
						</tr>
						<?php $c++; ?>
						@endforeach
					</tbody>
				</table>
			</div>
				<input type="hidden" name="_token" value="{{csrf_token()}}">
				<button type="button" id="create_extractor" data-toggle="modal" data-target="#save" class="btn btn-primary">
					حفظ المستخلص
				</button>
				<div class="modal fade" id="save" tabindex="-1" role="dialog">
					<div class="modal-dialog modal-sm">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title">هل تريد تريد حفظ هذا المستخلص؟</h4>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">لا
								</button>
								<button class="btn btn-primary">نعم</button>
							</div>
						</div>
					</div>
				</div>
				<a href="{{ route('printextractor',$project->id) }}" target="_blank" class="btn btn-default">طباعة <span class="glyphicon glyphicon-print"></span></a>
				<button id="set_extractor" type="button" class="btn btn-default"><span style="top:3px; position:relative; margin-left:0.5rem">&#10227;</span> ضبط قيم المستخلص</button>
			</form>
			@else
			<div class="alert alert-warning">
				لا يوجد بنود بدأت حتى ألأن
			</div>
			@endif
		</div>
	</div>
</div>
@endsection
