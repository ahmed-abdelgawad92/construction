@extends('layouts.master')
@section('title','جميع البنود')
@section('content')
<div class="content">
	@if(session('delete_error'))
	<div class="alert alert-danger">
		<strong>خطأ</strong>
		<br>
		<ul>
			<li>{{ session('delete_error') }}</li>
		</ul>
	</div>
	@endif
	@if(session('success'))
		<div class="alert alert-success">
			<strong>تمت بنجاح</strong>
			<br>
			<ul>
				<li>{{ session('success') }}</li>
			</ul>
		</div>
	@endif
	<div class="panel panel-default">
		<div class="panel-heading">
		@if(Route::current()->getName()=='allterm')
			@if(isset($project))
			<h3>
			جميع بنود المشروع <a href="{{ route('showproject',$project->id) }}">{{$project->name}}</a>
			</h3>
			@else
			<h3>جميع بنود المتعاقد عليها</h3>
			@endif
		@elseif(Route::current()->getName()=='alltermstoaddproduction')
			<h3>أختار البند لكى تضيف أنتاج اليه</h3>
		@elseif(Route::current()->getName()=='alltermstoshowproduction')
			<h3>أختار بند لكى تعرض أجمالى الأنتاج به</h3>
		@elseif(Route::current()->getName()=='termconsumption')
			<h3>أختار بند لكى تعرض جميع الأستهلاك به</h3>
		@elseif(Route::current()->getName()=='showtermtoaddconsumption')
			<h3>أختار بند لكى تضيف أستهلاك اليه</h3>
		@elseif(Route::current()->getName()=='notstartedterms')
			<h3>جميع البنود التى لم تبدأ بمشروع <a href="{{route('showproject',['id'=>$project->id])}}">{{$project->name}}</a></h3>
		@elseif(Route::current()->getName()=='startedterms')
			<h3>جميع البنود التي بدأت بمشروع <a href="{{route('showproject',['id'=>$project->id])}}">{{$project->name}}</a></h3>
		@elseif(Route::current()->getName()=='disabledterms')
			<h3>جميع البنود المعطلة بمشروع <a href="{{route('showproject',['id'=>$project->id])}}">{{$project->name}}</a></h3>
		@elseif(Route::current()->getName()=='doneterms')
			<h3>جميع البنود المنتهية بمشروع <a href="{{route('showproject',['id'=>$project->id])}}">{{$project->name}}</a></h3>
		@elseif(Route::current()->getName()=='deletedterms')
			<h3>جميع البنود المحذوفة بمشروع <a href="{{route('showproject',['id'=>$project->id])}}">{{$project->name}}</a></h3>
		@endif
		</div>
		<div class="panel-body">
		@if(Auth::user()->type=='admin')
			@if(count($terms)>0)
			<div class="table-responsive center">
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
					<th>#</th>
					<th>نوع البند</th>
					<th>كود البند</th>
					<th>بيان الأعمال</th>
					<th>وحدة</th>
					<th>الكمية</th>
					<th>الفئة</th>
					<th>القيمة</th>
					<th>الاوامر</th>
					@if(Route::current()->getName()=='allterm')
					<th>الحالة</th>
					@endif
					</tr>
				</thead>
				<tbody>
				<?php $page=$_GET['page']??1; $count=(($page -1)*30)+1;?>
				@foreach($terms as $term)
					<tr>
						<td>{{$count++}}</td>
						<td>{{$term->type}}</td>
						@if(Route::current()->getName()=='alltermstoaddproduction')
						<th style="white-space:nowrap;"><a href="{{ route('addproduction',$term->id) }}">{{$term->code}}</a></th>
						@elseif(Route::current()->getName()=='alltermstoshowproduction')
						<th style="white-space:nowrap;"><a href="{{ route('showtermproduction',$term->id) }}">{{$term->code}}</a></th>
						@elseif(Route::current()->getName()=='termconsumption')
						<th style="white-space:nowrap;"><a href="{{ route('showtermconsumption',$term->id) }}">{{$term->code}}</a></th>
						@elseif(Route::current()->getName()=='showtermtoaddconsumption')
						<th style="white-space:nowrap;"><a href="{{ route('addconsumption',$term->id) }}">{{$term->code}}</a></th>
						@else
						<th style="white-space:nowrap;"><a href="{{ route('showterm',$term->id) }}">{{$term->code}}</a></th>
						@endif
						<td>{{$term->statement}}</td>
						<td>{{$term->unit}}</td>
						<td>{{$term->amount}}</td>
						<td>{{$term->value}}</td>
						<td>{{$term->value*$term->amount}}</td>
						<td>
							@if ($term->deleted == 0)
								<a href="{{route('updateterm',['id'=>$term->id])}}" class="btn btn-default block">تعديل</a>
								@if (($term->started_at==null || date("Y-m-d",strtotime($term->started_at))>date("Y-m-d")) && $term->done==0)
									<a href="{{route('startterm',['id'=>$term->id])}}" class="btn btn-primary block mt-2">ابدا الان</a>
								@elseif ($term->done==0)
									<a href="{{route('endterm',['id'=>$term->id])}}" class="btn btn-success block mt-2">انهاء</a>
								@endif
								@if ($term->disabled==0 && $term->done==0)
									<a href="{{route('disableterm',['id'=>$term->id])}}" class="btn btn-dark block mt-2">تعطيل</a>
								@elseif ($term->done==0)
									<a href="{{route('enableterm',['id'=>$term->id])}}" class="btn btn-enable block mt-2">تفعيل</a>
								@endif
								<a href="{{route('updateterm',['id'=>$term->id])}}" class="btn btn-danger block mt-2">حذف</a>
							@else
								<a href="{{route('updateterm',['id'=>$term->id])}}" class="btn btn-success block mt-2">استرجاع</a>
							@endif
						</td>
						@if(Route::current()->getName()=='allterm')
						<td>
							@if (($term->started_at==null || date("Y-m-d",strtotime($term->started_at))>date("Y-m-d")) &&$term->done==0)
								لم يبدأ<br>
							@endif
							@if ($term->disabled==1)
								مُعطل <br>
							@endif
							@if ($term->done==1)
								انتهى <br>
							@endif
							@if ($term->deleted==1)
								محذوف
							@endif
							@if ((!($term->started_at==null || date("Y-m-d",strtotime($term->started_at))>date("Y-m-d")) &&$term->done==0 &&$term->disabled==0&&$term->deleted==0))
								جارى التنفيذ<br>
							@endif
						</td>
						@endif
					</tr>
				@endforeach
				</tbody>
			</table>
			{{ $terms->links() }}
			</div>
			@else
			<div class="alert alert-warning">
				<p>لا يوجد بنود</p>
			</div>
			@endif
		@endif
		</div>
	</div>
</div>
@endsection
