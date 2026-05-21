import React from 'react';
import { TrendingUp, TrendingDown } from 'lucide-react';

interface MetricsCardProps {
  title: string;
  value: string | number;
  icon: React.ReactNode;
  change?: number;
}

export function MetricsCard({ title, value, icon, change }: MetricsCardProps) {
  const isPositive = change !== undefined && change > 0;
  const isNegative = change !== undefined && change < 0;

  return (
    <div className="p-6 rounded-3xl bg-[#111] border border-white/5 space-y-4">
      <div className="flex items-start justify-between">
        <div className="space-y-1">
          <h3 className="text-3xl font-bold text-white">{value}</h3>
          <p className="text-sm text-gray-500 font-medium">{title}</p>
        </div>
        <div className="p-3 bg-white/5 rounded-2xl">
          {icon}
        </div>
      </div>
      
      {change !== undefined && (
        <div className="flex items-center gap-2">
          <div className={`flex items-center gap-1 text-xs font-bold px-2 py-1 rounded-full ${
            isPositive ? 'text-green-500 bg-green-500/10' : 
            isNegative ? 'text-red-500 bg-red-500/10' : 
            'text-gray-500 bg-gray-500/10'
          }`}>
            {isPositive ? <TrendingUp size={12} /> : isNegative ? <TrendingDown size={12} /> : null}
            {Math.abs(change)}%
          </div>
          <span className="text-xs text-gray-600 font-medium">vs last period</span>
        </div>
      )}
    </div>
  );
}
