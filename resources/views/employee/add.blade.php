@extends('layouts.master')
@section('title','أضافة موظف')
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>أضافة موظف</h3>
		</div>
		<div class="panel-body">
			@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
			@if(session("success"))
        <div class="alert alert-success">
          <strong>{{ session("success") }}</strong>
        </div>
      @endif
      @if(session("insert_error"))
        <div class="alert alert-danger">
          <strong>{{ session("insert_error") }}</strong>
        </div>
      @endif
      @if(session("info"))
        <div class="alert alert-info">
          <strong>{{ session("info") }}</strong>
        </div>
      @endif
			<form method="post" action="{{ route('addemployee') }}" class="form-horizontal">
				<div class="form-group row @if($errors->has('name')) has-error @endif">
					<label for="name" class="control-label col-sm-2 col-md-2 col-lg-2">أسم الموظف</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="text" name="name" id="name" value="{{old('name')}}" class="form-control" placeholder="أدخل أسم الموظف">
						@if($errors->has('name'))
							@foreach($errors->get('name') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group row @if($errors->has('type')) has-error @endif ">
					<label for="type_employee" class="control-label col-sm-2 col-md-2 col-lg-2">نوع الموظف</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<select name="type" id="type_employee" class="form-control">
							<option>أختار نوع الموظف</option>
							<option value="1" @if(old('type')==1) selected @endif >معين بالشركة</option>
							<option value="2" @if(old('type')==2) selected @endif >منتدب</option>
						</select>
						@if($errors->has('type'))
							@foreach($errors->get('type') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group row @if($errors->has('job')) has-error @endif">
					<label for="job" class="control-label col-sm-2 col-md-2 col-lg-2">المسمى الوظيفى</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="text" name="job" id="job" value="{{old('job')}}" class="form-control" placeholder="أدخل المسمى الوظيفى">
						@if($errors->has('job'))
							@foreach($errors->get('job') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				@if(old('phone')!==null)
				@for($i=0; $i<count(old('phone')); $i++)
				<div class="form-group row @if($errors->has("phone.$i")) has-error @endif" @if($i==0) id="phone_template" @else id="del_phone{{$i}}" @endif>
					<label for="phone" class="control-label col-sm-2 col-md-2 col-lg-2">تليفون * @if($i==0)<a href="#" id="add_another_phone"> أضافة رقم جديد؟</a>@else <span data-phone="{{$i}}" class="glyphicon glyphicon-trash delete_phone"></span> @endif</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="text" name="phone[{{$i}}]" id="phone{{$i>0?$i:null}}" value="{{old("phone.".$i)}}" class="form-control phone_input number" placeholder="أدخل التليفون">
						@if($errors->has("phone.$i"))
							@foreach($errors->get("phone.$i") as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				@endfor
				@else
				<div class="form-group row @if($errors->has('phone')) has-error @endif" id="phone_template">
					<label for="phone" class="control-label col-sm-2 col-md-2 col-lg-2">تليفون * <a href="#" id="add_another_phone"> أضافة رقم جديد؟</a></label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="text" name="phone[0]" id="phone" value="" class="form-control phone_input number" placeholder="أدخل التليفون">
					</div>
				</div>
				@endif
				<div class="form-group row @if($errors->has('address')) has-error @endif">
					<label for="address" class="control-label col-sm-2 col-md-2 col-lg-2">الشارع</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="text" name="address" id="address" value="{{old('address')}}" class="form-control" placeholder="أدخل الشارع">
						@if($errors->has('address'))
							@foreach($errors->get('address') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group row @if($errors->has('village')) has-error @endif">
					<label for="village" class="control-label col-sm-2 col-md-2 col-lg-2">القرية</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="text" name="village" id="village" value="{{old('village')}}" class="form-control" placeholder="أدخل القرية">
						@if($errors->has('village'))
							@foreach($errors->get('village') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group row @if($errors->has('city')) has-error @endif">
					<label for="city" class="control-label col-sm-2 col-md-2 col-lg-2">المدينة</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="text" name="city" id="city" value="{{old('city')}}" class="form-control" placeholder="أدخل المدينة">
						@if($errors->has('city'))
							@foreach($errors->get('city') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group row @if(old('salary_company')!=null) display @endif @if($errors->has('salary_company')) has-error @endif" id="div-salary-company">
					<label for="salary_company" class="control-label col-sm-2 col-md-2 col-lg-2">الراتب</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="text" name="salary_company" id="salary_company" value="{{old('salary_company')}}" class="form-control" placeholder="أدخل الراتب">
						@if($errors->has('salary_company'))
							@foreach($errors->get('salary_company') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group row @if(old('assign_job')!=null) display @endif @if($errors->has('assign_job')) has-error @endif" id="div-assign-job">
					<label for="assign_job" class="control-label col-sm-2 col-md-2 col-lg-2">تعيين الموظف ألأن</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<label>
						<input type="radio" name="assign_job" @if(old('assign_job')==0 ||old('assign_job')==null) checked @endif id="assign_job" value="0">
						لا
						</label>
						<label>
						<input type="radio" name="assign_job" @if(old('assign_job')==1) checked @endif id="assign_job" value="1">
						نعم
						</label>
						@if($errors->has('assign_job'))
							@foreach($errors->get('assign_job') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div id="assign_job_form" @if(old('assign_job')!=null && old('assign_job')==1) display @endif >
				<div class="form-group row @if($errors->has('project_id')) has-error @endif">
					<label for="project_id" class="control-label col-sm-2 col-md-2 col-lg-2">تعيينه بمشروع</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<select name="project_id" id="project_id" class="form-control">
							<option value="">أختار مشروع</option>
							@foreach($projects as $project)
							<option value="{{$project->id}}" @if(old('project_id')==$project->id) selected @endif >
							{{$project->name}}
							</option>
							@endforeach
						</select>
						@if($errors->has('project_id'))
							@foreach($errors->get('project_id') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group row @if($errors->has('salary')) has-error @endif">
					<label for="salary" class="control-label col-sm-2 col-md-2 col-lg-2">الراتب الشهرى بالمشروع</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="text" name="salary" id="salary" value="{{old('salary')}}" class="form-control" placeholder="أدخل الراتب الشهرى بالمشروع">
						@if($errors->has('salary'))
							@foreach($errors->get('salary') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				</div>
				<div class="col-sm-2 col-md-2 col-lg-2 offset-sm-5 offset-md-5 offset-lg-5">
					<button class="btn btn-primary form-control" id="save_btn">حفظ</button>
				</div>
				<input type="hidden" name="_token" value="{{csrf_token()}}">
			</form>
		</div>
	</div>
</div>
@endsection
