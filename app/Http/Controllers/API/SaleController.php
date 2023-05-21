<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaleRequest;
use App\Http\Requests\UpdateSaleRequest;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleTransaction;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sales = Sale::with(["client","seller","saleTransactions" => function($q){
            return $q->with(["product"]);
        }])->latest()->get();
        return response()->json($sales);
    }

    public function showCreate(){
        $client = User::query()->where("role","client")->get();
        $saler = User::query()->where("role","saler")->get();
        $product = Product::query()->get();
        return response()->json([
            "product" => $product,
            "saler" => $saler,
            "client" => $client,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SaleRequest $request)
    {
        try{
            $products = $request->sales;
            DB::beginTransaction();
            $sale = Sale::create([
                "client_id" => $request->client_id,
                "seller_id" => $request->seller_id,
                "total" => 0.0,
            ]);
            $FinalPrice = 0;
            foreach($products as $product){
                $temp = Product::query()->find($product["product_id"]);
                $tempPrice = $temp->price * $product["quantity"];
                $FinalPrice += $tempPrice;
                SaleTransaction::create([
                     "sale_id" => $sale->id,
                     "product_id"  => $temp->id,
                     "quantity" => $product["quantity"],
                     "price" => $tempPrice,
                ]);
            }
            $sale->update([
                "total" => $FinalPrice
            ]);
            Log::channel('update_sales')->info('New sale operation created', ['sale_transaction_id' => $sale->id]);
            DB::commit();
            return response()->json($sale, 201);
        }catch(Exception $e){
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        $sale = Sale::with(["client","seller","saleTransactions" => function($q){
            return $q->with(["product"]);
        }])->findOrFail($sale->id);
        return response()->json($sale);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSaleRequest $request, Sale $sale)
    {
        try{
            DB::beginTransaction();
            $products = $request->products;
            $sale = Sale::query()->findOrFail($sale->id);
            $FinalPrice = 0;
            foreach($products as $product){
                $temp1 = SaleTransaction::query()->find($product["SaleTransaction_id"]);
                $temp = Product::query()->find($temp1->product_id);
                $tempPrice = $temp->price * $product["quantity"];
                $FinalPrice += $tempPrice;
                $temp->update([
                    "quantity" => $product["quantity"],
                    "price" => $tempPrice,
                ]);
            
            }
            $sale->update([
                "total" => $FinalPrice
            ]);
            Log::channel('update_sales')->info('New sale operation updated', ['sale_transaction_id' => $sale->id]);
            DB::commit();
            return response()->json($sale);
        }catch(Exception $e){
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        $sale->delete();
        Log::channel('update_sales')->info(' sale operation Deleted', ['sale_transaction_id' => $sale->id]);
        DB::commit();
        return response()->json(null, 204);
    }
}
