@extends('layouts.master')
@section('title','الرئيسية')
@section('content')
<div class="row">
  <div class="col-md-12 col-lg-6">
    <div class="card mt-4">
      <div class="row">
        <div class="col-xs-4 col-sm-4 col-md-3 col-lg-4">
          <a href=""><img src="{{asset('images/contractor.png')}}" class="w-100 contractor-img" alt=""></a>
        </div>
        <div class="col-xs-8 col-sm-8 col-md-9 col-lg-8">
            <h3 class="mb-2">
              <span class="label label-default">اسم المقاول</span>
              Ahmed
            </h3>
        </div>
      </div>
      <div class="center mt-3">

      </div>
    </div>
  </div>
</div>
@endsection
