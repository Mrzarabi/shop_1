<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Product\ProductRequest;
use App\Http\Resources\Api\V1\Product\Product as ProductResource;
use App\Http\Resources\Api\V1\Product\ProductCollection;
use App\Models\Category;
use App\Models\Product;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::paginate(9);
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
        if($request->hasFile('images')) {

            $images = Collection::wrap( $request->file('images') );

            $images->each( function($image) use($request) {
                
                auth()->user()->products()->create( array_merge( $request->all(),
                    [ 'images' => $images->each( function($image) {
                            
                        } 
                    )]
                )); 
            });

        } else {
            auth()->user()->products()->create( array_merge( $request->all() ));
        }


        return response([
            'data' => 'محصول با موفقیت ثبت گردید',
            'status' => 'success'
        ]);
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
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return [
            'data' => 'محصول با موفقیت حذف شد',
            'status' => 'success'
        ];
    }
}
