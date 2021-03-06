<?php

namespace App\Http\Controllers;

use App\Category;
use App\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // 등록된 카테고리 목록 조회
    public function index() {
        /**
         * 1. post 조회 -> join :: category
         * 2. groupBy
         * 3. 카운트만 빼서 반환                 
         */

        $category_group = Category::select('category_id as id', 'categories.title as title', DB::raw('count(*) as posts'))
        ->join('posts', 'category_id', 'category')
        ->groupBy('category_id')
        ->get();
        
        return self::response_json("카테고리 목록 조회에 성공하였습니다.", 200, $category_group);
    }

    // 카테고리 생성
    public function store(Request $request) {
        $rules = [
            'title' => 'required|string',
        ];

        $validated_result = self::request_validator(
            $request, $rules, '카테고리 등록에 실패했습니다.'
        );

        if (is_object($validated_result)) {
            return $validated_result;
        }

        $created_category = Category::create($request->all());

        return self::response_json("카테고리 생성에 성공하였습니다.", 201, $created_category);
    }

}
