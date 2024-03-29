@extends('adminlte::master')

@section('adminlte_css')
    <link rel="stylesheet"
          href="{{ asset('vendor/adminlte/dist/css/skins/skin-' . config('adminlte.skin', 'blue') . '.min.css') }} ">

    <style type="text/css">
        body {
            padding-right: 0 !important;
        }

        hr {
            border-top: 1px solid #eee;
        }

        .select2 {
            width: 100% !important;
        }

        .dataTable {
            width: 100% !important;
        }

        .bg-white {
            background-color: #ffffff !important;
        }
    </style>

    @stack('css')
    @yield('css')
@stop

@section('body_class', 'skin-' . config('adminlte.skin', 'blue') . ' sidebar-mini ' . (config('adminlte.layout') ? [
    'boxed' => 'layout-boxed',
    'fixed' => 'fixed',
    'top-nav' => 'layout-top-nav'
][config('adminlte.layout')] : '') . (config('adminlte.collapse_sidebar') ? ' sidebar-collapse ' : ''))

@section('body')
    <div class="wrapper">

        <!-- Main Header -->
        <header class="main-header">
            @if(config('adminlte.layout') == 'top-nav')
                <nav class="navbar navbar-static-top">
                    <div class="container">
                        <div class="navbar-header">
                            <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}" class="navbar-brand">
                                {!! config('adminlte.logo', '<b>Admin</b>LTE') !!}
                            </a>
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                                    data-target="#navbar-collapse">
                                <i class="fa fa-bars"></i>
                            </button>
                        </div>

                        <!-- Collect the nav links, forms, and other content for toggling -->
                        <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                            <ul class="nav navbar-nav">
                                @each('adminlte::partials.menu-item-top-nav', $adminlte->menu(), 'item')
                            </ul>
                        </div>
                        <!-- /.navbar-collapse -->
                    @else
                        <!-- Logo -->
                            <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}" class="logo">
                                <!-- mini logo for sidebar mini 50x50 pixels -->
                                <span class="logo-mini">{!! config('adminlte.logo_mini', '<b>A</b>LT') !!}</span>
                                <!-- logo for regular state and mobile devices -->
                                <span class="logo-lg">{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}</span>
                            </a>

                            <!-- Header Navbar -->
                            <nav class="navbar navbar-static-top" role="navigation">
                                <!-- Sidebar toggle button-->
                                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                                    <span class="sr-only">{{ __('adminlte.toggle_navigation') }}</span>
                                </a>
                            @endif
                            <!-- Navbar Right Menu -->
                                <div class="navbar-custom-menu">

                                    <ul class="nav navbar-nav">
                                        <li class="dropdown notifications-menu">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"
                                               aria-expanded="false">
                                                <i class="fa fa-bell-o"></i>
                                                <span id="notificationCounter"
                                                      class="label label-success">{{ sizeof(\App\Auth::user()->unreadNotifications) }}</span>
                                            </a>

                                            <ul class="dropdown-menu">
                                                <li class="header">Notificações</li>

                                                <li>
                                                    <ul id="ulNotifications" class="menu">
                                                        @if(sizeof(\App\Auth::user()->unreadNotifications) == 0)

                                                            <li>
                                                                <a href="#">
                                                                    Nenhuma notificação
                                                                </a>
                                                            </li>

                                                        @else

                                                            @foreach(\App\Auth::user()->unreadNotifications as $notification)

                                                                <li id="notification-{{ $notification->id }}">
                                                                    <form method="post"
                                                                          action="{{ route('api.user.notification.lida', ['id' => $notification->id]) }}">
                                                                        @method('PUT')
                                                                        @csrf
                                                                    </form>

                                                                    <a href="{{ $notification->toArray()['data']['url'] ?? '#' }}"
                                                                       onclick="markAsSeen('{{ $notification->id }}'); return false;">
                                                                        <i class="fa fa-{{ $notification->toArray()['data']['icon'] }}"></i>
                                                                        <b>{{ $notification->toArray()['data']['description'] }}</b>
                                                                        <p style="margin: 0; white-space: normal;">{{ $notification->toArray()['data']['text'] }}</p>
                                                                    </a>
                                                                </li>

                                                            @endforeach

                                                        @endif
                                                    </ul>
                                                </li>

                                                <li class="footer">
                                                    <a href="{{ route('notificacoes') }}">Ver todas</a>
                                                </li>
                                            </ul>
                                        </li>

                                        <li>
                                            <a href="#"
                                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                <i class="fa fa-fw fa-power-off"></i>
                                                {{ __('adminlte.log_out') }} ({{ strtok(\App\Auth::user()->name, " ") }})
                                            </a>
                                            <form id="logout-form"
                                                  action="{{ url(config('adminlte.logout_url', 'auth/logout')) }}"
                                                  method="POST" style="display: none;">
                                                @csrf
                                            </form>
                                        </li>
                                    @if(config('adminlte.right_sidebar') and (config('adminlte.layout') != 'top-nav'))
                                        <!-- Control Sidebar Toggle Button -->
                                            <li>
                                                <a href="#" data-toggle="control-sidebar"
                                                   @if(!config('adminlte.right_sidebar_slide')) data-controlsidebar-slide="false" @endif>
                                                    <i class="{{config('adminlte.right_sidebar_icon')}}"></i>
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            @if(config('adminlte.layout') == 'top-nav')
                    </div>
                    @endif
                </nav>
        </header>

    @if(config('adminlte.layout') != 'top-nav')
        <!-- Left side column. contains the logo and sidebar -->
            <aside class="main-sidebar">

                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">

                    <!-- Sidebar Menu -->
                    <ul class="sidebar-menu" data-widget="tree">
                        @each('adminlte::partials.menu-item', $adminlte->menu(), 'item')
                    </ul>
                    <!-- /.sidebar-menu -->
                </section>
                <!-- /.sidebar -->
            </aside>
    @endif

    <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @if(config('adminlte.layout') == 'top-nav')
                <div class="container">
                @endif

                <!-- Content Header (Page header) -->
                    <section class="content-header">
                        @yield('content_header')
                    </section>

                    <!-- Main content -->
                    <section class="content">

                        @if(session()->has('password_updated') && session('password_updated'))
                            <div class="alert alert-success alert-dismissible" role="alert">
                                {{ __('passwords.change') }}

                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        @yield('content')

                    </section>
                    <!-- /.content -->
                    @if(config('adminlte.layout') == 'top-nav')
                </div>
                <!-- /.container -->
            @endif
        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <b>Versão</b> {{ config('app.version') }}
            </div>

            <b>Copyright © {{ \Carbon\Carbon::now()->year }} Blitz.</b> Todos os direitos reservados.
        </footer>

        @if(config('adminlte.right_sidebar') and (config('adminlte.layout') != 'top-nav'))
            <aside class="control-sidebar control-sidebar-{{config('adminlte.right_sidebar_theme')}}">
                @yield('right-sidebar')
            </aside>
            <!-- /.control-sidebar -->
            <!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
            <div class="control-sidebar-bg"></div>
        @endif

    </div>
    <!-- ./wrapper -->

    <script type="text/javascript">
        // Remove all empty help blocks
        Array.from(document.querySelectorAll('.form-group .help-block:empty')).forEach(element => {
            element.remove();
        });
    </script>
