<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\StockPileAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StockPileController extends Controller
{
    //
    protected $StockPileAction;

    public function __construct(StockPileAction $StockPileAction)
    {
        $this->StockPileAction = $StockPileAction;
    }
    public function index(Request $request)
    {
        $data = $this->StockPileAction->index($request);

        return $data;
    }
    public function CreateOrUpdate(Request $request)
    {
        $data = $this->StockPileAction->CreateOrUpdate($request);

        return $data;
    }
}
