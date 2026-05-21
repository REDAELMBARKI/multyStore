<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use App\Services\Discount\PromotionService;
use Illuminate\Http\Request;

class PromotionController extends Controller
{


    public function __construct(private PromotionService $promotionService)
    {
    }
      public function getAll()
    {
        $promotions = $this->promotionService->getDbPromotions();
      
        $transformed = $promotions->map(function($promo) {
            return [
                'id' => $promo->id,
                'name' => $promo->name,
                'discount' => $promo->type === 'percentage' ? "{$promo->value}%" : "{$promo->value} " . config('app.currency', 'MAD'),
                'expiry' => $promo->valid_until ? $promo->valid_until->format('Y-m-d') : null,
            ];
        });

        return response()->json($transformed);
    }

}
