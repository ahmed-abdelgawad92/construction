<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Payment;
use App\Contract;
use App\Log;
use Validator;
use Auth;

class PaymentController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Contract $contract
     * @return \Illuminate\Http\Response
     */
    public function showAllContractorPayments($id)
    {
      $contract = Contract::where('id',$id)->where('deleted',0)->firstOrFail();
      $payments = $contract->payments();
      $total_payment = $contract->paidProductions();
      $contractor = $contract->contractor;
      $term = $contract->term;
      $array=[
        'contract'=>$contract,
        'contractor'=>$contractor,
        'term'=>$term,
        'payments'=>$payments,
        'active'=>'trans',
        'total_payment'=>$total_payment
      ];
      return view('contract.payment',$array);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $payment = Payment::findOrFail($id);
      $contract = Contract::findOrFail($payment->table_id);
      if($payment->deleted==0){
        $payment->deleted=1;
        $saved= $payment->save();
        //check if saved correctly
        if (!$saved) {
          return redirect()->back()->with("insert_error","حدث عطل خلال حذف هذه المعاملة");
        }
        $log=new Log;
        $log->table="payments";
        $log->action="delete";
        $log->record_id=$id;
        $log->user_id=Auth::user()->id;
        $log->description='قام بحذف معاملة بقيمة '.$payment->payment_amount.' جنيه من المقاول '.$contract->contractor->name.' بالبند '.$contract->term->code.' بمشروع '.$payment->project->name;
        $log->save();
      }
      return redirect()->back()->with("success","تم حذف المعاملة بنجاح");
    }
}
