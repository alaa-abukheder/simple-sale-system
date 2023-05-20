<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SaleTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class SaleTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $saleTransactions = SaleTransaction::with('sale', 'product')->get();
        return response()->json($saleTransactions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'sale_id' => 'required|integer',
            'product_id' => 'required|integer',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
        ]);
        $saleTransaction = SaleTransaction::create($validatedData);
        Log::channel('update_sales')->info('New sale transaction created', ['sale_transaction_id' => $saleTransaction->id]);
        return response()->json($saleTransaction, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(SaleTransaction $saleTransaction)
    {
        $saleTransaction->load('sale', 'product');
        return response()->json($saleTransaction);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SaleTransaction $saleTransaction)
    {
        $saleTransaction->update($request->all());
        Log::channel('update_sales')->info('New sale transaction edited', ['sale_transaction_id' => $saleTransaction->id]);
        return response()->json($saleTransaction);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaleTransaction $saleTransaction)
    {
        $saleTransaction->delete();
        return response()->json(null, 204);
    }
}
