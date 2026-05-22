import React from 'react';
import {
  LayoutDashboard, Users, Settings, Package, ShoppingCart, Globe, Shield,
  BarChart3, MessageSquare, Bell, CreditCard, Store
} from 'lucide-react';

interface SubLink {
  title: string;
  icon: React.ElementType;
  href: string;         
  disabled?: boolean;   
}

interface MenuItem {
  title: string;
  icon: React.ElementType;
  href?: string;
  badge?: number;
  badgeColor?: string;
  subLinks?: SubLink[];
  section?: boolean;
  sectionTitle?: string;
}

export const tenancyMenuItems: MenuItem[] = [
  { section: true, sectionTitle: "Overview", icon: BarChart3, title: 'overview' },
  {
    title: "Dashboard",
    icon: LayoutDashboard,
    href: "tenancy.dashboard"
  },
  
  { section: true, sectionTitle: "Management", icon: Package, title: 'management' },
  {
    title: "Tenants",
    icon: Globe,
    subLinks: [
      { title: "All Tenants", icon: Users, href: "tenancy.tenants.index" },
      { title: "Create Tenant", icon: Store, href: "tenancy.tenants.create" },
    ]
  },
  {
    title: "Subscriptions",
    icon: CreditCard,
    subLinks: [
      { title: "Plans", icon: Shield, href: "tenancy.plans.index" },
      { title: "Billing", icon: CreditCard, href: "tenancy.billing.index" },
    ]
  },

  { section: true, sectionTitle: "Settings", icon: Settings, title: 'settings' },
  {
    title: "System Settings",
    icon: Settings,
    href: "tenancy.settings"
  }
];
