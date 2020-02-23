<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* 後台要驗證 */
// FIXME: 不懂命名路由的意義 -> 命名路由讓你更方便為特定路由產生 URLs 或進行重導。可以在定義路由時使用陣列的 as 鍵為路由指定名稱
//        -> 路由命名可以让我们在使用 route函数 生成指向该路由的URL或者生成跳转到该路由的重定向链接时更加方便
Route::group(['prefix' => 'backend', 'as' => 'backend::', 'middleware' => ['auth:admin']], function () {
    //後台首頁
    Route::get('/', 'Backend\IndexController@index');

    //後台會員管理
    Route::group(['prefix' => 'admin', 'as' => 'admin::'], function () {
        //列表
        Route::get('/', 'Backend\AdminController@lists');
        Route::get('lists', 'Backend\AdminController@lists');
        //新增
        Route::get('add', 'Backend\AdminController@add');
        //修改 //TODO: 路由的參數都會被放在「大括號」內。當執行路由時，參數會透過路由閉包來傳遞。
        Route::get('upd/{id}', 'Backend\AdminController@upd')->where('id', '[0-9]+');

        //新增送出
        Route::post('add', 'Backend\AdminController@addPost');
        //修改送出
        Route::post('upd/{id}', 'Backend\AdminController@updPost')->where('id', '[0-9]+');
        //刪除資料
        Route::post('delData', 'Backend\AdminController@delData');
    });

    //後台會員群組管理
    Route::group(['prefix' => 'admingroup', 'as' => 'admingroup::'], function () {
        //列表
        Route::get('/', 'Backend\AdminGroupController@lists');
        Route::get('lists', 'Backend\AdminGroupController@lists');
        //新增
        Route::get('add', 'Backend\AdminGroupController@add');
        //修改
        Route::get('upd/{id}', 'Backend\AdminGroupController@upd')->where('id', '[0-9]+');

        //新增送出
        Route::post('add', 'Backend\AdminGroupController@addPost');
        //修改送出
        Route::post('upd/{id}', 'Backend\AdminGroupController@updPost')->where('id', '[0-9]+');
        //刪除資料
        Route::post('delData', 'Backend\AdminGroupController@delData');
    });

    // 後台經銷商管理
    Route::group(['prefix' => 'dealer', 'as' => 'dealer::'], function () {
        // 列表
        Route::get('/', 'Backend\DealerController@lists');

        Route::group(['prefix' => 'lists', 'as' => 'lists::'], function () {
            // 列表
            Route::get('/', 'Backend\DealerController@lists');
            // 新增
            Route::get('add', 'Backend\DealerController@add');
            //修改
            Route::get('upd/{id}', 'Backend\DealerController@upd')->where('id', '[0-9]+');

            //新增送出
            Route::post('add', 'Backend\DealerController@addPost');
            //修改送出
            Route::post('upd/{id}', 'Backend\DealerController@updPost')->where('id', '[0-9]+');

            //刪除資料
            Route::post('delData', 'Backend\DealerController@delData');
        });

        // 分店管理
        Route::group(['prefix' => 'branch', 'as' => 'branch::'], function () {
            // 列表
            Route::get('/', 'Backend\BranchController@lists');

            Route::group(['prefix' => 'lists', 'as' => 'list::'], function () {
                // 列表
                Route::get('/', 'Backend\BranchController@lists');

                // 新增
                Route::get('add', 'Backend\BranchController@add');
                //修改
                Route::get('upd/{id}', 'Backend\BranchController@upd')->where('id', '[0-9]+');

                //新增送出
                Route::post('add', 'Backend\BranchController@addPost');
                //修改送出
                Route::post('upd/{id}', 'Backend\BranchController@updPost')->where('id', '[0-9]+');

                //刪除資料
                Route::post('delData', 'Backend\BranchController@delData');
            });
        });

        Route::group(['prefix' => 'client', 'as' => 'client::'], function () {
             // 列表
             Route::get('/', 'Backend\ClientController@lists');

             Route::group(['prefix' => 'lists', 'as' => 'lists::'], function () {
                // 列表
                Route::get('/', 'Backend\ClientController@lists');

                // 新增
                Route::get('add', 'Backend\ClientController@add');
                //修改
                Route::get('upd/{id}', 'Backend\ClientController@upd')->where('id', '[0-9]+');

                //新增送出
                Route::post('add', 'Backend\ClientController@addPost');
                //修改送出
                Route::post('upd/{id}', 'Backend\ClientController@updPost')->where('id', '[0-9]+');

                //刪除資料
                Route::post('delData', 'Backend\ClientController@delData');

             });

        });
    });
});


/* 不用驗證 */
Route::group(['prefix' => 'backend', 'as' => 'backend::'], function () {
    //登入
    // TODO: 'uses' -> 可以指定路由名稱到你的控制器操作
    // FIXME: 為什麼不直接寫
    // Route::get('login', 'Backend\LoginController@login']);

    // TODO: 有需要用到的時候再使用命名路由才會特別命名 -> 中介層Authenticate.php會使用
    Route::get('login', ['as' => 'login', 'uses' => 'Backend\LoginController@login']);
    //登入送出
    Route::post('login', 'Backend\LoginController@loginPost');
    //登出
    Route::get('logout', 'Backend\LoginController@logout');
});
