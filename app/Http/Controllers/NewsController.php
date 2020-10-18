<?php

namespace App\Http\Controllers;

use App\News;
use App\Faq;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        } catch (Exception $e) {
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
        } catch (Exception $e) {
            Log::error(json_encode($e->getMessage()));
            return response()->json([
                        'status' => false,
                        'message' => $e->getMessage(),
                        'data' => []
                            ], 500);
        }
    }
    
    public function faqList() {
        return response()->json([
                    'status' => true,
                    'message' => 'Faq List',
                    'data' => Faq::get()
                        ], 200);
    }
    
    public function createFaq(Request $request) {
        $validator = Validator::make($request->all(), [
                    'question' => 'required',
                    'answer' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => 'The given data was invalid.',
                        'data' => $validator->errors()
                            ], 422);
        }
        try {
            $faq = new Faq([
                'question' => $request->question,
                'answer' => $request->answer,
            ]);
            if ($faq->save()) {
                return response()->json([
                                'status' => true,
                                'message' => 'Faq created successfully.',
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
    
    public function singleFaq(Request $request) {
        $faq = Faq::where('id', $request->faq_id)->first();
        if($faq->count() > 0) {
            return response()->json([
                    'status' => true,
                    'message' => 'Single faq',
                    'data' => $faq
                        ], 200);
        }
        return response()->json([
                    'status' => true,
                    'message' => 'No faq',
                    'data' => []
                        ], 200);
    }
    
    public function updateFaq(Request $request) {
        $validator = Validator::make($request->all(), [
                    'faq_id' => 'required',
                    'question' => 'required',
                    'answer' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => 'The given data was invalid.',
                        'data' => $validator->errors()
                            ], 422);
        }
        
        try {
            Faq::where('id', $request->faq_id)->update(['question' => $request->question, 'answer' => $request->answer]);
            return response()->json([
                    'status' => true,
                    'message' => 'Faq updated sucessfully.',
                    'data' => []
                        ], 200);
        } catch (Exception $e) {
            Log::error(json_encode($e->getMessage()));
            return response()->json([
                        'status' => false,
                        'message' => $e->getMessage(),
                        'data' => []
                            ], 500);
        }
    }
    public function deleteFaq(Request $request) {
        try {
            Faq::whereId($request->faq_id)->delete();
            return response()->json([
                        'status' => true,
                        'message' => 'Faq deleted successfully.',
                        'data' => []
                            ], 200);
        } catch (Exception $e) {
            Log::error(json_encode($e->getMessage()));
            return response()->json([
                        'status' => false,
                        'message' => $e->getMessage(),
                        'data' => []
                            ], 500);
        }
    }
}
