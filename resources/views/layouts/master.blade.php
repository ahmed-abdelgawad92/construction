<!DOCTYPE html>
<html lang="ar">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<link href="https://fonts.googleapis.com/css?family=Markazi+Text:500&amp;subset=arabic,latin-ext,vietnamese" rel="stylesheet">
	<title>@yield('title')</title>
	<link rel="icon" type="image/png" href="{{ asset('images/logo_3omad_sm.png') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ asset('js/js.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
	<script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
	<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/functions.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/main.js') }}"></script>
</head>
<body>
<header>
	<!--______________________NavBAR___________________________-->
	<nav class="navbar navbar-default" role="navigation">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#collapse">
			        <span class="sr-only">Toggle navigation</span>
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
		       		<span class="icon-bar"></span>
	    		</button>
				<a href="{{route('dashboard')}}" class="navbar-brand"><img src="{{asset('images/logo_3omad_sm.png')}}" alt="" id="logo"></a>
			</div>
			<div class="collapse navbar-collapse" id="collapse">
				<ul class="nav navbar-nav navbar-right">
					<!--______________________UserData___________________________-->
					@if(Auth::check())
					<li class="dropdown">
					  	<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
					    <span class="caret"></span>
					    <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
					    {{Auth::user()->username}}
					  	</a>
					  	<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
					  		<li><a href="{{ route('showuser',Auth::user()->id) }}">حسابى</a></li>
						    <li><a href="{{route('logout')}}">تسجيل خروج</a></li>
					  	</ul>
					</li>
					@endif
					<!--______________________END USER DATA___________________________-->
				</ul>
			</div>
		</div>
	</nav>
	<!--______________________END NAVBAR___________________________-->
