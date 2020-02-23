<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Auth;

class LoginController extends MY_BackendController
{

    public function __construct(Request $request)
    {
        //判斷是否有登入
        $this->middleware(function ($request, $next) {
            if (Auth::check()) {
                return redirect("/backend");
            } else {
                return $next($request);
            }
        });
    }

    //登入
    public function login()
    {
        return view("backend.login.login");
    }

    //登入送出
    public function loginPost(Request $request)
    {
        $this->validate(
            $request,
            [
                'email' => 'required|email',
                'password' => 'required',
                // 'captcha' => 'required|captcha'
            ]
        );

        // FIXME: 不懂all()用法
        $data = $request->all();

        // TODO: attempt 會接受一個陣列來作為第一個參數，這個陣列的值會用來找尋資料庫裡的使用者資料
        // TODO: 客製化guard
        $attempt = Auth::guard("admin")->attempt([
            'email' => $data['email'],
            'password' => $data['password'],
            'is_active' => 1
        ]);

        if ($attempt) {
            if (isset($data["redirectUrl"])) {
                return redirect($data["redirectUrl"]);
            } else {
                return redirect("/backend");
            }
        }

        return redirect()->back()->withErrors(['fail' => '帳號密碼錯誤']);
    }

    //登出
    public function logout()
    {
        Auth::logout();
        return redirect("/backend/login");
    }
}
