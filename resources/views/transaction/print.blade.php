<html>
<head>
	<title>مستخلص للمشروع {{$project->name}}</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<link rel="icon" type="image/png" href="{{ asset('icon2.png') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ asset('js/js.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
	<script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('bootstrap/js/bootstrap.min.js') }}">
	</script>
	<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/main.js') }}"></script>
</head>
<body>
<div class="container-fluid">
<button id="print" type="button" class="btn btn-primary" style="margin: 10px;">
	طباعة <span class="glyphicon glyphicon-print"></span>
</button>
<div class="table-responsive">
	<h3 class="print-title" style="text-align: center;">طلب مستخلص للمشروع {{$project->name}}</h3>
	<table class="table table-bordered" id="printTable">
		<thead id="pageHeader">
			<tr>
			<th rowspan="2">#</th>
			<th rowspan="2">كود البند</th>
			<th rowspan="2">الوحدة</th>
			<th rowspan="2">الفئة</th>
			<th rowspan="2">الكمية</th>
			<th colspan="3" style="text-align: center;">الكميات المنتجة</th>
			<th colspan="3" style="text-align: center;">الأستقطاع</th>
			</tr>
			<tr>
				<th>السابقة</th>
				<th>الحالية</th>
				<th>الجملة</th>
				<th>نسبة</th>
				<th>قيمة</th>
				<th>الصافى</th>
			</tr>
		</thead>
		<tbody>
		<?php $count=1; ?>
			@foreach($terms as $term)
			<?php
				$total_production=$term->productions()->sum('productions.amount');
				$prev_production=$term->transactions()->where('type','in')->sum('transaction')/$term->value;
				$current_production=$total_production-$prev_production;
			?>
			<tr>
			<th>{{$count++}}</th>
			<th>{{$term->code}}</th>
			<th>{{$term->unit}}</th>
			<th>{{$term->value}}</th>
			<th>{{$term->amount}}</th>
			<th class="prev_amount">{{$prev_production}}</th>
			@if($current_production<0)
			<th style="width: 100px;">0</th>
			<th class="total_production">{{ $prev_production }}</th>
			@else
			<th style="width: 100px;">{{$current_production}}</th>
			<th class="total_production">{{$total_production}}</th>
			@endif
			
			<th>{{$term->deduction_percent}}</th>
			<th>{{($term->deduction_percent/100)*$term->term_amount*$term->value}}</th>
			<th>{{$term->amount*$term->value-(($term->deduction_percent/100)*$term->amount*$term->value)}}</th>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>
</div>
</body>
</html>