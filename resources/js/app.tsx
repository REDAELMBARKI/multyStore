import '../css/app.css';
import './bootstrap.js';
import '../../i18n';
import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createRoot } from 'react-dom/client';
import StoreConfigProvider from '@/contextProvoders/StoreConfigProvider';
import { ToastProvider } from '@/contextProvoders/ToastProvider';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
        const pages = import.meta.glob('./Pages/**/*.{tsx,jsx}');
        const nameLower = name.toLowerCase().replace(/\\/g, '/');
        const key = Object.keys(pages).find(k => k.toLowerCase().replace('./pages/', '').replace(/\.(tsx|jsx)$/, '') === nameLower);
        
        if (!key) {
            throw new Error(`Page not found: ${name}`);
        }
        
        return resolvePageComponent(key, pages);
    },
    setup({ el, App, props }) {
        const root = createRoot(el);

        root.render(   
            <StoreConfigProvider>
                <ToastProvider>
                    <App {...props} />
                </ToastProvider>
            </StoreConfigProvider>
         );
    }
   ,
    progress: {
        color: '#006affff',
    },
});
