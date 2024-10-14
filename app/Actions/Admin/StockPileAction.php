<?php

namespace App\Actions\Admin;
use App\Models\EOCLGU\StockPileModel;

class StockPileAction
{
    public function index($request)
    {
       try {
           $query = StockPileModel::query();

           if($request->lgu_id === 'null')
           {
                $query->where('lgu_id','null');
           }

           if($request->lgu_id)
           {
                $query->where('lgu_id', $request->lgu_id);
           }



           $data = $query->first();

           return response()->json([
            'status' => true,
            'message' => 'fetch data success',
            'data' => $data
           ], 200);
       } catch (\Exception $e) {
           return response()->json([
            'status' => false,
            'message' => 'Failed to fetch data',
            'error' => $e->getMessage()
           ], 500);
       }
    }
    public function CreateOrUpdate($request)
    {
        try {
            $validatedData = $request->validate([
                'stock_id' => 'nullable|string',
                'lgu_id' => 'nullable|string',
                'quick_response_fund' => 'nullable|string',
                'familyFood_quantity' => 'nullable|string',
                'familyFood_price' => 'nullable|string',
                'familyKits_quantity' => 'nullable|string',
                'familyKits_price' => 'nullable|string',
                'hygieneKits_quantity' => 'nullable|string',
                'hygieneKits_price' => 'nullable|string',
                'kitchenKits_quantity' => 'nullable|string',
                'kitchenKits_price' => 'nullable|string',
                'mosquitoKits_quantity' => 'nullable|string',
                'mosquitoKits_price' => 'nullable|string',
                'modularTents_quantity' => 'nullable|string',
                'modularTents_price' => 'nullable|string',
                'sleepingKits_quantity' => 'nullable|string',
                'sleepingKits_price' => 'nullable|string',
            ]);

            if($validatedData['stock_id'] === 'null')
            {
                 StockPileModel::create([
                    'lgu_id' => $validatedData['lgu_id'] ?? null,
                    'quick_response_fund' => $validatedData['quick_response_fund'],
                    'familyFood_quantity' => $validatedData['familyFood_quantity'],
                    'familyFood_price' => $validatedData['familyFood_price'],
                    'familyKits_quantity' => $validatedData['familyKits_quantity'],
                    'familyKits_price' => $validatedData['familyKits_price'],
                    'hygieneKits_quantity' => $validatedData['hygieneKits_quantity'],
                    'hygieneKits_price' => $validatedData['hygieneKits_price'],
                    'kitchenKits_quantity' => $validatedData['kitchenKits_quantity'],
                    'kitchenKits_price' => $validatedData['kitchenKits_price'],
                    'mosquitoKits_quantity' => $validatedData['mosquitoKits_quantity'],
                    'mosquitoKits_price' => $validatedData['mosquitoKits_price'],
                    'modularTents_quantity' => $validatedData['modularTents_quantity'],
                    'modularTents_price' => $validatedData['modularTents_price'],
                    'sleepingKits_quantity' => $validatedData['sleepingKits_quantity'],
                    'sleepingKits_price' => $validatedData['sleepingKits_price'],
                ]);
            }
            else
            {
               $stockPile = StockPileModel::where('id',$validatedData['stock_id'])->first();

                if($stockPile)
                {
                    $stockPile->update([
                        'lgu_id' => $validatedData['lgu_id'] ?? null,
                        'quick_response_fund' => $validatedData['quick_response_fund'],
                        'familyFood_quantity' => $validatedData['familyFood_quantity'],
                        'familyFood_price' => $validatedData['familyFood_price'],
                        'familyKits_quantity' => $validatedData['familyKits_quantity'],
                        'familyKits_price' => $validatedData['familyKits_price'],
                        'hygieneKits_quantity' => $validatedData['hygieneKits_quantity'],
                        'hygieneKits_price' => $validatedData['hygieneKits_price'],
                        'kitchenKits_quantity' => $validatedData['kitchenKits_quantity'],
                        'kitchenKits_price' => $validatedData['kitchenKits_price'],
                        'mosquitoKits_quantity' => $validatedData['mosquitoKits_quantity'],
                        'mosquitoKits_price' => $validatedData['mosquitoKits_price'],
                        'modularTents_quantity' => $validatedData['modularTents_quantity'],
                        'modularTents_price' => $validatedData['modularTents_price'],
                        'sleepingKits_quantity' => $validatedData['sleepingKits_quantity'],
                        'sleepingKits_price' => $validatedData['sleepingKits_price'],
                    ]);
                }
            }
            return response()->json([
                'status' => true,
                'message' => 'Stock pile created successfully',

            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create stock pile',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
