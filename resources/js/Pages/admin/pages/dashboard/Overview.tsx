import React, { useState } from 'react';
import { 
  DollarSign, ShoppingCart, TrendingUp, Package, 
  ArrowUpRight, Clock, User
} from 'lucide-react';
import { MetricsCard } from './dashboardComponents/MetricsCard';
import { SalesChart } from './dashboardComponents/SalesChart';
import { TopSellingProductsChart } from './dashboardComponents/TopSellingProductsChart';
import { AdminLayout } from '@/admin/components/layout/AdminLayout';
import { router } from '@inertiajs/react';

interface OverviewProps {
  kpis: {
    total_revenue: number;
    total_orders: number;
    avg_order_value: number;
  };
  charts: {
    sales_trend: any[];
  };
  top_products: any[];
  recent_orders: any[];
  period: string;
}

export default function Overview({ kpis, charts, top_products, recent_orders, period }: OverviewProps) {
  const [loading, setLoading] = useState(false);

  const handlePeriodChange = (newPeriod: string) => {
    setLoading(true);
    router.get(route('dashboard.overview'), { period: newPeriod }, {
      preserveState: true,
      onFinish: () => setLoading(false)
    });
  };

  const periods = [
    { label: '7 Days', value: '7d' },
    { label: '30 Days', value: '30d' },
    { label: 'Year', value: 'year' },
    { label: 'All Time', value: 'all' },
  ];

  const formatCurrency = (val: number) => {
    return new Intl.NumberFormat('en-US', {
      style: 'currency',
      currency: 'MAD',
      maximumFractionDigits: 0
    }).format(val);
  };

  const salesTrendData = charts.sales_trend.map(item => ({
    date: item.date,
    total: parseFloat(item.revenue)
  }));

  return (
    <AdminLayout>
      <div className="min-h-screen p-6 space-y-8 bg-black text-white">
        {/* Header */}
        <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div className="flex items-center gap-4">
            <div className="p-3 bg-orange-600 rounded-2xl shadow-lg shadow-orange-600/20">
              <Package size={24} className="text-white" />
            </div>
            <div>
              <h1 className="text-2xl font-bold tracking-tight">Dashboard Overview</h1>
              <p className="text-sm text-gray-500 font-medium">Welcome back! Here's what's happening today.</p>
            </div>
          </div>
          
          <div className="flex items-center gap-2 p-1.5 bg-white/5 border border-white/10 rounded-2xl">
            {periods.map((p) => (
              <button
                key={p.value}
                onClick={() => handlePeriodChange(p.value)}
                disabled={loading}
                className={`px-4 py-1.5 text-xs font-bold rounded-xl transition-all ${
                  period === p.value 
                    ? 'bg-orange-600 text-white shadow-lg' 
                    : 'text-gray-400 hover:text-gray-200 hover:bg-white/5'
                }`}
              >
                {p.label}
              </button>
            ))}
          </div>
        </div>

        {/* KPI Grid */}
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
          <MetricsCard 
            title="Total Revenue" 
            value={formatCurrency(kpis.total_revenue)} 
            icon={<DollarSign size={20} className="text-orange-500" />}
          />
          <MetricsCard 
            title="Total Orders" 
            value={kpis.total_orders} 
            icon={<ShoppingCart size={20} className="text-blue-500" />}
          />
          <MetricsCard 
            title="Avg. Order Value" 
            value={formatCurrency(kpis.avg_order_value)} 
            icon={<TrendingUp size={20} className="text-green-500" />}
          />
        </div>

        {/* Charts Section */}
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <div className="lg:col-span-2 p-6 rounded-3xl bg-[#111] border border-white/5 space-y-6">
            <div className="flex items-center justify-between">
              <h3 className="text-lg font-bold">Sales Trend</h3>
              <ArrowUpRight size={20} className="text-orange-500" />
            </div>
            <div className="h-[350px]">
              <SalesChart data={salesTrendData} />
            </div>
          </div>

          <div className="p-6 rounded-3xl bg-[#111] border border-white/5 space-y-6">
            <h3 className="text-lg font-bold">Top Products</h3>
            <div className="h-[300px]">
              <TopSellingProductsChart products={top_products} />
            </div>
          </div>
        </div>

        {/* Recent Orders Table */}
        <div className="p-6 rounded-3xl bg-[#111] border border-white/5 space-y-6">
          <div className="flex items-center justify-between">
            <h3 className="text-lg font-bold">Recent Orders</h3>
            <Link href={route('orders.index')} className="text-sm text-orange-500 font-bold hover:underline">
              View All
            </Link>
          </div>
          <div className="overflow-x-auto">
            <table className="w-full text-left border-collapse">
              <thead>
                <tr className="text-xs text-gray-500 uppercase border-b border-white/5">
                  <th className="pb-4 font-bold">Order #</th>
                  <th className="pb-4 font-bold">Customer</th>
                  <th className="pb-4 font-bold">Amount</th>
                  <th className="pb-4 font-bold">Status</th>
                  <th className="pb-4 font-bold">Date</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-white/5">
                {recent_orders.map((order) => (
                  <tr key={order.id} className="text-sm">
                    <td className="py-4 font-bold">{order.order_number}</td>
                    <td className="py-4">
                      <div className="flex items-center gap-2">
                        <div className="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center">
                          <User size={14} className="text-gray-400" />
                        </div>
                        <span>{order.user?.name || 'Guest'}</span>
                      </div>
                    </td>
                    <td className="py-4 font-bold">{formatCurrency(order.total_amount)}</td>
                    <td className="py-4">
                      <span className={`px-2 py-1 rounded-full text-[10px] font-bold uppercase ${
                        order.order_status === 'delivered' ? 'bg-green-500/10 text-green-500' :
                        order.order_status === 'pending' ? 'bg-orange-500/10 text-orange-500' :
                        'bg-gray-500/10 text-gray-500'
                      }`}>
                        {order.order_status}
                      </span>
                    </td>
                    <td className="py-4 text-gray-500 flex items-center gap-1">
                      <Clock size={12} />
                      {new Date(order.created_at).toLocaleDateString()}
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </AdminLayout>
  );
}

import { Link } from '@inertiajs/react';
