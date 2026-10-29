<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FurnitureSeeder extends Seeder
{
    public function run(): void
    {
        Product::whereIn('slug', ['shadow-essential-hoodie','midnight-cargo-pant','void-graphic-tee','concrete-zip-hoodie','noir-oversized-tee','urban-shell-jacket','stealth-jogger','archive-logo-hoodie'])->update(['is_active'=>false]);
        Category::whereIn('slug',['hoodies','tees','pants'])->update(['is_active'=>false,'is_featured'=>false]);

        $brand=Brand::updateOrCreate(['slug'=>'arden-and-co'],['name'=>'Arden & Co.','is_active'=>true]);
        $rooms=collect([
            ['living-room','Living Room','https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=1000&q=85'],
            ['dining','Dining','https://images.unsplash.com/photo-1617806118233-18e1de247200?w=1000&q=85'],
            ['bedroom','Bedroom','https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?w=1000&q=85'],
            ['lighting','Lighting','https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=1000&q=85'],
        ])->mapWithKeys(function($room,$i){$cat=Category::updateOrCreate(['slug'=>$room[0]],['name'=>$room[1],'image'=>$room[2],'is_featured'=>true,'is_active'=>true,'sort_order'=>$i+1]);return [$room[0]=>$cat];});

        $products=[
            ['Haven Modular Sofa','living-room',2490,'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=1200&q=88',['is_best_seller'=>true,'is_trending'=>true]],
            ['Elara Lounge Chair','living-room',890,'https://images.unsplash.com/photo-1567538096630-e0c55bd6374c?w=1200&q=88',['is_new_arrival'=>true]],
            ['Sora Oak Coffee Table','living-room',720,'https://images.unsplash.com/photo-1532372320572-cda25653a694?w=1200&q=88',['is_best_seller'=>true]],
            ['Milo Bouclé Armchair','living-room',980,'https://images.unsplash.com/photo-1549497538-303791108f95?w=1200&q=88',['is_trending'=>true]],
            ['Tide Solid Oak Dining Table','dining',1890,'https://images.unsplash.com/photo-1617806118233-18e1de247200?w=1200&q=88',['is_new_arrival'=>true,'is_featured'=>true]],
            ['Cove Dining Chair','dining',340,'https://images.unsplash.com/photo-1503602642458-232111445657?w=1200&q=88',['is_best_seller'=>true]],
            ['Forma Bed Frame','bedroom',1650,'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?w=1200&q=88',['is_new_arrival'=>true]],
            ['Aster Travertine Lamp','lighting',420,'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=1200&q=88',['is_flash_sale'=>true,'flash_sale_price'=>350]],
        ];

        foreach($products as $i=>$item){
            [$name,$room,$price,$image,$flags]=$item;$slug=Str::slug($name);
            $product=Product::updateOrCreate(['slug'=>$slug],array_merge(['name'=>$name,'sku'=>'ARD-'.str_pad((string)($i+1),3,'0',STR_PAD_LEFT),'brand_id'=>$brand->id,'category_id'=>$rooms[$room]->id,'short_description'=>'A refined, enduring piece designed for relaxed Australian living.','description'=>'Made with honest materials and considered proportions, this piece brings warmth, comfort and quiet character to everyday spaces.','price'=>$price,'compare_at_price'=>isset($flags['is_flash_sale'])?$price:null,'stock'=>20+$i,'is_active'=>true,'available_sizes'=>null,'available_colors'=>['Natural','Oat','Walnut'],'shipping_info'=>'White-glove delivery available. Dispatches in 3–7 business days with Australia-wide tracking.','specifications'=>'Responsibly sourced materials. Five-year structural warranty. Assembly service available.','is_trending'=>false,'is_new_arrival'=>false,'is_best_seller'=>false,'is_flash_sale'=>false],$flags));
            ProductImage::where('product_id',$product->id)->delete();
            ProductImage::create(['product_id'=>$product->id,'path'=>$image,'alt'=>$name,'is_primary'=>true,'sort_order'=>0]);
            ProductVariant::where('product_id',$product->id)->delete();
            foreach(['Natural'=>'#d6c5aa','Oat'=>'#e6dfd1','Walnut'=>'#6d4c36'] as $colour=>$hex){ProductVariant::create(['product_id'=>$product->id,'sku'=>$product->sku.'-'.strtoupper(substr($colour,0,3)),'color'=>$colour,'color_hex'=>$hex,'stock'=>12]);}
        }
    }
}
