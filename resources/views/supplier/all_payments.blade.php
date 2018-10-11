@extends('layouts.master')
@section('title','جميع المبالغ المدفوعة للمورد '.$supplier->name)
@section('content')
<div class="content">
   <div class="row">
     <div class="col-md-12 col-lg-12 col-sm-12">
       <div class="panel panel-default">
         <div class="panel-heading">
           <h3>جميع المبالغ المدفوعة للمورد <a href="{{route("showsupplier",['id'=>$supplier->id])}}">{{$supplier->name}}</a></h3>
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
           <h2 style="border-bottom: 1px solid #000; padding-bottom: 5px;">أجمالى حساب المورد منذ أنشائه</h2>
           <br><br>
           <div class="row">
             <div class="col-sm-12 col-md-10 col-lg-6 col-xl-4 offset-md-1 offset-lg-0" style="margin-bottom: 10px;">
               <div class="circle-div">
                 {{ Str::number_format(round($allRaws->value,2)) }} جنيه
               </div>
               <p style="text-align: center; margin-top: 8px;">سعر جملة الواردات</p>
             </div>
             <div class="col-sm-12 col-md-10 col-lg-6 col-xl-4 offset-md-1 offset-lg-0" style="margin-bottom: 10px;">
               <div class="circle-div">
                 {{Str::number_format(round($allRaws->paid,2))}} جنيه
               </div>
               <p style="text-align: center; margin-top: 8px;">أجمالى المدفوع</p>
             </div>
             <div class="col-sm-12 col-md-10 col-lg-6 col-xl-4 offset-md-1 offset-lg-0" style="margin-bottom: 10px;">
               <div class="circle-div">
                 {{ Str::number_format(round($allRaws->value - $allRaws->paid,2)) }} جنيه
               </div>
               <p style="text-align: center; margin-top: 8px;">أجمالى المبلغ الباقى للمورد</p>
             </div>
           </div>
         </div>
         <table class="table table-bordered">
           <thead>
             <th>#</th>
             <th>نوع الخام</th>
             <th>الكمية</th>
             <th>المبلغ المدفوع</th>
             <th>نوع الدفع</th>
             <th>تاريخ الدفع</th>
             <th>تاريخ توريد الخام</th>
             <th>أمر</th>
           </thead>
           <tbody>
             <?php $page=$_GET['page']??1; $count=(($page -1)*30)+1;?>
             @foreach ($payments as $payment)
               <tr>
                 <td>{{$count++}}</td>
                 <td>{{$payment->raw_type}}</td>
                 <td>{{Str::number_format($payment->amount)}} {{$payment->unit}}</td>
                 <td>{{Str::number_format($payment->payment_amount)}} جنيه</td>
                 <td>@if($payment->type) قرض @else صندوق @endif</td>
                 <td>{{$payment->created_at->format("d/m/Y")}}</td>
                 <td>{{date("d/m/Y",strtotime($payment->store_created_at))}}</td>
                 <td>
                   <a href="{{route("allstorePayments",['id'=>$payment->table_id])}}" class="btn btn-primary">جميع المبالغ المدفوعة لهذه الكمية</a>
                   <a href="" class="btn btn-danger">حذف</a>
                 </td>
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
