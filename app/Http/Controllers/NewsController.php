<?php

namespace App\Http\Controllers;

use App\News;
use Validator;
use Illuminate\Http\Request;

class NewsController extends Controller {
    public function createNews(Request $request) {
        $validator = Validator::make($request->all(), [
                    'heading' => 'required',
                    'description' => 'required',
                    'image' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => 'The given data was invalid.',
                        'data' => $validator->errors()
                            ], 422);
        }
        try {
            $news = new News([
                'heading' => $request->heading,
                'description' => $request->description,
                'image' => $request->image,
            ]);
            if ($news->save()) {
                return response()->json([
                                'status' => true,
                                'message' => 'News created successfully.',
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
    }
    
    public function newsList() {
        return response()->json([
                    'status' => true,
                    'message' => 'News List',
                    'data' => News::get()
                        ], 200);
    }
    
    public function singleNews(Request $request) {
        $news = News::where('id', $request->news_id)->first();
        if($news->count() > 0) {
            return response()->json([
                    'status' => true,
                    'message' => 'Single news',
                    'data' => $news
                        ], 200);
        }
        return response()->json([
                    'status' => true,
                    'message' => 'No news',
                    'data' => []
                        ], 200);
    }
    
    public function updateNews(Request $request) {
        $validator = Validator::make($request->all(), [
                    'news_id' => 'required',
                    'heading' => 'required',
                    'description' => 'required',
                    'image' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => 'The given data was invalid.',
                        'data' => $validator->errors()
                            ], 422);
        }
        
        try {
            News::where('id', $request->news_id)->update(['description' => $request->description, 'heading' => $request->heading, 'image'=> $request->image]);
            return response()->json([
                    'status' => true,
                    'message' => 'News updated sucessfully.',
                    'data' => []
                        ], 200);
        } catch (Exception $ex) {
            Log::error(json_encode($e->getMessage()));
            return response()->json([
                        'status' => false,
                        'message' => $e->getMessage(),
                        'data' => []
                            ], 500);
        }
    }
    
    public function deleteNews(Request $request) {
        try {
            News::whereId($request->news_id)->delete();
            return response()->json([
                        'status' => true,
                        'message' => 'News deleted successfully.',
                        'data' => []
                            ], 200);
        } catch (Exception $ex) {
            Log::error(json_encode($e->getMessage()));
            return response()->json([
                        'status' => false,
                        'message' => $e->getMessage(),
                        'data' => []
                            ], 500);
        }
    }
}
