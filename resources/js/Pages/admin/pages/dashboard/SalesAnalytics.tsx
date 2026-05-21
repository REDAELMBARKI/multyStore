import React, { useState } from 'react';
import { 
  DollarSign, ShoppingCart, TrendingUp, TrendingDown,
  ArrowUpRight, ArrowDownRight, Package, CreditCard,
  BarChart3, PieChart, Activity
} from 'lucide-react';
import { MetricsCard } from './dashboardComponents/MetricsCard';
import { SalesChart } from './dashboardComponents/SalesChart';
import { StatusDonutChart } from './dashboardComponents/StatusDonutChart';
import { TopSellingProductsChart } from './dashboardComponents/TopSellingProductsChart';
import { AdminLayout } from '@/admin/components/layout/AdminLayout';
import { router } from '@inertiajs/react';

interface SalesAnalyticsProps {
  kpis: {
    total_revenue: { value: number; change: number };
    orders_count: { value: number; change: number };
    avg_order_value: { value: number; change: number };
    delivered_rate: number;
  };
  charts: {
    revenue_over_time: any[];
    payment_methods: any[];
    order_status: any[];
  };
  top_products: any[];
  period: string;
}

export default function SalesAnalytics({ kpis, charts, top_products, period }: SalesAnalyticsProps) {
  const [loading, setLoading] = useState(false);

  const handlePeriodChange = (newPeriod: string) => {
    setLoading(true);
    router.get(route('dashboard.sales_analytics'), { period: newPeriod }, {
      preserveState: true,
      onFinish: () => setLoading(false)
    });
  };

  const periods = [
    { label: '7 Days', value: '7d' },
    { label: '30 Days', value: '30d' },
    { label: '90 Days', value: '90d' },
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

  const revenueChartData = charts.revenue_over_time.map(item => ({
    date: item.date,
    total: parseFloat(item.revenue)
  }));

  const orderStatusData = charts.order_status.map(item => ({
    status: item.order_status,
    count: item.count
  }));

  return (
    <AdminLayout>
      <div className="min-h-screen p-6 space-y-8 bg-black text-white">
        <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div className="flex items-center gap-4">
            <div className="p-3 bg-orange-600 rounded-2xl shadow-lg shadow-orange-600/20">
              <DollarSign size={24} className="text-white" />
            </div>
            <div>
              <h1 className="text-2xl font-bold tracking-tight">Sales Analytics</h1>
              <p className="text-sm text-gray-500 font-medium">Detailed breakdown of your store's performance</p>
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

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <MetricsCard 
            title="Total Revenue" 
            value={formatCurrency(kpis.total_revenue.value)} 
            icon={<DollarSign size={20} className="text-orange-500" />}
            change={kpis.total_revenue.change}
          />
          <MetricsCard 
            title="Total Orders" 
            value={kpis.orders_count.value} 
            icon={<ShoppingCart size={20} className="text-blue-500" />}
            change={kpis.orders_count.change}
          />
          <MetricsCard 
            title="Avg. Order Value" 
            value={formatCurrency(kpis.avg_order_value.value)} 
            icon={<Activity size={20} className="text-green-500" />}
            change={kpis.avg_order_value.change}
          />
          <MetricsCard 
            title="Delivered Rate" 
            value={`${kpis.delivered_rate}%`} 
            icon={<Package size={20} className="text-purple-500" />}
          />
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <div className="lg:col-span-2 p-6 rounded-3xl bg-[#111] border border-white/5 space-y-6">
            <div className="flex items-center justify-between">
              <div>
                <h3 className="text-lg font-bold">Revenue Growth</h3>
                <p className="text-sm text-gray-500">Revenue from delivered orders over time</p>
              </div>
              <TrendingUp size={20} className="text-orange-500" />
            </div>
            <div className="h-[350px]">
              <SalesChart data={revenueChartData} />
            </div>
          </div>

          <div className="p-6 rounded-3xl bg-[#111] border border-white/5 space-y-6">
            <div className="flex items-center justify-between">
              <h3 className="text-lg font-bold">Payment Methods</h3>
              <CreditCard size={20} className="text-blue-500" />
            </div>
            <div className="space-y-4">
              {charts.payment_methods.map((method, idx) => (
                <div key={idx} className="flex items-center justify-between p-4 rounded-2xl bg-white/5 border border-white/5">
                  <div className="flex items-center gap-3">
                    <div className={`p-2 rounded-xl ${
                      method.label === 'COD' ? 'bg-orange-500/10 text-orange-500' : 
                      method.label === 'CARD' ? 'bg-blue-500/10 text-blue-500' : 'bg-green-500/10 text-green-500'
                    }`}>
                      <CreditCard size={16} />
                    </div>
                    <div>
                      <p className="text-sm font-bold">{method.label}</p>
                      <p className="text-xs text-gray-500">{method.count} orders</p>
                    </div>
                  </div>
                  <p className="text-sm font-bold">{formatCurrency(method.total)}</p>
                </div>
              ))}
            </div>
          </div>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
          <div className="p-6 rounded-3xl bg-[#111] border border-white/5 space-y-6">
            <div className="flex items-center justify-between">
              <h3 className="text-lg font-bold">Top Selling Products</h3>
              <BarChart3 size={20} className="text-green-500" />
            </div>
            <div className="h-[300px]">
              <TopSellingProductsChart products={top_products} />
            </div>
          </div>

          <div className="p-6 rounded-3xl bg-[#111] border border-white/5 space-y-6">
            <div className="flex items-center justify-between">
              <h3 className="text-lg font-bold">Order Status Distribution</h3>
              <PieChart size={20} className="text-purple-500" />
            </div>
            <div className="h-[300px]">
              <StatusDonutChart data={orderStatusData} />
            </div>
          </div>
        </div>
      </div>
    </AdminLayout>
  );
}
