@extends('layouts.master')
@section('title','جميع المبالغ المدفوعة بالكمية المُوردة')
@section('content')
<div class="content">
   <div class="row">
     <div class="col-md-12 col-lg-12 col-sm-12">
       <div class="panel panel-default">
         <div class="panel-heading">
           <h3>تفاصيل حساب توريد ال{{$store->type}} المُورد بتاريخ {{$store->created_at->format("d/m/Y")}} من المقاول المورد  <a href="{{route("showsupplier",['id'=>$store->supplier_id])}}">{{$store->supplier->name}}</a> إلى مشروع <a href="{{route("showproject",['id'=>$store->project_id])}}">{{$store->project->name}}</a></h3>
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
         <div class="jumbotron">
           <h2 style="border-bottom: 1px solid #000; padding-bottom: 5px;">تفاصيل هذا التوريد</h2>
           <br><br>
           <div class="row">
             <div class="col-sm-12 col-md-10 col-lg-6 col-xl-4 offset-md-1 offset-lg-0" style="margin-bottom: 10px;">
               <div class="circle-div">
                 {{ Str::number_format(round(($store->value*$store->amount),2)) }} جنيه
               </div>
               <p style="text-align: center; margin-top: 8px;">السعر الأجمالى</p>
             </div>
             <div class="col-sm-12 col-md-10 col-lg-6 col-xl-4 offset-md-1 offset-lg-0" style="margin-bottom: 10px;">
               <div class="circle-div">
                 {{Str::number_format(round($store->amount_paid,2))}} جنيه
               </div>
               <p style="text-align: center; margin-top: 8px;">أجمالى المدفوع</p>
             </div>
             <div class="col-sm-12 col-md-10 col-lg-6 col-xl-4 offset-md-1 offset-lg-0" style="margin-bottom: 10px;">
               <div class="circle-div">
                 @if(Str::number_format(round(($store->value*$store->amount) - $store->amount_paid,2))==0)
                   الحساب خالص
                 @else
                  {{Str::number_format(round(($store->value*$store->amount) - $store->amount_paid,2))}}  جنيه
                 @endif
               </div>
               <p style="text-align: center; margin-top: 8px;">أجمالى المبلغ الباقى للمورد</p>
             </div>
             <div class="col-sm-12 col-md-10 col-lg-6 col-xl-4 offset-md-1 offset-lg-0 offset-xl-4" style="margin-bottom: 10px;">
               <div class="circle-div">
                 {{Str::number_format(round($store->amount,2))}} {{$store->unit}}
               </div>
               <p style="text-align: center; margin-top: 8px;">الكمية الموردة</p>
             </div>
           </div>
           @if(($store->value*$store->amount) > $store->amount_paid)
           <div class="center mt-5"><a href="{{route("addPaymentToStore",['id'=>$store->id])}}" class="btn btn-success width-100">أدفع</a></div>
           @endif
         </div>
         <table class="table table-bordered">
           <thead>
             <th>#</th>
             <th>المبلغ المدفوع</th>
             <th>نوع الدفع</th>
             <th>تاريخ الدفع</th>
             <th>أمر</th>
           </thead>
           <tbody>
             <?php $page=$_GET['page']??1; $count=(($page -1)*30)+1;?>
             @foreach ($payments as $payment)
               <tr>
                 <td>{{$count++}}</td>
                 <td>{{Str::number_format($payment->payment_amount)}} جنيه</td>
                 <td>@if($payment->type) قرض @else صندوق @endif</td>
                 <td>{{$payment->created_at->format("d/m/Y")}}</td>
                 <td><a href="" class="btn btn-danger">حذف</a></td>
               </tr>
             @endforeach
           </tbody>
         </table>
         <div class="center">{!!$payments->links()!!}</div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