</header>
<div class="container-fluid">
@if(Auth::check())
<div class="row">
<div class="col-lg-2 col-md-2 col-sm-12">
	<button type="button" class="btn btn-default" aria-label="Left Align" id="side-bar-icon">
	  <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
	</button>
	<!--______________________SIDEBAR FOR ADMIN___________________________-->
	@if(Auth::user()->type=='admin')
	@if(!isset($active))
		<?php $active='home';?>
	@endif
	<ul id="side-bar" class="nav nav-pills nav-stacked">
		<li @if($active=="home")class="active"@endif>
			<a href="{{ route('dashboard') }}">
			<span class="glyphicon glyphicon-home" aria-hidden="true"> </span> الرئيسية
			</a>
		</li>
		<li @if($active=="org")class="active"@endif>
			<a href="#" class="side-link">
			<span class="glyphicon glyphicon-phone-alt" aria-hidden="true"> </span> عملاء
			</a>
			<ul class="nav child-options">
				<li>
				<a href="{{ route('addorganization') }}">
					<span class="glyphicon glyphicon-plus" aria-hidden="true"> </span>
					أضافة عميل
				</a>
				</li>
				<li>
				<a href="{{ route('allclients') }}">
					<span class="glyphicon glyphicon-th" aria-hidden="true"> </span>
					جميع العملاء (بدون نسبة)
				</a>
				</li>
				<li>
				<a href="{{ route('allcontractclients') }}">
					<span class="glyphicon glyphicon-tasks" aria-hidden="true"> </span>
					جميع المقاوليين العملاء
				</a>
				</li>
				<li>
				<a href="{{ route('allorganization') }}">
					<span class="glyphicon glyphicon-th" aria-hidden="true"> </span>
					جميع العملاء
				</a>
				</li>
			</ul>
		</li>
		<li @if($active=="project")class="active"@endif>
			<a href="#" class="side-link">
			<span class="glyphicon glyphicon-briefcase" aria-hidden="true"> </span> مشروعات
			</a>
			<ul class="nav child-options">
				<li>
				<a href="{{ route('addproject') }}">
					<span class="glyphicon glyphicon-plus" aria-hidden="true"> </span>
					أضافة مشروع
				</a>
				</li>
				<li>
				<a href="{{ route('allstartedproject') }}">
					<span class="glyphicon glyphicon-tasks" aria-hidden="true"> </span>
					جميع المشروعات الحالية
				</a>
				</li>
				<li>
				<a href="{{ route('allnotstartedproject') }}">
					<span class="glyphicon glyphicon-unchecked" aria-hidden="true"> </span>
					جميع المشروعات التى لم تبدأ
				</a>
				</li>
				<li>
				<a href="{{ route('alldoneproject') }}">
					<span class="glyphicon glyphicon-check" aria-hidden="true"> </span>
					جميع المشروعات المنتهية
				</a>
				</li>
				<li>
				<a href="{{ route('allproject') }}">
					<span class="glyphicon glyphicon-th" aria-hidden="true"> </span>
					جميع المشروعات
				</a>
				</li>
			</ul>
		</li>
		<li @if($active=="term")class="active"@endif>
			<a href="#" class="side-link">
			<span class="glyphicon glyphicon-list-alt" aria-hidden="true"> </span> بنود
			</a>
			<ul class="nav child-options">
				<li><a href="{{ route('addterm') }}">أضافة بند</a></li>
				<li><a href="{{ route('addtermtype') }}">أضافة نوع بند جديد</a></li>
				<li><a href="{{ route('alltermtype') }}">جميع أنواع البنود</a></li>
			</ul>
		</li>
		<li @if($active=="cont")class="active"@endif>
			<a href="#" class="side-link">
			<span class="glyphicon glyphicon-wrench" aria-hidden="true"> </span> مقاوليين
			</a>
			<ul class="nav child-options">
				<li>
				<a href="{{ route('addcontractor') }}">
					<span class="glyphicon glyphicon-plus" aria-hidden="true"> </span>
					أضافة مقاول
				</a>
				</li>
				<li>
				<a href="{{ route('allcontractor') }}">
					<span class="glyphicon glyphicon-tasks" aria-hidden="true"> </span>
					جميع المقاوليين
				</a>
				</li>
			</ul>
		</li>
		<li @if($active=="sup")class="active"@endif>
			<a href="#" class="side-link">
			<span class="glyphicon glyphicon-import" aria-hidden="true"> </span> موردون
			</a>
			<ul class="nav child-options">
				<li>
				<a href="{{ route('addsupplier') }}">
					<span class="glyphicon glyphicon-plus" aria-hidden="true"> </span>
					أضافة مورد
				</a>
				</li>
				<li>
				<a href="{{ route('allsupplier') }}">
					<span class="glyphicon glyphicon-tasks" aria-hidden="true"> </span>
					جميع الموردين
				</a>
				</li>
			</ul>
		</li>
		<li @if($active=="store")class="active"@endif>
			<a href="#" class="side-link">
			<span class="glyphicon glyphicon-import" aria-hidden="true"> </span> مخازن
			</a>
			<ul class="nav child-options">
				<li><a href="{{ route('addstore') }}">شراء خامات</a></li>
				<li><a href="{{ route('findstores') }}">جميع الخامات بالمخازن</a></li>
				<li><a href="{{ route('addstoretype') }}">أضافة نوع خام جديد</a></li>
				<li><a href="{{ route('allstoretype') }}">جميع أنواع الخامات</a></li>
			</ul>
		</li>
		<li @if($active=="cons")class="active"@endif>
			<a href="#" class="side-link">
			<span class="glyphicon glyphicon-export" aria-hidden="true"> </span> أستهلاك
			</a>
			<ul class="nav child-options">
				<li><a href="{{ route('showprojecttoaddconsumption') }}">أضافة أستهلاك</a></li>
				<li><a href="{{ route('selecttermconsumption') }}">جميع الأستهلاك بالبند</a></li>
				<li><a href="{{ route('selectprojectconsumption') }}">جميع الأستهلاك بالمشروع</a></li>
			</ul>
		</li>
		<li @if($active=="pro")class="active"@endif>
			<a href="#" class="side-link">
			<span class="glyphicon glyphicon-saved" aria-hidden="true"> </span> أنتاج
			</a>
			<ul class="nav child-options">
				<li><a href="{{ route('findtermstoaddproduction') }}">أضافة أنتاج</a></li>
				<li><a href="{{ route('findproductionforterm') }}">أجمالى أنتاج البند</a></li>
				<li><a href="{{ route('findproduction') }}">أجمالى أنتاج المشروع</a></li>
			</ul>
		</li>
		<li @if($active=="graph")class="active"@endif>
			<a href="#" class="side-link">
			<span class="glyphicon glyphicon-blackboard" aria-hidden="true"> </span> رسومات
			</a>
			<ul class="nav child-options">
				<li><a href="{{ route('addgraphs') }}">أضافة رسم</a></li>
				<li><a href="{{ route('chooseprojectgraph') }}">جميع الرسومات بالمشروع</a></li>
			</ul>
		</li>
		<li @if($active=="emp")class="active"@endif>
			<a href="#" class="side-link">
			<span class="glyphicon glyphicon-user" aria-hidden="true"> </span> موظفيين
			</a>
			<ul class="nav child-options">
				<li><a href="{{ route('addemployee') }}">أضافة موظف</a></li>
				<li><a href="{{ route('allcompanyemployee') }}">جميع موظفيين الشركة</a></li>
				<li><a href="{{ route('allemployee') }}">جميع الموظفيين المنتدبيين</a></li>
				<li><a href="{{ route('chooseprojectemployee') }}">جميع الموظفيين المنتدبيين بالمشروع</a></li>
			</ul>
		</li>
		<li @if($active=="tax")class="active"@endif>
			<a href="#" class="side-link">
			<span class="glyphicon glyphicon-euro" aria-hidden="true"> </span> أستقطاعات
			</a>
			<ul class="nav child-options">
				<li><a href="{{ route('addtaxes') }}">أضافة أستقطاع</a></li>
				<li><a href="{{ route('chooseprojecttax') }}">أجمالى أستقطاعات المشروع</a></li>
			</ul>
		</li>
		<li @if($active=="exp")class="active"@endif>
			<a href="#" class="side-link">
			<span class="glyphicon glyphicon-euro" aria-hidden="true"> </span> أكراميات
			</a>
			<ul class="nav child-options">
				<li><a href="{{ route('addexpenses') }}">أضافة أكرامية</a></li>
				<li><a href="{{ route('chooseprojectexpense') }}">أجمالى أكراميات المشروع</a></li>
			</ul>
		</li>
		<li @if($active=="adv")class="active"@endif>
			<a href="#" class="side-link">
			<span class="glyphicon glyphicon-euro" aria-hidden="true"> </span> سلفات
			</a>
			<ul class="nav child-options">
				<li><a href="{{ route('addcompanyadvances') }}">أضافة سلفة إلى موظف بالشركة</a></li>
				<li><a href="{{ route('addadvances') }}">أضافة سلفة إلى موظف منتدب</a></li>
				<li><a href="{{ route('alladvance') }}">جميع السلفات</a></li>
			</ul>
		</li>
		<li @if($active=="trans")class="active"@endif>
			<a href="#" class="side-link">
			<span class="glyphicon glyphicon-euro" aria-hidden="true"> </span> الحسابات المالية
			</a>
			<ul class="nav child-options">
				<li><a href="{{ route('chooseprojectextractor') }}">أنشاء أو عرض مستخلص</a></li>
				<li><a href="{{ route('chooseprojecttransaction') }}">أجمالى المستخلصات</a></li>
				<li><a href="{{ route('chooseprojecttransaction') }}">أجمالى المعاملات</a></li>
			</ul>
		</li>
		<li @if($active=="user")class="active"@endif>
			<a href="#" class="side-link">
			<span class="glyphicon glyphicon-user" aria-hidden="true"> </span> الحسابات الشخصية
			</a>
			<ul class="nav child-options">
				<li>
				<a href="{{ route('adduser') }}">
					<span class="glyphicon glyphicon-plus" aria-hidden="true"> </span>
					أضافة حساب
				</a>
				</li>
				<li>
				<a href="{{ route('alluser') }}">
					<span class="glyphicon glyphicon-th" aria-hidden="true"> </span>
					جميع الحسابات
				</a>
				</li>
				<li>
				<a href="{{ route('allcontractor') }}">
					<span class="glyphicon glyphicon-eye-close" aria-hidden="true"> </span>
					جميع حسابات المقاوليين
				</a>
				</li>
				<li>
				<a href="{{ route('alladmin') }}">
					<span class="glyphicon glyphicon-eye-open" aria-hidden="true"> </span>
					جميع حسابات المشرفيين
				</a>
				</li>
			</ul>
		</li>
	</ul>
	@else
	<!--___________________SIDE BAR FOR CONTRACTOR__________________-->
		<ul id="side-bar" class="nav nav-pills nav-stacked">
		<li @if($active=="home")class="active"@endif>
			<a href="{{ route('dashboard') }}">
			<span class="glyphicon glyphicon-home" aria-hidden="true"> </span> الرئيسية
			</a>
		</li>
		<li @if($active=="project")class="active"@endif>
			<a href="#" class="side-link">
			<span class="glyphicon glyphicon-briefcase" aria-hidden="true"> </span> مشروعات
			</a>
			<ul class="nav child-options">
				<li>
				<a href="{{ route('allproject') }}">
					<span class="glyphicon glyphicon-tasks" aria-hidden="true"> </span>
					جميع المشروعات الحالية
				</a>
				</li>
				<li>
				<a href="">
					<span class="glyphicon glyphicon-check" aria-hidden="true"> </span>
					جميع المشروعات المنتهية
				</a>
				</li>
				<li>
				<a href="">
					<span class="glyphicon glyphicon-th" aria-hidden="true"> </span>
					جميع المشروعات
				</a>
				</li>
			</ul>
		</li>
		<li @if($active=="term")class="active"@endif>
			<a href="#" class="side-link">
			<span class="glyphicon glyphicon-list-alt" aria-hidden="true"> </span> بنود
			</a>
			<ul class="nav child-options">
				<li><a href="">أضافة </a></li>
				<li><a href="">جميع الهيئات</a></li>
			</ul>
		</li>
		<li @if($active=="con")class="active"@endif>
			<a href="#">
			<span class="glyphicon glyphicon-export" aria-hidden="true"> </span> أستهلاك
			</a>
		</li>
		<li @if($active=="pro")class="active"@endif>
			<a href="#">
			<span class="glyphicon glyphicon-saved" aria-hidden="true"> </span> أنتاج
			</a>
		</li>
	</ul>
	@endif
	<!--______________________END SIDEBAR___________________________-->
