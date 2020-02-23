
@php
    $sidebarList = App\Http\Controllers\Backend\MY_BackendController::getSidebarList();
    // TODO: 取得當前使用者權限
    $permission = Auth::user()->adminGroup->permission;
@endphp
<!-- BEGIN SIDEBAR -->
<div class="page-sidebar-wrapper">
    <!-- BEGIN SIDEBAR -->
    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
    <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
    <div class="page-sidebar navbar-collapse collapse">
        <!-- BEGIN SIDEBAR MENU -->
        <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
        <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
        <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
        <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
        <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
        <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->

        <ul class="page-sidebar-menu   " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
            {{-- @php
                echo '<pre>';
                print_r($sidebarList);
                exit;
            @endphp --}}
            @foreach ($sidebarList as $id => $va)
                {{-- TODO: 驗證權限 --}}
                @if(strrpos($permission, "[".$id."A],") !== false)
                {{-- TODO: 判斷是否有sub這個陣列存在 -> 假如沒有則跳到else執行沒有小選單的情況 --}}
                    @if(isset($va["sub"]))
                        @php
                            $clickSidebar = false;
                        @endphp
                        @foreach($va["sub"] as $sub_id => $sub_va)
                            @if($sub_va["url"] == "/backend/")
                                @if(("/".Request::path()."/") == $sub_va["url"])
                                    @php
                                        $clickSidebar = true;
                                    @endphp
                                @endif
                            @else
                                @if(strrpos(("/".Request::path()."/"), $sub_va["url"]) !== false)
                                    @php
                                        $clickSidebar = true;
                                    @endphp
                                    {{-- FIXME: 為什麼這邊要使用break --}}
                                    @break
                                @endif
                            @endif
                        @endforeach
                        <li class="nav-item start @if($clickSidebar) active open @endif">
                            <a href="javascript:;" class="nav-link nav-toggle">
                                @if($va["icon"])
                                    <i class="{{ $va["icon"] }}"></i>
                                @endif
                                <span class="title">{{ $va["name"] }}</span>
                                <span class="arrow @if($clickSidebar) open @endif"></span>
                            </a>
                            <ul class="sub-menu">
                                @foreach($va["sub"] as $sub_id => $sub_va)
                                    @php
                                        $clickSidebar = false;
                                    @endphp
                                    @if(strrpos($permission, "[".$sub_id."A],") !== false)
                                        @if($sub_va["url"] == "/backend/")
                                            @if(("/".Request::path()."/") == $sub_va["url"])
                                                @php
                                                    $clickSidebar = true;
                                                @endphp
                                            @endif
                                        @else
                                            @if(strrpos(("/".Request::path()."/"), $sub_va["url"]) !== false)
                                                @php
                                                    $clickSidebar = true;
                                                @endphp
                                            @endif
                                        @endif
                                        <li class="nav-item start @if($clickSidebar) active open @endif">
                                            <a href="{{ $sub_va["url"] }}" class="nav-link ">
                                                @if($sub_va["icon"])
                                                    <i class="{{ $sub_va["icon"] }}"></i>
                                                @endif
                                                <span class="title">{{ $sub_va["name"] }}</span>
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                    @else
                        @php
                            $clickSidebar = false;
                        @endphp
                        @if($va["url"] == "/backend/")
                        {{-- TODO: 判斷首頁的URL情況 --}}
                            @if(("/".Request::path()."/") == $va["url"])
                                @php
                                // TODO: 當把下方 $clickSidebar 拿掉後 -> 在選到該sidebar欄位即不會有背景效果
                                    $clickSidebar = true;
                                @endphp
                            @endif
                        @else
                        {{-- TODO: 判斷非首頁的URL情況 --}}
                            @if(strrpos(("/".Request::path()."/"), $va["url"]) !== false)
                                @php
                                    $clickSidebar = true;
                                @endphp
                            @endif
                        @endif

                        {{-- TODO: active open 為選單選擇該sidebar後的自動背景效果 --}}
                        <li class="nav-item @if($clickSidebar) active open @endif">
                            <a href="{{ $va["url"] }}" class="nav-link nav-toggle">
                                @if($va["icon"])
                                {{-- FIXME: 公司的圖像無法顯示可能原因 -> font-awesome版本不同 --}}
                                    <i class="{{ $va["icon"] }}"></i>
                                @endif
                                <span class="title">{{ $va["name"] }}</span>
                            </a>
                        </li>

                    @endif
                @endif
            @endforeach
            {{-- <li class="nav-item">
                <a href="/backend/" class="nav-link nav-toggle">
                    <i class="icon-home"></i>
                    <span class="title">Dashboard</span>
                </a>
            </li>
            <li class="nav-item active open">
                <a href="/backend/admin/" class="nav-link nav-toggle">
                    <i class="icon-user"></i>
                    <span class="title">後台會員管理</span>
                </a>
            </li> --}}

            {{-- <li class="nav-item start ">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-home"></i>
                    <span class="title">Dashboard</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item start ">
                        <a href="index.html" class="nav-link ">
                            <i class="icon-bar-chart"></i>
                            <span class="title">Dashboard 1</span>
                        </a>
                    </li>
                    <li class="nav-item start ">
                        <a href="dashboard_2.html" class="nav-link ">
                            <i class="icon-bulb"></i>
                            <span class="title">Dashboard 2</span>
                            <span class="badge badge-success">1</span>
                        </a>
                    </li>
                    <li class="nav-item start ">
                        <a href="dashboard_3.html" class="nav-link ">
                            <i class="icon-graph"></i>
                            <span class="title">Dashboard 3</span>
                            <span class="badge badge-danger">5</span>
                        </a>
                    </li>
                </ul>
            </li> --}}
            {{-- <li class="heading">
                <h3 class="uppercase">Pages</h3>
            </li> --}}


            {{-- <li class="nav-item  active open">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-user"></i>
                    <span class="title">User</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item  active open">
                        <a href="page_user_profile_1.html" class="nav-link ">
                            <i class="icon-user"></i>
                            <span class="title">Profile 1</span>
                        </a>
                    </li>
                    <li class="nav-item  ">
                        <a href="page_user_profile_1_account.html" class="nav-link ">
                            <i class="icon-user-female"></i>
                            <span class="title">Profile 1 Account</span>
                        </a>
                    </li>
                    <li class="nav-item  ">
                        <a href="page_user_profile_1_help.html" class="nav-link ">
                            <i class="icon-user-following"></i>
                            <span class="title">Profile 1 Help</span>
                        </a>
                    </li>
                    <li class="nav-item  ">
                        <a href="page_user_profile_2.html" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">Profile 2</span>
                        </a>
                    </li>
                    <li class="nav-item  ">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <i class="icon-notebook"></i>
                            <span class="title">Login</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <li class="nav-item ">
                                <a href="page_user_login_1.html" class="nav-link " target="_blank"> Login Page 1 </a>
                            </li>
                            <li class="nav-item ">
                                <a href="page_user_login_2.html" class="nav-link " target="_blank"> Login Page 2 </a>
                            </li>
                            <li class="nav-item ">
                                <a href="page_user_login_3.html" class="nav-link " target="_blank"> Login Page 3 </a>
                            </li>
                            <li class="nav-item ">
                                <a href="page_user_login_4.html" class="nav-link " target="_blank"> Login Page 4 </a>
                            </li>
                            <li class="nav-item ">
                                <a href="page_user_login_5.html" class="nav-link " target="_blank"> Login Page 5 </a>
                            </li>
                            <li class="nav-item ">
                                <a href="page_user_login_6.html" class="nav-link " target="_blank"> Login Page 6 </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item  ">
                        <a href="page_user_lock_1.html" class="nav-link " target="_blank">
                            <i class="icon-lock"></i>
                            <span class="title">Lock Screen 1</span>
                        </a>
                    </li>
                    <li class="nav-item  ">
                        <a href="page_user_lock_2.html" class="nav-link " target="_blank">
                            <i class="icon-lock-open"></i>
                            <span class="title">Lock Screen 2</span>
                        </a>
                    </li>
                </ul>
            </li> --}}

        </ul>
        <!-- END SIDEBAR MENU -->
    </div>
    <!-- END SIDEBAR -->
</div>
<!-- END SIDEBAR -->
