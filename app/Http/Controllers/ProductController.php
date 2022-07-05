<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       try {
            $products = Product::paginate(10);

            if($products) {
                $response = response([
                    'status' => "success",
                    'message' => "Data Porducts",
                    'data'  => [
                        'product' => $products
                        ]
                    ], 200);
            }else {
                $response = response([
                    'status' => "success",
                    'message' => "Data Porducts",
                    'data'  => [
                        'product' => ''
                        ]
                    ], 200);
            }
           return $response;
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
            $data = $request->validate([
                'name' => 'required',
                'type' => 'required',
                'price' => 'required',
                'quantity' => 'required',
            ]);
            $data['id']  = Uuid::uuid4()->getHex();

            $product = Product::create($data);

            return response([
                'status' => "succes",
                'message' => 'product has been created',
                'data'  =>  [
                    'product' => $product
                ]
                ], 400);
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
            $product = Product::findOrFail($id);

            return response([
                'status' => "succes",
                'message' => 'data product',
                'data'  =>  [
                    'product' => $product
                ]
                ], 400);

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
        try {
            $data = $request->validate([
                'name' => 'required',
                'type' => 'required',
                'price' => 'required',
                'quantity' => 'required',
            ]);

            $product = Product::findOrFail($id);
            $product->update($data);

            return response([
                'status' => "succes",
                'message' => 'product have been updated',
                'data'  =>  [
                    'product' => $product
                ]
                ], 400);

        } catch (\Throwable $th) {
            return response([
                'status' => "error",
                'message' => $th->getMessage(),
                'data'  => ""
                ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->trashed();

            return response([
                'status' => "succes",
                'message' => 'product have been deleted',
                'data'  =>  [
                    'product' => $product
                ]
                ], 400);
        } catch (\Throwable $th) {
            return response([
                'status' => "error",
                'message' => $th->getMessage(),
                'data'  => ""
                ], 400);
        }
    }
}
