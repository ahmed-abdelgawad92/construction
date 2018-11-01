<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Payment;
use App\Contract;
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
        //
    }
}
