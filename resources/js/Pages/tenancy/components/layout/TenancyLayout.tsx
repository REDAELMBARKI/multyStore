import React, { ReactNode, useState } from "react";
import { TenancySidebar } from "./Sidebar";
import { TenancyHeader } from "./Header";
import { useTenancyTheme } from "../../hooks/useTenancyTheme";
import { TenancyThemeProvider } from "../../context/TenancyThemeContext";

export function TenancyLayout({ children }: { children: ReactNode }) {
  return (
    <TenancyThemeProvider>
      <TenancyLayoutContent>{children}</TenancyLayoutContent>
    </TenancyThemeProvider>
  );
}

function TenancyLayoutContent({ children }: { children: ReactNode }) {
  const [collapsed, setCollapsed] = useState(false);
  const { theme } = useTenancyTheme();

  return (
    <div 
      className="flex h-dvh overflow-hidden"
      style={{ backgroundColor: theme.bg }}
    >
      <div 
        style={{
          width: collapsed ? '80px' : '256px',
          transition: 'width 0.3s ease',
        }}
        className="flex-shrink-0 h-full"
      >
        <TenancySidebar collapsed={collapsed} setCollapsed={setCollapsed} />
      </div>

      <div className="flex-1 flex flex-col overflow-hidden min-w-0">
        <TenancyHeader collapsed={collapsed} setCollapsed={setCollapsed} />
        <main 
          className="flex-1 overflow-auto p-6"
          style={{ color: theme.text, background: theme.bgSecondary }}
        >
          {children}
        </main>
      </div>
    </div>
  );
}
