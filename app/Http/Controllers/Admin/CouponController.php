<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CouponRequest;
use App\Services\Discount\CouponService;
use Inertia\Inertia;
use App\Models\Product;
use App\Models\Category;

class CouponController extends Controller
{
    public function __construct(private CouponService $couponService)
    {
    }

    public function index()
    {
        return Inertia::render('admin/pages/coupons/index', [
            'coupons' => $this->couponService->getAllCoupons()
        ]);
    }

    public function create()
    {
        return Inertia::render('admin/pages/coupons/create', [
            'products' => Product::all(['id', 'name']),
            'categories' => Category::whereNull('parent_id')->get(['id', 'name']),
            'subCategories' => Category::whereNotNull('parent_id')->get(['id', 'name']),
        ]);
    }

    public function store(CouponRequest $request)
    {
        $this->couponService->createCoupon($request->validated());
        return redirect()->route('coupons.index')->with('success', 'Coupon created successfully');
    }

    public function edit($id)
    {
        $coupon = $this->couponService->getCouponById($id);
        if (!$coupon) {
            return redirect()->route('coupons.index')->with('error', 'Coupon not found');
        }

        return Inertia::render('admin/pages/coupons/create', [
            'coupon' => $coupon,
            'products' => Product::all(['id', 'name']),
            'categories' => Category::whereNull('parent_id')->get(['id', 'name']),
            'subCategories' => Category::whereNotNull('parent_id')->get(['id', 'name']),
        ]);
    }

    public function update(CouponRequest $request, $id)
    {
        $this->couponService->updateCoupon($id, $request->validated());
        return redirect()->route('coupons.index')->with('success', 'Coupon updated successfully');
    }

    public function destroy($id)
    {
        $this->couponService->deleteCoupon($id);
        return redirect()->route('coupons.index')->with('success', 'Coupon deleted successfully');
    }
}
