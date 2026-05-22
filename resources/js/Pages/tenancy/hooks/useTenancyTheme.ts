import { useContext } from 'react';
import { TenancyThemeContext } from '../context/TenancyThemeContext';

export function useTenancyTheme() {
  const context = useContext(TenancyThemeContext);
  if (context === undefined) {
    throw new Error('useTenancyTheme must be used within a TenancyThemeProvider');
  }
  return context;
}
