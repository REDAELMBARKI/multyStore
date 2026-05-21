import React, { useState } from 'react';
import { Filter, Search, Star, Heart, ShoppingCart, Eye, ChevronDown } from 'lucide-react';
import Layout from '../Layouts/Layout';
import { useStoreConfigCtx } from '@/contextHooks/useStoreConfigCtx';

const ShopPage = () => {
  const { state: { currentTheme: theme } } = useStoreConfigCtx();
  const [activeFilter, setActiveFilter] = useState('*');
  const [showFilters, setShowFilters] = useState(false);
  const [showSearch, setShowSearch] = useState(false);
  const [sortBy, setSortBy] = useState('default');

  const products = [
    {
      id: 1,
      name: 'Esprit Ruffle Shirt',
      price: 16.64,
      image: 'https://images.pexels.com/photos/996329/pexels-photo-996329.jpeg?auto=compress&cs=tinysrgb&w=300',
      category: 'women',
      rating: 4.5,
      reviews: 12
    },
    {
      id: 2,
      name: 'Herschel Supply',
      price: 35.31,
      image: 'https://images.pexels.com/photos/2529148/pexels-photo-2529148.jpeg?auto=compress&cs=tinysrgb&w=300',
      category: 'women',
      rating: 4.2,
      reviews: 8
    },
    {
      id: 3,
      name: 'Only Check Trouser',
      price: 25.50,
      image: 'https://images.pexels.com/photos/1183266/pexels-photo-1183266.jpeg?auto=compress&cs=tinysrgb&w=300',
      category: 'men',
      rating: 4.8,
      reviews: 15
    },
    {
      id: 4,
      name: 'Classic Trench Coat',
      price: 75.00,
      image: 'https://images.pexels.com/photos/1926769/pexels-photo-1926769.jpeg?auto=compress&cs=tinysrgb&w=300',
      category: 'women',
      rating: 4.6,
      reviews: 22
    },
    {
      id: 5,
      name: 'Front Pocket Jumper',
      price: 34.75,
      image: 'https://images.pexels.com/photos/1040945/pexels-photo-1040945.jpeg?auto=compress&cs=tinysrgb&w=300',
      category: 'women',
      rating: 4.3,
      reviews: 9
    },
    {
      id: 6,
      name: 'Vintage Inspired Classic',
      price: 93.20,
      image: 'https://images.pexels.com/photos/190819/pexels-photo-190819.jpeg?auto=compress&cs=tinysrgb&w=300',
      category: 'watches',
      rating: 4.7,
      reviews: 18
    },
    {
      id: 7,
      name: 'Converse All Star Hi',
      price: 75.00,
      image: 'https://images.pexels.com/photos/2529148/pexels-photo-2529148.jpeg?auto=compress&cs=tinysrgb&w=300',
      category: 'shoes',
      rating: 4.5,
      reviews: 25
    },
    {
      id: 8,
      name: 'Mini Silver Mesh Watch',
      price: 86.85,
      image: 'https://images.pexels.com/photos/190819/pexels-photo-190819.jpeg?auto=compress&cs=tinysrgb&w=300',
      category: 'watches',
      rating: 4.4,
      reviews: 14
    }
  ];

  const filters = [
    { key: '*', label: 'All Products' },
    { key: 'women', label: 'Women' },
    { key: 'men', label: 'Men' },
    { key: 'bag', label: 'Bag' },
    { key: 'shoes', label: 'Shoes' },
    { key: 'watches', label: 'Watches' }
  ];

  const sortOptions = [
    { value: 'default', label: 'Default' },
    { value: 'popularity', label: 'Popularity' },
    { value: 'rating', label: 'Average rating' },
    { value: 'newness', label: 'Newness' },
    { value: 'price-low', label: 'Price: Low to High' },
    { value: 'price-high', label: 'Price: High to Low' }
  ];

  const priceRanges = [
    { label: 'All', active: true },
    { label: '$0.00 - $50.00' },
    { label: '$50.00 - $100.00' },
    { label: '$100.00 - $150.00' },
    { label: '$150.00 - $200.00' },
    { label: '$200.00+' }
  ];

  const colors = [
    { name: 'Black', color: '#222', active: false },
    { name: 'Blue', color: '#4272d7', active: true },
    { name: 'Grey', color: '#b3b3b3', active: false },
    { name: 'Green', color: '#00ad5f', active: false },
    { name: 'Red', color: '#fa4251', active: false },
    { name: 'White', color: '#fff', active: false }
  ];

  const tags = ['Fashion', 'Lifestyle', 'Denim', 'Streetstyle', 'Crafts'];

  const filteredProducts = activeFilter === '*' 
    ? products 
    : products.filter(product => product.category === activeFilter);

  return (
    <Layout currentPage="shop">
      {/* Products Section */}
      <section className="py-12">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          {/* Filter Bar */}
          <div className="flex flex-wrap items-center justify-between mb-8 gap-4">
            {/* Category Filters */}
            <div className="flex flex-wrap gap-4">
              {filters.map((filter) => (
                <button
                  key={filter.key}
                  onClick={() => setActiveFilter(filter.key)}
                  style={{ 
                    backgroundColor: activeFilter === filter.key ? theme.primary : 'transparent',
                    color: activeFilter === filter.key ? theme.textInverse : theme.textSecondary,
                    borderColor: activeFilter === filter.key ? theme.primary : theme.border
                  }}
                  className="px-6 py-2 rounded-full border transition-all"
                >
                  {filter.label}
                </button>
              ))}
            </div>

            {/* Filter & Search Controls */}
            <div className="flex items-center space-x-4">
              <button
                onClick={() => setShowFilters(!showFilters)}
                style={{ borderColor: theme.border, color: theme.textSecondary }}
                className="flex items-center space-x-2 px-4 py-2 border rounded-md hover:opacity-80 transition-opacity"
              >
                <Filter className="w-4 h-4" />
                <span>Filter</span>
              </button>
              <button
                onClick={() => setShowSearch(!showSearch)}
                style={{ borderColor: theme.border, color: theme.textSecondary }}
                className="flex items-center space-x-2 px-4 py-2 border rounded-md hover:opacity-80 transition-opacity"
              >
                <Search className="w-4 h-4" />
                <span>Search</span>
              </button>
            </div>
          </div>

          {/* Search Bar */}
          {showSearch && (
            <div style={{ backgroundColor: theme.bgSecondary }} className="mb-8 p-4 rounded-lg">
              <div style={{ borderColor: theme.border, backgroundColor: theme.bg }} className="flex items-center border rounded-md">
                <Search style={{ color: theme.textMuted }} className="w-5 h-5 ml-3" />
                <input
                  type="text"
                  placeholder="Search products..."
                  style={{ backgroundColor: 'transparent', color: theme.text }}
                  className="flex-1 px-4 py-3 outline-none"
                />
              </div>
            </div>
          )}

          {/* Advanced Filters */}
          {showFilters && (
            <div className="mb-8 p-6 bg-gray-50 rounded-lg">
              <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
                {/* Sort By */}
                <div>
                  <h3 style={{ color: theme.text }} className="font-semibold mb-4">Sort By</h3>
                  <ul className="space-y-2">
                    {sortOptions.map((option) => (
                      <li key={option.value}>
                        <button
                          onClick={() => setSortBy(option.value)}
                          style={{ 
                            color: sortBy === option.value ? theme.primary : theme.textSecondary,
                            fontWeight: sortBy === option.value ? 600 : 400
                          }}
                          className="text-sm transition-colors hover:opacity-80"
                        >
                          {option.label}
                        </button>
                      </li>
                    ))}
                  </ul>
                </div>

                {/* Price */}
                <div>
                  <h3 style={{ color: theme.text }} className="font-semibold mb-4">Price</h3>
                  <ul className="space-y-2">
                    {priceRanges.map((range, index) => (
                      <li key={index}>
                        <button
                          style={{ 
                            color: range.active ? theme.primary : theme.textSecondary,
                            fontWeight: range.active ? 600 : 400
                          }}
                          className="text-sm transition-colors hover:opacity-80"
                        >
                          {range.label}
                        </button>
                      </li>
                    ))}
                  </ul>
                </div>

                {/* Color */}
                <div>
                  <h3 style={{ color: theme.text }} className="font-semibold mb-4">Color</h3>
                  <ul className="space-y-2">
                    {colors.map((color, index) => (
                      <li key={index} className="flex items-center space-x-3">
                        <div
                          className="w-4 h-4 rounded-full border border-gray-300"
                          style={{ backgroundColor: color.color }}
                        />
                        <button
                          style={{ 
                            color: color.active ? theme.primary : theme.textSecondary,
                            fontWeight: color.active ? 600 : 400
                          }}
                          className="text-sm transition-colors hover:opacity-80"
                        >
                          {color.name}
                        </button>
                      </li>
                    ))}
                  </ul>
                </div>

                {/* Tags */}
                <div>
                  <h3 style={{ color: theme.text }} className="font-semibold mb-4">Tags</h3>
                  <div className="flex flex-wrap gap-2">
                    {tags.map((tag) => (
                      <button
                        key={tag}
                        style={{ borderColor: theme.border, color: theme.textSecondary }}
                        className="px-3 py-1 text-sm border rounded-full hover:opacity-80 transition-all"
                      >
                        {tag}
                      </button>
                    ))}
                  </div>
                </div>
              </div>
            </div>
          )}

          {/* Products Grid */}
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            {filteredProducts.map((product) => (
              <div key={product.id} className="group">
                <div className="relative overflow-hidden rounded-lg bg-gray-100 mb-4">
                  <img
                    src={product.image}
                    alt={product.name}
                    className="w-full h-80 object-cover group-hover:scale-110 transition-transform duration-500"
                  />
                  
                  {/* Quick View Button */}
                  <button className="absolute bottom-4 left-1/2 transform -translate-x-1/2 bg-white text-gray-900 px-6 py-2 rounded-md opacity-0 group-hover:opacity-100 transition-all duration-300 hover:bg-gray-100">
                    Quick View
                  </button>

                  {/* Wishlist Button */}
                  <button className="absolute top-4 right-4 bg-white bg-opacity-90 text-gray-600 p-2 rounded-full hover:bg-white hover:text-red-500 transition-colors">
                    <Heart className="w-4 h-4" />
                  </button>
                </div>

                <div className="flex items-start justify-between">
                  <div className="flex-1">
                    <h3 
                      style={{ color: theme.text }}
                      className="text-lg font-medium transition-colors mb-2 hover:opacity-80"
                    >
                      {product.name}
                    </h3>
                    
                    {/* Rating */}
                    <div className="flex items-center space-x-2 mb-2">
                      <div className="flex items-center">
                        {[...Array(5)].map((_, i) => (
                          <Star
                            key={i}
                            className={`w-4 h-4 ${
                              i < Math.floor(product.rating)
                                ? 'fill-current'
                                : ''
                            }`}
                            style={{ color: i < Math.floor(product.rating) ? theme.starColor || '#fbbf24' : theme.border }}
                          />
                        ))}
                      </div>
                      <span style={{ color: theme.textMuted }} className="text-sm">({product.reviews})</span>
                    </div>

                    <p style={{ color: theme.text }} className="text-xl font-bold">${product.price}</p>
                  </div>
                </div>
              </div>
            ))}
          </div>

          {/* Load More Button */}
          <div className="text-center mt-12">
            <button 
              style={{ backgroundColor: theme.bgSecondary, color: theme.text }}
              className="px-8 py-3 rounded-md hover:opacity-80 transition-opacity"
            >
              Load More
            </button>
          </div>
        </div>
      </section>
    </Layout>
  );
};

export default ShopPage;