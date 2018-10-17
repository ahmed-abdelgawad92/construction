@extends('layouts.master')
@section('title','أجمالى أنتاج المقاول '.$contractor->name)
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>جميع أنتاج المقاول <a href="{{ route('showcontractor',$contractor->id) }}">{{$contractor->name}}</a></h3>
		</div>
		<div class="panel-body">
			@if(session('success'))
				<div class="alert alert-success">
					<strong>تمت بنجاح</strong>
					<br>
					<ul>
						<li>{{ session('success') }}</li>
					</ul>
				</div>
			@endif
			@if(session('delete_error'))
				<div class="alert alert-danger">
					{{ session('delete_error') }}
				</div>
			@endif
			@if(count($productions)>0)
			<div class="jumbotron">

				<h2 style="border-bottom: 1px solid #000; padding-bottom: 5px;">متوسط تقييم الأنتاج من 10</h2>
				<br><br>
				<div class="row">
					<div class="col-sm-6 col-md-4 col-lg-4 col-xs-6" style="margin-bottom: 10px;">
					</div>
					<div class="col-sm-6 col-md-4 col-lg-4 col-xs-6" style="margin-bottom: 10px;">
						<div class="circle-div">
							{{Str::number_format($avg_rate)}}
						</div>
					</div>
					<div class="col-sm-6 col-md-4 col-lg-4 col-xs-6" style="margin-bottom: 10px;">
					</div>
				</div>
			</div>
			<h4 style="border-bottom: 1px solid #eee; padding-bottom: 5px;">تفاصيل الأنتاج</h4>
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>#</th>
							<th>كود البند</th>
							<th>الكمية المنتجة</th>
							<th>التقييم</th>
							<th>تاريخ الأنتاج</th>
							<th>ملحوظة</th>
							<th style="width: 100px !important">تعديل</th>
							<th style="width: 100px !important">حذف</th>
						</tr>
					</thead>
					<tbody>
						<?php $count=1; ?>
						@foreach($productions as $production)
						<tr>
						<td>{{$count++}}</td>
						<td><a href="{{route("showterm",['id'=>$production->term()])}}">{{$production->term()->code}}</a></td>
						<td>{{Str::number_format($production->amount)}} {{$production->term()->unit}}</td>
						<td style="color: #337ab7;">
							<?php $stars=10-$production->rate; ?>
							@while($production->rate!=0)
							<span class="glyphicon glyphicon-star" aria-hidden="true"></span>
							<?php $production->rate--; ?>
							@endwhile
							@while($stars!=0)
							<span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span>
							<?php $stars--; ?>
							@endwhile
						</td>
						<td>{{date("d/m/Y",strtotime($production->created_at))}}</td>
						<td style="width: 100px !important">
							@if(empty($production->note))
							لا يوجد ملحوظة
							@else
							<button type="button" data-toggle="modal" data-target="#note{{$count}}" class="btn btn-primary btn-block">
							أقرا الملحوظة
							</button>
							<div class="modal fade" id="note{{$count}}" tabindex="-1" role="dialog" aria-labelledby="note-title" style="white-space: normal !important">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<h4 class="modal-title" id="note-title">نص الملحوظة</h4>
										</div>
										<div class="modal-body">
											<p>{{ $production->note }}</p>
										</div>
										<div class="modal-footer">
									        <button type="button" class="btn btn-default" data-dismiss="modal">أغلق
									        </button>
									    </div>
									</div>
								</div>
							</div>
							@endif
						</td>
						<td style="width: 100px !important"><a href="{{ route('updateproduction',$production->id) }}" class="btn btn-default btn-block">تعديل</a></td>
						<td style="width: 100px !important">
							<form method="post" action="{{ route('deleteproduction',$production->id) }}">
								<button type="button" data-toggle="modal" data-target="#delete{{$count}}" class="btn btn-danger btn-block">حذف</button>
								<div class="modal fade" id="delete{{$count}}" tabindex="-1" role="dialog">
									<div class="modal-dialog modal-sm">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title">هل تريد حذف كمية الأنتاج هذه من البند؟</h4>
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
			<div class="alert alert-warning">لا يوجد أنتاج لهذا البند</div>
			@endif
		</div>
	</div>
</div>
@endsection