@stop

@section('adminlte_js')
    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            function removeHasError() {
                // Remove the has-error class when the user focuses the input
                this.classList.remove('has-error');

                // Remove the span class=".help-block"
                let help_block = this.querySelector('.help-block');
                if (help_block !== null) {
                    help_block.remove();
                }
            }

            jQuery('.has-error').on('focusin', removeHasError).on('change', removeHasError);
        });

        function markAsSeen(id, redir = true) {
            jQuery.ajax({
                url: `{{ config('app.url') }}/api/usuario/notificacao/${id}/lida`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'PUT'
                },
                success: function (data) {
                    let url = jQuery(`#notification-${id} a`).attr('href');

                    let notificationCounter = jQuery('#notificationCounter');
                    notificationCounter.text(parseInt(notificationCounter.text()) - 1);
                    jQuery(`#notification-${id}`).remove();

                    if (notificationCounter.text() === "0") {
                        let noNotifications = '<li><a href="#">Nenhuma notificação</a></li>';
                        jQuery('#ulNotifications').append(noNotifications);
                    }

                    if (redir) {
                        window.location = url;
                    }
                },
                error: function (e) {
                    if (redir) {
                        window.location = jQuery(`#notification-${id} a`).attr('href');
                    }
                }
            });
        }
    </script>
    @stack('js')
    @yield('js')
@stop
