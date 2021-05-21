<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/dashboard" class="brand-link">
        <img src="/logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">NWS LAB</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
            </div>
            <div class="info">
{{--                <a href="{{route('account.profile')}}" class="d-block">{{\Illuminate\Support\Facades\Auth::user()->fullName}}</a>--}}
                <p class="text-white mt-2">
                    @foreach(\Illuminate\Support\Facades\Auth::user()->roles as $role)
                        <i class="{{$role->icon}}" title="{{$role->name}}"> {{$role->name}}</i>
                    @endforeach
                </p>
            </div>
        </div>


        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->

                @can('permissions')
                    <li class="nav-item ">
                        <a href="{{route('permissions.index')}}"
                           class="nav-link {{request()->is('dashboard/permissions*') ?'active':''}}">
                            <i class=" fas fa-user-tag nav-icon"></i>
                            <p>Permissions</p>
                        </a>
                    </li>
                @endcan

                @can('roles')
                    <li class="nav-item">
                        <a href="{{route('roles.index')}}"
                           class="nav-link {{request()->is('dashboard/roles*') ?'active':''}}">
                            <i class=" fas fa-user-tag nav-icon"></i>
                            <p>Roles</p>
                        </a>
                    </li>
                @endcan

                @can('keys')
                    <li class="nav-item">
                        <a href="{{route('keys.index')}}"
                           class="nav-link {{request()->is('dashboard/keys*') ?'active':''}}">
                            <i class=" fas fa-key nav-icon"></i>
                            <p> Keys</p>
                        </a>
                    </li>
                @endcan
                <li class="nav-item">
                    <a href="{{route('countries.index')}}"
                       class="nav-link {{request()->is('dashboard/countries*') ?'active':''}}">
                        <i class=" fas fa-key nav-icon"></i>
                        <p>Countries</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route('proxies.index')}}"
                       class="nav-link {{request()->is('dashboard/proxies*') ?'active':''}}">
                        <i class=" fas fa-key nav-icon"></i>
                        <p>Proxies</p>
                    </a>
                </li>


            @can('users')
                    <li class="nav-item">
                        <a href="{{route('users.index')}}"
                           class="nav-link {{request()->is('dashboard/users*') ?'active':''}}">
                            <i class=" fas fa-user-alt nav-icon"></i>
                            <p>Users</p>
                        </a>
                    </li>
                @endcan

                @can('accounts')
                    <li class="nav-item">
                        <a href="{{route('accounts.index')}}"
                           class="nav-link {{request()->is('dashboard/accounts*') ?'active':''}}">
                            <i class=" fas fa-user-tag nav-icon"></i>
                            <p>Accounts</p>
                        </a>
                    </li>
                @endcan



                @can('connections')
                <li class="nav-item">
                    <a href="{{route('connections.index')}}"
                       class="nav-link {{request()->is('dashboard/connections*') ?'active':''}}">
                        <i class=" fas fa-user-alt nav-icon"></i>
                        <p>Connections</p>
                    </a>
                </li>

                @endcan


                <li class="nav-item">
                    <a href="{{route('connectionRequest.index')}}"
                       class="nav-link {{request()->is('dashboard/connection-request*') ?'active':''}}">
                        <i class=" fas fa-user-alt nav-icon"></i>
                        <p>Connection Requests</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route('companies.index')}}"
                       class="nav-link {{request()->is('dashboard/companies*') ?'active':''}}">
                        <i class=" fas fa-user-alt nav-icon"></i>
                        <p>Companies</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route('linkedin.chat')}}"
                       class="nav-link {{request()->is('dashboard/linkedin/chat') ?'active':''}}">
                        <i class="nav-icon fab fa-linkedin mr-2"></i>
                        <p>Chat</p>
                    </a>
                </li>

{{--                <li class="nav-item">--}}
{{--                    <a href="{{route('search.index')}}"--}}
{{--                       class="nav-link {{request()->is('dashboard/search/linkedin') ?'active':''}}">--}}
{{--                        <i class="nav-icon fab fa-linkedin mr-2"></i>--}}
{{--                        <p>Search</p>--}}
{{--                    </a>--}}
{{--                </li>--}}

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
