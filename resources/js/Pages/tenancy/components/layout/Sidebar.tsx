import React, { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';
import { route } from 'ziggy-js';
import {
  ChevronDown,
  ChevronLeft,
  ChevronRight,
} from 'lucide-react';
import { useTenancyTheme } from '../../hooks/useTenancyTheme';
import { tenancyMenuItems } from '../../data/tenancyNavigationsLinks';


export function TenancySidebar({ collapsed, setCollapsed }: { collapsed: boolean; setCollapsed: (val: boolean) => void }) {
  const [expandedItem, setExpandedItem] = useState<string | null>('Dashboard');
  const { url } = usePage();
  const { theme } = useTenancyTheme();

  const toggleExpanded = (itemTitle: string) => {
    setExpandedItem(expandedItem === itemTitle ? null : itemTitle);
  };

  return (
    <div
      style={{
        backgroundColor: theme.sidebarBg,
        color: theme.sidebarFg,
        height: '100vh',
        position: 'sticky',
        top: 0,
        display: 'flex',
        flexDirection: 'column',
        borderRight: `1px solid ${theme.sidebarBorder}`,
        transition: 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)',
        width: collapsed ? '80px' : '256px',
        overflow: 'hidden',
      }}
    >
      {/* Logo Header */}
      <div
        style={{
          padding: '1rem',
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'space-between',
          borderBottom: `1px solid ${theme.sidebarBorder}`,
          backgroundColor: theme.sidebarBg,
        }}
      >
        {!collapsed && (
          <div
            style={{
              color: theme.sidebarFg,
              fontWeight: 700,
              fontSize: '1.25rem',
              letterSpacing: '-0.025em',
            }}
          >
            Tenancy
          </div>
        )}
        <div style={{ display: 'flex', alignItems: 'center', gap: '0.25rem' }}>
          <button
            onClick={() => setCollapsed(!collapsed)}
            title={collapsed ? 'Expand sidebar' : 'Collapse sidebar'}
            style={{
              color: theme.sidebarMutedFg,
              padding: '0.5rem',
              borderRadius: '0.375rem',
              transition: 'all 0.2s',
              border: 'none',
              background: 'transparent',
              cursor: 'pointer',
            }}
            onMouseEnter={(e: React.MouseEvent<HTMLButtonElement>) => {
              e.currentTarget.style.color = theme.sidebarFg;
              e.currentTarget.style.backgroundColor = theme.sidebarHover;
            }}
            onMouseLeave={(e: React.MouseEvent<HTMLButtonElement>) => {
              e.currentTarget.style.color = theme.sidebarMutedFg;
              e.currentTarget.style.backgroundColor = 'transparent';
            }}
          >
            {collapsed ? <ChevronRight size={20} /> : <ChevronLeft size={20} />}
          </button>
        </div>
      </div>

      {/* Navigation */}
      <div
        className="sidebar-scroll"
        style={{
          flex: 1,
          overflowY: 'auto',
          paddingTop: '1rem',
          paddingBottom: '1rem',
        }}
      >
        <nav style={{ paddingBlock: '0 0.75rem' }}>
          {tenancyMenuItems.map((item, index) => {
            if (item.section) {
              return (
                <div key={`section-${index}`} style={{ padding: '0.75rem 1rem', marginTop: index > 0 ? '1rem' : '0' }}>
                  {!collapsed && (
                    <h4 style={{ fontSize: '0.75rem', fontWeight: 600, color: theme.sidebarMutedFg, textTransform: 'uppercase', letterSpacing: '0.05em' }}>
                      {item.sectionTitle}
                    </h4>
                  )}
                </div>
              );
            }

            const Icon = item.icon;
            const isExpanded = expandedItem === item.title;
            const hasActiveChild = item.subLinks?.some((sub) => sub.href === url);

            return (
              <div key={item.title} style={{ marginBottom: '0.25rem' }}>
                <button
                  onClick={() => item.subLinks && toggleExpanded(item.title)}
                  style={{
                    width: '100%',
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'space-between',
                    padding: '0.75rem 1rem',
                    transition: 'all 0.2s',
                    border: 'none',
                    cursor: 'pointer',
                    backgroundColor: isExpanded || hasActiveChild ? theme.sidebarActive : 'transparent',
                    color: isExpanded || hasActiveChild ? theme.sidebarActiveFg : theme.sidebarFg,
                  }}
                  onMouseEnter={(e: React.MouseEvent<HTMLButtonElement>) => {
                    if (!isExpanded && !hasActiveChild) {
                      e.currentTarget.style.backgroundColor = theme.sidebarHover;
                    }
                  }}
                  onMouseLeave={(e: React.MouseEvent<HTMLButtonElement>) => {
                    if (!isExpanded && !hasActiveChild) {
                      e.currentTarget.style.backgroundColor = 'transparent';
                    }
                  }}
                >
                  <div style={{ display: 'flex', alignItems: 'center', gap: '0.75rem' }}>
                    <Icon size={20} />
                    {!collapsed && <span style={{ fontSize: '0.875rem', fontWeight: 500 }}>{item.title}</span>}
                  </div>
                  {!collapsed && item.subLinks && (
                    <ChevronDown size={16} style={{ transition: 'transform 0.3s', transform: isExpanded ? 'rotate(180deg)' : 'rotate(0deg)' }} />
                  )}
                </button>

                {item.subLinks && isExpanded && !collapsed && (
                  <div style={{ padding: '0.5rem 0', animation: 'fadeIn 0.2s ease-out' }}>
                    {item.subLinks.map((subLink, subIndex) => {
                      const SubIcon = subLink.icon;
                      return (
                        <Link
                          key={`${subLink.title}-${subIndex}`}
                          href={route(subLink.href)}
                          style={{
                            display: 'flex',
                            alignItems: 'center',
                            gap: 10,
                            padding: '8px 16px 8px 42px',
                            fontSize: '0.825rem',
                            fontWeight: 500,
                            textDecoration: 'none',
                            color: theme.sidebarMutedFg,
                            transition: 'color 0.2s',
                          }}
                          onMouseEnter={(e: React.MouseEvent<HTMLAnchorElement>) => e.currentTarget.style.color = theme.sidebarFg}
                          onMouseLeave={(e: React.MouseEvent<HTMLAnchorElement>) => e.currentTarget.style.color = theme.sidebarMutedFg}
                        >
                          <SubIcon size={16} />
                          <span>{subLink.title}</span>
                        </Link>
                      );
                    })}
                  </div>
                )}
              </div>
            );
          })}
        </nav>
      </div>

      <style>
        {`
          .sidebar-scroll::-webkit-scrollbar { width: 4px; }
          .sidebar-scroll::-webkit-scrollbar-thumb { background: ${theme.sidebarBorder}; border-radius: 2px; }
          @keyframes fadeIn { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }
        `}
      </style>
    </div>
  );
}
