import { useStoreConfigCtx } from "@/contextHooks/useStoreConfigCtx";
import ProductImageSlideshow from "@/components/partials/ProductImageSlideshow";
import { ProductClient } from "@/types/clientSideTypes";
import { Heart, Star, Eye, ShoppingCart } from "lucide-react";

// Card 1: Classic Grid Card
const Card1 = ({ product, onAddToCart, onViewDetails }:{product : any, onAddToCart: any, onViewDetails: any}) => {
  const {state :{currentCardConf :{isRounded , showPrice , showRating , showBorder}} } = useStoreConfigCtx()
  return (
    <div 
      className={`bg-white rounded-lg overflow-hidden cursor-pointer transition-all hover:shadow-lg ${showBorder ? 'border-2 border-gray-200' : 'shadow-md'}`}
      onClick={onViewDetails}
    >
      <div className="relative group">
        <ProductImageSlideshow 
          images={product.images || [product.image]} 
          alt={product.name} 
          className="w-full h-48" 
          productId={product.id}
        />
        <div className="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
          <button 
            className="p-2 bg-white rounded-full shadow-md hover:bg-orange-500 hover:text-white transition-colors"
            onClick={(e) => { e.stopPropagation(); onViewDetails(e); }}
          >
            <Eye className="w-5 h-5" />
          </button>
          <button 
            className="p-2 bg-white rounded-full shadow-md hover:bg-orange-500 hover:text-white transition-colors"
            onClick={onAddToCart}
          >
            <ShoppingCart className="w-5 h-5" />
          </button>
        </div>
        <button 
          className="absolute top-2 right-2 bg-white rounded-full p-2 shadow-md hover:bg-red-50"
          onClick={(e) => { e.stopPropagation(); /* Handle wishlist */ }}
        >
          <Heart className="w-5 h-5 text-red-500" />
        </button>
      </div>
      <div className="p-4">
        <h3 className="font-semibold text-lg mb-2">{product?.name}</h3>
        {showRating && (
          <div className="flex items-center gap-1 mb-2">
            <Star className="w-4 h-4 fill-yellow-400 text-yellow-400" />
            <span className="text-sm text-gray-600">{product?.rating}</span>
          </div>
        )}
        {showPrice && (
          <p className="text-xl font-bold text-gray-900 mb-3">${product?.price}</p>
        )}
        <button 
          className="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-colors"
          onClick={onAddToCart}
        >
          Add to Cart
        </button>
      </div>
    </div>
  );
};
export default Card1;