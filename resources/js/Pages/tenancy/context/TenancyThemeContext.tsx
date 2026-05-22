import React, { createContext, useContext, useState, ReactNode } from 'react';

export interface TenancyTheme {
  bg: string;
  bgSecondary: string;
  sidebarBg: string;
  sidebarFg: string;
  sidebarActive: string;
  sidebarActiveFg: string;
  sidebarBorder: string;
  sidebarHover: string;
  sidebarMutedFg: string;
  text: string;
  textMuted: string;
  accent: string;
  border: string;
}

const sapphireNight: TenancyTheme = {
  bg: '#0f172a', // slate-900
  bgSecondary: '#1e293b', // slate-800
  sidebarBg: '#020617', // slate-950
  sidebarFg: '#f8fafc', // slate-50
  sidebarActive: '#2563eb', // blue-600
  sidebarActiveFg: '#ffffff',
  sidebarBorder: '#1e293b',
  sidebarHover: '#1e293b',
  sidebarMutedFg: '#94a3b8', // slate-400
  text: '#f8fafc',
  textMuted: '#94a3b8',
  accent: '#3b82f6', // blue-500
  border: '#334155', // slate-700
};

const emeraldLight: TenancyTheme = {
  bg: '#f8fafc',
  bgSecondary: '#ffffff',
  sidebarBg: '#111827', // dark sidebar even in light mode for contrast
  sidebarFg: '#f9fafb',
  sidebarActive: '#10b981', // emerald-500
  sidebarActiveFg: '#ffffff',
  sidebarBorder: '#1f2937',
  sidebarHover: '#374151',
  sidebarMutedFg: '#9ca3af',
  text: '#111827',
  textMuted: '#6b7280',
  accent: '#10b981',
  border: '#e5e7eb',
};

interface TenancyThemeContextType {
  theme: TenancyTheme;
  isDark: boolean;
  toggleTheme: () => void;
}

export const TenancyThemeContext = createContext<TenancyThemeContextType | undefined>(undefined);

export function TenancyThemeProvider({ children }: { children: ReactNode }) {
  const [isDark, setIsDark] = useState(true);
  const theme = isDark ? sapphireNight : emeraldLight;

  const toggleTheme = () => setIsDark(!isDark);

  return (
    <TenancyThemeContext.Provider value={{ theme, isDark, toggleTheme }}>
      {children}
    </TenancyThemeContext.Provider>
  );
}
