import React from 'react';
import { Bell, User, LogOut, Sun, Moon } from "lucide-react";
import { Button } from "@/components/ui/button";
import { Avatar, AvatarFallback } from "@/components/ui/avatar";
import { router } from "@inertiajs/react";
import { route } from "ziggy-js";
import { useTenancyTheme } from '../../hooks/useTenancyTheme';

export function TenancyHeader({ collapsed, setCollapsed }: { collapsed: boolean; setCollapsed: (val: boolean) => void }) {
  const { theme, isDark, toggleTheme } = useTenancyTheme();

  const toggleSidebar = () => {
    setCollapsed(!collapsed);
  };

  const logout = () => {
    router.get(route('logout'));
  };

  return (
    <header 
      style={{ background: theme.bg, color: theme.text, borderColor: theme.border }}
      className="sticky top-0 z-40 flex h-16 items-center justify-between gap-4 border-b px-6"
    >
      <div className="flex items-center gap-4">
        <Button
          variant="ghost"
          size="icon"
          className="h-9 w-9"
          style={{ color: theme.text }}
          onClick={toggleSidebar}
        >
          ☰
          <span className="sr-only">Toggle Sidebar</span>
        </Button>
      </div>

      <div className="flex items-center gap-2">
        <Button 
          variant="ghost" 
          size="icon" 
          className="h-9 w-9"
          style={{ color: theme.text }}
          onClick={toggleTheme}
        >
          {isDark ? <Sun className="h-4 w-4" /> : <Moon className="h-4 w-4" />}
        </Button>

        <Button 
          variant="ghost" 
          size="icon" 
          className="h-9 w-9"
          style={{ color: theme.text }}
        >
          <Bell className="h-4 w-4" />
        </Button>

        <Avatar className="h-8 w-8">
          <AvatarFallback style={{ background: theme.accent, color: '#fff' }}>T</AvatarFallback>
        </Avatar>

        <Button 
          variant="ghost" 
          size="icon" 
          style={{ color: theme.text }}
          onClick={logout}
        >
          <LogOut className="h-4 w-4" />
        </Button>
      </div>
    </header>
  );
}
