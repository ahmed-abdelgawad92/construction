@extends('layouts.master')
@section('title','جميع المشاريع التى لم تنتهى')
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading navy-heading">
			<h3>جميع المشروعات التى لم تنتهى</h3>
		</div>
		<div class="panel-body">
			@if(Route::current()->getName()=='findproduction')
			<div class="alert alert-info">أختار مشروع لعرض أجمالى الأنتاج به.</div>
			@elseif(Route::current()->getName()=='findtermstoaddproduction')
			<div class="alert alert-info">أختار مشروع لعرض جميع بنوده و أختيار البند المراد أدخال كمية الأنتاج اليه.</div>
			@elseif(Route::current()->getName()=='selecttermconsumption')
			<div class="alert alert-info">أختار مشروع لعرض جميع بنوده و أختيار البند المراد عرض جميع الأستهلاك به.</div>
			@elseif(Route::current()->getName()=='selectprojectconsumption')
			<div class="alert alert-info">أختار مشروع لعرض جميع الأستهلاك به.</div>
			@elseif(Route::current()->getName()=='selecttermconsumedraw')
			<div class="alert alert-info">أختار مشروع لعرض جميع بنوده و أختيار البند المراد عرض أجمالى الأستهلاك به.</div>
			@elseif(Route::current()->getName()=='selectprojectconsumedraw')
			<div class="alert alert-info">أختار مشروع لعرض أجمالى الأستهلاك به.</div>
			@elseif(Route::current()->getName()=='showprojecttoaddconsumption')
			<div class="alert alert-info">أختار مشروع لعرض جميع بنوده و أختيار البند المراد أدخال كمية الأستهلاك اليه.</div>
			@elseif(Route::current()->getName()=='chooseprojectexpense')
			<div class="alert alert-info">أختار مشروع لعرض جميع الأكراميات المدفوعة به.</div>
			@elseif(Route::current()->getName()=='chooseprojecttax')
			<div class="alert alert-info">أختار مشروع لعرض جميع الضرائب به.</div>
			@elseif(Route::current()->getName()=='chooseprojectgraph')
			<div class="alert alert-info">أختار مشروع لعرض جميع رسوماته</div>
			@elseif(Route::current()->getName()=='chooseprojectemployee')
			<div class="alert alert-info">أختار مشروع لعرض جميع موظفيينه</div>
			@endif
			@if(count($projects)>0)
			@foreach($projects as $project)
			<a href="
			@if(Route::current()->getName()=='findproduction')
			{{ route('showprojectproduction',$project->id) }}
			@elseif(Route::current()->getName()=='findtermstoaddproduction')
			{{ route('alltermstoaddproduction',$project->id) }}
			@elseif(Route::current()->getName()=='findproductionforterm')
			{{ route('alltermstoshowproduction',$project->id) }}
			@elseif(Route::current()->getName()=='selectprojectconsumption')
			{{ route('showprojectconsumption',$project->id) }}
			@elseif(Route::current()->getName()=='selecttermconsumption')
			{{ route('termconsumption',$project->id) }}
			@elseif(Route::current()->getName()=='selecttermconsumedraw')
			{{ route('termconsumedraw',$project->id) }}
			@elseif(Route::current()->getName()=='selectprojectconsumedraw')
			{{ route('showprojectconsumedraw',$project->id) }}
			@elseif(Route::current()->getName()=='showprojecttoaddconsumption')
			{{ route('showtermtoaddconsumption',$project->id) }}
			@elseif(Route::current()->getName()=='chooseprojectexpense')
			{{ route('showexpense',$project->id) }}
			@elseif(Route::current()->getName()=='chooseprojecttax')
			{{ route('showtax',$project->id) }}
			@elseif(Route::current()->getName()=='chooseprojectgraph')
			{{ route('allgraph',$project->id) }}
			@elseif(Route::current()->getName()=='chooseprojectemployee')
			{{ route('allemployee',$project->id) }}
			@endif
			 " class="list-hover">
				<div class="row item">

				<div class="col-md-2 col-lg-2 col-sm-2 col-xs-2">
					<img src="{{ asset('images/project2.png') }}" alt="" class="img-responsive">
				</div>
				<div class="col-md-10  col-lg-10  col-sm-10 col-xs-10">
					<h4>مشروع {{$project->name}}</h4>
					<p>العنوان : {{$project->address}} , {{$project->center}} , {{$project->city}} </p>
				</div>
			</div>
			</a>

			@endforeach
			@else
			<div class="alert alert-warning">لا يوجد مشروعات حالية</div>
			@endif
		</div>
	</div>
</div>
@endsection
