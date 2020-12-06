<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
//use Illuminate\Validation\Rule;
//use Illuminate\Support\Facades\DB;
//use App\TimeSlots;
//use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function createProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
                    'name' => 'required|string',
                    'price' => 'required|numeric',
                    'description' => 'required|string',
                    'image' => 'required|image',
                    'quantity' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => $validator->errors(),
                        'data' => []
                            ], 422);
        }
        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
            try {
                $product = new Product([
                    'name' => $request->name,
                    'image' => $request->image,
                    'description' => $request->description,
                    'price' => $request->price,
                    'quantity' => $request->quantity,
                ]);
                if ($product->save()) {
                    return response()->json([
                                'status' => true,
                                'message' => 'Product created successfully.',
                                'data' => []
                                    ], 200);
                }
            } catch (\Exception $e) {
                Log::error(json_encode($e->getMessage()));
                return response()->json([
                            'status' => false,
                            'message' => $e->getMessage(),
                            'data' => []
                                ], 500);
            }
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
        }
    }

    public function editProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
                    'product_id' => 'required',
                    'name' => 'required|string',
                    'price' => 'required|numeric',
                    'description' => 'required|string',
                    'image' => 'required|image',
                    'quantity' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => $validator->errors(),
                        'data' => []
                            ], 422);
        }

        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
            try {
                Product::whereId($request->product_id)->update([
                    'name' => $request->name,
                    'image' => $request->image,
                    'description' => $request->description,
                    'price' => $request->price,
                    'quantity' => $request->quantity,
                ]);
                return response()->json([
                            'status' => true,
                            'message' => 'Product edit successfully.',
                            'data' => []
                                ], 200);
            } catch (\Exception $e) {
                Log::error(json_encode($e->getMessage()));
                return response()->json([
                            'status' => false,
                            'message' => $e->getMessage(),
                            'data' => []
                                ], 500);
            }
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
        }
    }

    public function listProducts(Request $request) {
        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
            $products = Product::get();

            return response()->json([
                        'status' => true,
                        'message' => 'Product Listing.',
                        'data' => $products
                            ], 200);
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
        }
    }

    public function listProductsMobile(Request $request) {
        $validator = Validator::make($request->all(), [
                    'offset' => 'required',
                    'take' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => 'The given data was invalid.',
                        'data' => $validator->errors()
                            ], 422);
        }
        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
            return response()->json([
                        'status' => true,
                        'message' => 'Product Listing.',
                        'data' => Product::skip($request->offset)->take($request->take)->get()
                            ], 200);
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
        }
    }

    public function getProduct(Request $request) {
        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
            $fetchProduct = Product::whereId($request->product_id)->first();
            if ($fetchProduct != null) {
                $status = true;
                $message = "Product Found.";
                $statusCode = 200;
            } else {
                $status = false;
                $message = "Product not found.";
                $statusCode = 400;
            }
            return response()->json([
                        'status' => $status,
                        'message' => $message,
                        'data' => $fetchProduct
                            ], $statusCode);
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
        }
    }

    public function deleteProduct(Request $request) {
        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
            if (Product::whereId($request->product_id)->delete()) {
                $status = true;
                $message = "Product deleted successfully.";
                $statusCode = 200;
            } else {
                $status = false;
                $message = "Product not found.";
                $statusCode = 400;
            }
            return response()->json([
                        'status' => $status,
                        'message' => $message,
                        'data' => []
                            ], $statusCode);
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
        }
    }

//    public function getTimeSlots(Request $request) {
//        $getTime = TimeSlots::whereSlotType($request->slot_type)->get();
//        if ($getTime->count()) {
//            return response()->json([
//                        'status' => true,
//                        'message' => 'success',
//                        'data' => $getTime
//                            ], 200);
//        } else {
//            return response()->json([
//                        'status' => false,
//                        'message' => 'no time slots found',
//                        'data' => $getTime
//                            ], 200);
//        }
//    }
}
