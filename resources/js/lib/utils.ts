import { clsx, type ClassValue } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
  return twMerge(clsx(inputs));
}

export function getProductPlaceholder(id: string | number) {
  return `https://picsum.photos/seed/${id}/600/800`;
}

export function getProductImage(product: any) {
  if (!product) return getProductPlaceholder('default');

  // Helper to check if a string is a valid external URL or already a correct storage path
  const isValidUrl = (url: string | null | undefined) => {
    if (!url) return false;
    // If it's a full URL (http/https), it's valid
    if (url.startsWith('http')) return true;
    // If it's a relative path that looks like it's already correctly formatted for storage
    if (url.startsWith('/storage/') && !url.includes('/storage/products/')) return true;
    return false;
  };

  // 1. Try product.image
  if (isValidUrl(product.image)) {
    return product.image;
  }

  // 2. Try product.thumbnail.url
  if (product.thumbnail?.url && isValidUrl(product.thumbnail.url)) {
    return product.thumbnail.url;
  }

  // 3. Fallback to placeholder using product ID or name
  return getProductPlaceholder(product.id || product.name || 'default');
}
