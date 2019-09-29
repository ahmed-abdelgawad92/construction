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
	<link rel="stylesheet" type="text/css" href="{{ asset('css/bug.css') }}">
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
   </header>
   <div class="container">
      @yield('content')
   </div>
</body>
</html>