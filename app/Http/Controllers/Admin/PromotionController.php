<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PromotionRequest;
use App\Services\Discount\PromotionService;
use Inertia\Inertia;
use App\Models\Product;
use App\Models\Category;

class PromotionController extends Controller
{
    public function __construct(private PromotionService $promotionService)
    {
    }

    public function index()
    {
        return Inertia::render('admin/pages/promotions/index', [
            'promotions' => $this->promotionService->getAllPromotions()
        ]);
    }

    public function create()
    {
        return Inertia::render('admin/pages/promotions/create', [
            'products' => Product::all(['id', 'name']),
            'categories' => Category::whereNull('parent_id')->get(['id', 'name']),
            'subCategories' => Category::whereNotNull('parent_id')->get(['id', 'name']),
        ]);
    }

    public function store(PromotionRequest $request)
    {
        $this->promotionService->createPromotion($request->validated());
        return redirect()->route('promotions.index')->with('success', 'Promotion created successfully');
    }

    public function edit($id)
    {
        $promotion = $this->promotionService->getPromotionById($id);
        if (!$promotion) {
            return redirect()->route('promotions.index')->with('error', 'Promotion not found');
        }

        return Inertia::render('admin/pages/promotions/create', [
            'promotion' => $promotion,
            'products' => Product::all(['id', 'name']),
            'categories' => Category::whereNull('parent_id')->get(['id', 'name']),
            'subCategories' => Category::whereNotNull('parent_id')->get(['id', 'name']),
        ]);
    }

    public function update(PromotionRequest $request, $id)
    {
        $this->promotionService->updatePromotion($id, $request->validated());
        return redirect()->route('promotions.index')->with('success', 'Promotion updated successfully');
    }

    public function destroy($id)
    {
        $this->promotionService->deletePromotion($id);
        return redirect()->route('promotions.index')->with('success', 'Promotion deleted successfully');
    }
}
