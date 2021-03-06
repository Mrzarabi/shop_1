<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Images\ImageRequest;
use App\Http\Requests\V1\MultiDeleteProduct\MultiDeleteProductRequest;
use App\Http\Requests\V1\Product\ProductRequest;
use App\Http\Resources\Api\V1\Product\Product as ProductResource;
use App\Http\Resources\Api\V1\Product\ProductCollection;
use App\Models\Category;
use App\Models\Product;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::latest()->paginate(9);
        return new ProductCollection($products);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        if( auth()->user()->hasRole('100e82ba-e1c0-4153-8633-e1bd228f7399') ) {
            $u_price = isset($request->u_price) ? number_format($request->u_price) : $request->u_price;
            $c_price = isset($request->c_price) ? number_format($request->c_price) : $request->c_price;

            $product = auth()->user()->products()->create( array_merge( $request->all(), [
                    'u_price' => $u_price,
                    'c_price' => $c_price
                ]) 
            );
            $post = new ProductResource($product);

            return response([
                'data' => $post->id,
                'message' => 'محصول با موفقیت ثبت گردید', 
                'status' => 'success'
            ]);
        }
    }

    /**
     * Store a newly created images in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function upload(ImageRequest $request, Product $product)
    {
        if( auth()->user()->hasRole('100e82ba-e1c0-4153-8633-e1bd228f7399') ) {
            if($request->images )
            {
                $images = $request->images;
                foreach ($images as $image) {
                    $file = $this->upload_image($image);
                    $product->images()->create(['image' => $file]);
                }

                return response([
                    'data' => 'تصاویر با موفقیت آپلود شدند',
                    'status' => 'success'
                ]);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, Product $product)
    {
        if( auth()->user()->hasRole('100e82ba-e1c0-4153-8633-e1bd228f7399') ) {

            $u_price = isset($request->u_price) ? number_format($request->u_price) : $product->u_price;
            $c_price = isset($request->c_price) ? number_format($request->c_price) : $product   ->c_price;

            $product->update( array_merge( $request->all(), [
                'u_price' => $u_price,
                'c_price' => $c_price
            ]));
            $post = new ProductResource($product);

            return response([
                'data' => $post->id,
                'images' => $post->images()->get(),
                'message' => 'محصول مورد نظر با موفقیت به روز رسانی شد',
                'status' => 'success'
            ]);
        }
    }

    /**
     * Store a newly created images in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateUpload(ImageRequest $request, Product $product)
    {
        if( auth()->user()->hasRole('100e82ba-e1c0-4153-8633-e1bd228f7399') ) {
            if($request->images)
            {
                $past_images = $product->images()->get();
                foreach ($past_images as $image) {
                    $image->delete();
                }

                $images = $request->images;
                foreach ($images as $image) {
                    $file = $this->upload_image($image);
                    $product->images()->create(['image' => $file]);
                }

                return response([
                    'data' => 'تصاویر با موفقیت به روز رسانی شدند',
                    'status' => 'success'
                ]);
            } 

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if( auth()->user()->hasRole('100e82ba-e1c0-4153-8633-e1bd228f7399') ) {
            $product->delete();
            return [
                'data' => 'محصول با موفقیت حذف شد',
                'status' => 'success'
            ];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function multiDelete(MultiDeleteProductRequest $request)
    {
        if( auth()->user()->hasRole('100e82ba-e1c0-4153-8633-e1bd228f7399') ) {
            $ids = explode(',', $request->ids);
            foreach ($ids as $id) {
                DB::table('products')->where('id', $id)->delete();
            }

            return response([
                'data' => 'محصولات با موفقیت حذف شدند',
                'status' => 'success'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function search($query = null)
    {
        $resualt = Product::search( $query )->latest()->paginate(10);
        return new ProductCollection($resualt);
    }
}
