import React from 'react';
import { BarChart, Bar, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer } from 'recharts';

interface TopSellingProductsChartProps {
  products: any[];
}

export function TopSellingProductsChart({ products }: TopSellingProductsChartProps) {
  return (
    <div className="h-full w-full">
      <ResponsiveContainer width="100%" height="100%">
        <BarChart data={products} margin={{ top: 10, right: 10, left: 0, bottom: 40 }}>
          <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="#ffffff05" />
          <XAxis 
            dataKey="name" 
            axisLine={false}
            tickLine={false}
            tick={{ fill: '#4b5563', fontSize: 10, fontWeight: 600 }}
            angle={-25}
            textAnchor="end"
            interval={0}
          />
          <YAxis 
            axisLine={false}
            tickLine={false}
            tick={{ fill: '#4b5563', fontSize: 11, fontWeight: 600 }}
            tickFormatter={(value) => value >= 1000 ? `${(value / 1000).toFixed(0)}K` : value}
          />
          <Tooltip 
            contentStyle={{ 
              backgroundColor: '#1a1a1a', 
              borderColor: '#ffffff10',
              borderRadius: '12px',
              fontSize: '12px',
              fontWeight: 'bold',
              color: '#fff'
            }}
            itemStyle={{ color: '#f97316' }}
            cursor={{ fill: '#ffffff05' }}
            formatter={(value: any) => [`${value.toLocaleString()} MAD`, 'Revenue']}
          />
          <Bar 
            dataKey="revenue" 
            fill="#f97316" 
            radius={[6, 6, 0, 0]}
            animationDuration={1500}
          />
        </BarChart>
      </ResponsiveContainer>
    </div>
  );
}