</div>
<div class="col-lg-10 col-md-10 col-sm-12">
	<!--______________________SEARCHBAR___________________________-->
	<div class="row">
		<div class="col-lg-8 offset-lg-2 col-md-8 offset-sm-2 col-sm-8 offset-md-2">
		<form method="get" action="">
			<div class="search">
				<input type="text" name="search" placeholder="بحث" class="form-control search">
				<button class="search">
					<span class="glyphicon glyphicon-search search" aria-hidden="true"></span>
				</button>
			</div>
			<div class="search">
				<select class="search form-control">
					<option value="">بالهيئة</option>
					<option value="">بالمشروع</option>
					<option value="">بكود البند</option>
					<option value="">بالمقاول</option>
					<option value="">بالمورد</option>
					<option value="">بالموظف</option>
					<option value="">بالمستخدم</option>
				</select>
			</div>
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
		</form>
		</div>
	</div>
	<!--______________________END SEARCHBAR___________________________-->
	@yield('content')
</div>
</div>
@endif
@if (!Auth::check())
	@yield('guestcontent')
@endif
</div>
<!-- <footer class="navbar-fixed-bottom">
	<div>contact with the developer</div>
	<a href="https://www.linkedin.com/in/ahmed-ayman-29955767?trk=hp-identity-name"><img class="footer-img" src="{{ asset('images/linkedin.png') }}"></a>
	<a href="https://www.facebook.com/ahmed.elkayaty"><img class="footer-img" src="{{ asset('images/fb.png') }}"></a>
	<a href="https://plus.google.com/u/0/+AhmedAymanElkayaty/posts"><img class="footer-img" src="{{ asset('images/google.png') }}"></a>
	<div>cyco3dg &copy; 2016</div>
</footer> -->
</body>
</html>
