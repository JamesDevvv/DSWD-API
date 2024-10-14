<?php

namespace App\Models\EOCLGU;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockPileModel extends Model
{
    use HasFactory;


    protected $table = 'stock_piles';

    protected $fillable = [
        'stock_id',
        'lgu_id',
        'quick_response_fund',
        'familyFood_quantity',
        'familyFood_price',
        'familyKits_quantity',
        'familyKits_price',
        'hygieneKits_quantity',
        'hygieneKits_price',
        'kitchenKits_quantity',
        'kitchenKits_price',
        'mosquitoKits_quantity',
        'mosquitoKits_price',
        'modularTents_quantity',
        'modularTents_price',
        'sleepingKits_quantity',
        'sleepingKits_price',
    ];
}
