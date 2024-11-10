<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index() {
        return Product::all();
    }

    public function store(Request $request) {
        $rules=[
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer'
        ];

        $response = $this->validateWithJSON($request->all(), $rules);
        if ($response === true) {
            try {
                if ($this->isAdmin() === true) {
                 $product = Product::create($request->all());
                 return $this->responseWithSuccess('Product added successfully');
                } else {
                    return $this->responseWithError('Invalid permission!Only admin can add products');
                }
            } catch (\Exception $exception) {
                return $this->responseWithError($exception->getMessage());
            }
        }

        return $this->responseWithError('Validation failed', $response);

    }


    public function show($id) {
        $products= Product::findOrFail($id);
        return $this->responseWithSuccess('Product',$products);
    }


    public function topFive() {
        $topProducts = Product::orderBy('sales_count', 'desc')->limit(5)->get();
        return $this->responseWithSuccess('Top sold 5 products',$topProducts);
    }


    public function update(Request $request, $id) {
        $rules=[
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer'
        ];

        $response = $this->validateWithJSON($request->all(), $rules);
        if ($response === true) {
            try {
                if ($this->isAdmin() === true || $this->isEditor() === true) {
                    $product = Product::findOrFail($id);
                    $product->update($request->all());
                    return $this->responseWithSuccess('Product updated successfully');
                } else {
                    return $this->responseWithError('Invalid permission!Only admin/editor can update products');
                }
            } catch (\Exception $exception) {
                return $this->responseWithError($exception->getMessage());
            }
        }

        return $this->responseWithError('Validation failed', $response);

    }



    public function destroy($id) {

            try {
                if ($this->isAdmin() === true) {

                    $product = DB::table('products')->where('id',$id);

                    if ($product->exists()) {

                        $comment_delete =  $product->delete();
                        return $this->responseWithSuccess('Product deleted!');

                    } else {
                        return $this->responseWithError('Invalid product');
                    }


                } else {
                    return $this->responseWithError('Invalid permission!Only admin can delete products');
                }
            } catch (\Exception $exception) {
                return $this->responseWithError($exception->getMessage());
            }

    }


}
