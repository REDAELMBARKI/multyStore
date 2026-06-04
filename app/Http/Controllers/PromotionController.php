<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use App\Models\ShippingSetting;
use App\Services\CartService;
use App\Services\Discount\PromotionService;
use App\Services\ShippingService;
use App\Services\StoreSettingService;
use Illuminate\Http\Request;

class PromotionController extends Controller
{


    private $store_currency ;


    public function __construct(
        private PromotionService $promotionService ,
        private CartService $cartService, 
        private StoreSettingService $storeSettingsService  , 
        private ShippingService $shippingService
        )
    {
        $this->store_currency = $storeSettingsService->getStoreCurrency();

    }
      public function getAll()
    {
        $promotions = $this->promotionService->getDbPromotions();
      
        $transformed = $promotions->map(function($promo) {
            return [
                'id' => $promo->id,
                'name' => $promo->name,
                'discount' => $promo->type === 'percentage' ? "{$promo->value}%" : "FREE",
                'expiry' => $promo->valid_until ? $promo->valid_until->format('Y-m-d') : null,
            ];
        });

        return response()->json($transformed);
    }


      public function calculateBestRewardForUser()
    {
        $items = $this->cartService->getCartItems();
        $cartTotal = $this->cartService->calculateCartItemsSubtotal($items->toArray());
        
        if ($cartTotal == 0) {
            return response()->json([
                'bestRewardForUser' => null,
                'milestones' => []
            ], 200);
        }

        $globalShipping = ShippingSetting::where('free_shipping_type' , 'amount')->first();
        $milestones = $this->promotionService->getPromotionMillestones();
        $goal = (float) $globalShipping->free_shipping_threshold_amount ;
        $remaining = max(0, $goal - $cartTotal) ;
        if ($globalShipping 
            && $globalShipping->free_shipping_threshold_amount > 0
            && !$milestones->contains(fn($m) => $m['type']  === 'free_shipping')
            ) {
            $milestones->push([
                'goal' => $goal,
                'label' => 'FREE SHIPPING',
                'percentage' => null ,
                'type' => 'free_shipping',
                'estimated_value' => (float) $this->shippingService->avgShippingCost(),
                'message' => "Add " .$remaining . " " .$this->store_currency  ." and get a Free Shipping " 
            ]);
        }


        // 2. Process milestones: Group by goal (keep best reward per goal) and sort
        $sortedMilestones = $milestones
                            ->groupBy('goal')
                            ->map(fn($group) => $group->sortByDesc("estimated_value")->first())
                            ->sortBy('goal')
                            ->values();

        // 3. Strictly Upward: Filter out any higher-goal milestone that offers a worse reward
        $finalMilestones = collect();
        $currentMaxValue = -1;

        //   keep th scalling of the reward gos up only keep if has estiimatedsave biger then the previous
        foreach ($sortedMilestones as $m) {
            if ($m['estimated_value'] > $currentMaxValue) {
                $finalMilestones->push($m);
                $currentMaxValue = $m['estimated_value'];
            }
        }

        // 4. Recalculate next milestone from the filtered set
        $nextMilestone = $finalMilestones->first(fn($m) => $m['goal'] > $cartTotal);
        $currentMilestone = $finalMilestones->last(fn($m) => $m['goal'] <= $cartTotal);
        return response()->json([
            'nextMilestone' => $nextMilestone,
            'currentMilestone' => $currentMilestone ,
            'milestones' => $finalMilestones
        ], 200);
    }




    public function validateScalling(){ // befreo store or update validation the scalling
    // Higher goal => Higher estimated reward.
    // Lower goal => Lower estimated reward.
    }

}
