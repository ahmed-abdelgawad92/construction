@extends('layouts.master')
@section('title','مستخلص للمقاول '.$contractor->name)
@section('content')
<div class="content">
   <div class="panel panel-default">
     <div class="panel-heading">
       <h3>جميع المبالغ المدفوعة للمقاول <a href="{{route('showcontractor',['id'=>$contractor->id])}}">{{$contractor->name}}</a> بالبند <a href="{{route('showterm',['id'=>$term->id])}}">{{$term->code}}</a>
         بمشروع <a href="{{route('showproject',['id'=>$term->project_id])}}">{{$term->project->name}}</a>
       </h3>
     </div>
     <div class="panel-body">
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
     @if (count($payments)>0)
     <div class="jumbotron">
       <h2 style="border-bottom: 1px solid #000; padding-bottom: 5px;">تقرير بالمدفوعات</h2>
       <br><br>
       <div class="row">
         <div class="col-sm-6 col-md-4 col-lg-4 col-xs-6" style="margin-bottom: 10px;">
           <div class="circle-div">
             {{ Str::number_format($contract->unit_price) }} جنيه
           </div>
           <p style="text-align: center; margin-top: 8px;">سعر الوحدة المحدد بالعقد</p>
         </div>
         <div class="col-sm-6 col-md-4 col-lg-4 col-xs-6" style="margin-bottom: 10px;">
           <div class="circle-div">
             {{ Str::number_format($total_payment/$contract->unit_price).' '.$term->unit }}
           </div>
           <p style="text-align: center; margin-top: 8px;">الكمية المنتجة المُحاسب عليها</p>
         </div>
         <div class="col-sm-6 col-md-4 col-lg-4 col-xs-6" style="margin-bottom: 10px;">
           <div class="circle-div">
             {{ Str::number_format($total_payment) }} جنيه
           </div>
           <p style="text-align: center; margin-top: 8px;">أجمالى المدفوع للمقاول</p>
         </div>
       </div>
     </div>
     <div class="table-responsive">
       <table class="table table-bordered">
         <thead>
           <tr>
             <th>#</th>
             <th>الكمية المنتجة</th>
             <th>المبلغ الدفوع</th>
             <th>نوع الدفع</th>
             <th>تاريخ الدفع</th>
             <th>أمر</th>
           </tr>
         </thead>
         <tbody>
           @php
           $count=0;
           @endphp
           @foreach ($payments as $payment)
             <tr>
               <td>{{++$count}}</td>
               <td>{{Str::number_format($payment->payment_amount/$contract->unit_price).' '.$term->unit}}</td>
               <td>{{$payment->payment_amount}} جنيه</td>
               @if ($payment->type==0)
               <td>صندوق</td>
               @else
               <td>قرض</td>
               @endif
               <td>{{date('d/m/Y',strtotime($payment->created_at))}}</td>
               <td>
                 <form class="float" method="post" action="{{route('deletecontractpayment',['id'=>$payment->id])}}">
                   <button type="button" data-toggle="modal" data-target="#delete{{$payment->id}}" class="btn btn-danger">حذف</button>
             				<div class="modal fade" id="delete{{$payment->id}}" tabindex="-1" role="dialog">
             					<div class="modal-dialog modal-sm">
             						<div class="modal-content">
             							<div class="modal-header">
             								<h4 class="modal-title">هل تريد حذف هذه المعاملة؟</h4>
             							</div>
             							<div class="modal-footer">
             								<button type="button" class="btn btn-default" data-dismiss="modal">لا
             								</button>
             								<input type="submit" class="btn btn-danger" value="نعم"/>
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
       <a class="btn btn-primary" href="{{route('addcontracttransaction',['id'=>$contract->id])}}">أضافة معاملة مالية</a>
     @else
       <div class="alert alert-warning">لا يوجد معاملات مالية <a class="btn btn-warning" href="{{route('addcontracttransaction',['id'=>$contract->id])}}">أضافة معاملة مالية</a></div>
     @endif
     </div>
  </div>
</div>

@endsection
