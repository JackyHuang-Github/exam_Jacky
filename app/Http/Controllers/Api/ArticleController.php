<?php

namespace App\Http\Controllers\Api;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

//請完成下方所有方法的實作，並撰寫對應的路由，用 Postman 來進行測試
class ArticleController extends Controller
{
    /**
     * 回傳該表格的所有資料，以 sort 欄位從小到大排序
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = Article::orderby('sort', 'asc')->get();
        return $articles;
    }

    /**
     * 儲存前端傳入的資料，成功後回傳儲存的內容
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $article = Article::create($request->all());
        $article = $article->refresh();
        return response($article, Response::HTTP_CREATED);

        // $subject = $this->request('subject');
        // $enabled_at = $request('enabled_at');
        // $sort = $request('sort');
        // $pic = $request('pic');
        // $enabled = $request('enabled');
        // $cgy_id = $request('cgy_id');

        // $result = 
        //     "subject = {$subject}, <br>" .
        //     "enabled_at = {$enabled_at}, <br>" .
        //     "sort = {$sort}, <br>" .
        //     "pic = {$pic}, <br>" .
        //     "enabled = {$enabled}, <br>" .
        //     "cgy_id = {$cgy_id} <br>";

        // $result = 'Okay';

        // $result = [
        //     $subject,
        //     $enabled_at,
        //     $sort,
        //     $pic,
        //     $enabled,
        //     $cgy_id
        // ];

        // return $result;
    }

    /**
     * 回傳指定的資料
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $article = Article::find($id);
        return $article;
    }

    /**
     * 更新指定的資料，成功後回傳更新後的內容
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $article = Article::find($id);
        $article->update($request->all());
        return $article;
    }

    /**
     * 刪除指定的資料，成功後回傳 Success
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $article = Article::find($id);
        $article->delete();
        return 'success';
    }

    //查詢所有資料，只取 id , subject 以及 content 這三個欄位
    public function querySelect()
    {
        $articles= Article::select(['id', 'subject', 'content'])->get();
        return $articles;
    }

    //查詢 enabled_at 於 2022/12/13 00:00:00 之後，enabled 為 true 的資料，按照 created_at 從新到舊排序，回傳第一筆資料的 subject 欄位內容
    public function querySpecific()
    {
        $articles= Article::select('subject')->where('enabled_at', '>', '2022/12/13 00:00:00')->where('enabled', true)->orderby('created_at', 'desc')->first();
        return $articles;
    }

    //查詢 enabled_at 於 2022/12/10 00:00:00 之後，enabled 為 true 的資料，按照 created_at 從新到舊排序，回傳第2~4筆資料
    public function queryPagination()
    {
        $articles= Article::where('enabled_at', '>', '2022/12/10 00:00:00')->where('enabled', true)->orderby('created_at', 'desc')->skip(1)->take(3)->get();
        return $articles;
    }

    //查詢 enabled_at 介於 2022/12/10 00:00:00 和 2022/12/15 23:59:59 之間，sort 位於 $min 到 $max 之間的資料並回傳
    public function queryRange($min,$max)
    {
        $articles = Article::whereBetween('enabled_at', ['2022/12/10 00:00:00', '2022/12/15 23:59:59'])->whereBetween('sort', [$min, $max])->orderby('sort', 'asc')->get();
        return $articles;
    }

    //根據所傳入的分類id，取出該分類所有 enabled 為 true 的資料，依照 sort 從小到大排序，回傳符合的資料
    public function queryByCgy($cgy_id)
    {
        $articles = Article::where('cgy_id', $cgy_id)->where('enabled', true)->orderby('sort', 'asc')->get();
        return $articles;
    }

    //試著使用 pluck() 來取得 id 為 key ， subject 為 value 的陣列
    public function queryPluck()
    {
        $articles = Article::get();
        $plucked = $articles->pluck(['id' => 'subject']);
        return $plucked->all();
    }

    //計算所有 enabled 為 true 的資料筆數後回傳，利用查詢方法 count()
    public function enabledCount()
    {
        $article = Article::where('enabled', true)->count();
        return $article;
    }
}
