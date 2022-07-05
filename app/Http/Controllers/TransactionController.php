<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            if(Auth::user()->role == 'Admin'){
                $transactions = Transaction::latest()->paginate(10);

            }else {
                $transactions = Transaction::where('user_id', Auth::user()->id)->latest()->paginate(10);
            }

           return response([
                'status' => "success",
                'message' => "Data Transactions",
                'data'  => [
                    'Transactions' => $transactions
                    ]
                ], 200);

        } catch (\Throwable $th) {
            return response([
                'status' => "error",
                'message' => $th->getMessage(),
                'data'  => ""
                ], 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                    'id_product' => 'required',
                    'quantity' => 'required|numeric'
            ]);
            if(Auth::user()->role == 'Admin'){
                return response([
                    'status' => "error",
                    'message' => "Admin Can't make transactions",
                    'data'  => ''
                    ], 500);
            }
            $product = Product::findOrFail($request->id);
            if(!$product){
                return response([
                    'status' => "success",
                    'message' => "Product Not Found",
                    'data'  => ''
                    ], 404);
            }
            if($product->quantity == 0) {
                return response([
                    'status' => "success",
                    'message' => "Sorry product stock is empty",
                    'data'  => ''
                    ], 400);
            }
            $tax = $product->price * 0.1;
            $biayaAdmin = ($product->price + $tax) * 0.05;
            $amount = $product->price * $request->quantity;
            $total = $amount + $tax + $biayaAdmin;

            $transaction = new Transaction;
            $transaction->user_id = Auth::user()->id;
            $transaction->amount = $amount;
            $transaction->tax = $tax;
            $transaction->admin_fee = $biayaAdmin;
            $transaction->total = $total;

            if($transaction->save()){
                $product->quantity = $product->quantity - 1;
                $product->save();
            };


            return response([
                'status' => "success",
                'message' => "Transactions has been saved",
                'data'  => [
                    'Transactions' => $transaction->id
                    ]
                ], 200);
        } catch (\Throwable $th) {
            return response([
                'status' => "error",
                'message' => $th->getMessage(),
                'data'  => ""
                ], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {

            $transaction = Transaction::findOrFail($id);
            if($transaction->user_id == Auth::user()->id || Auth::user()->role == "Admin") {

                return response([
                    'status' => "success",
                    'message' => "Data Transaction",
                    'data'  => [
                        'Transactions' => $transaction
                        ]
                    ], 200);
            }else {
                return response([
                    'status' => "Error",
                    'message' => "Data Transaction not Found",
                    'data'  => [
                        'Transactions' => ''
                        ]
                    ], 404);
            }
        } catch (\Throwable $th) {
            return response([
                'status' => "error",
                'message' => $th->getMessage(),
                'data'  => ""
                ], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
