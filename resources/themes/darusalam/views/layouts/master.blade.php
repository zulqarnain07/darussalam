<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <title>@yield('page_title')</title>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="content-language" content="{{ app()->getLocale() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        @yield('head')

        @section('seo')
            <meta name="description" content="{{ core()->getCurrentChannel()->description }}"/>
        @show

        @stack('css')
        
        <style>
            .main-heading {
                top: 30%;
                left: 35%;
                font-size: 50px;
                position: absolute;
            }
        </style>

        {!! view_render_event('bagisto.shop.layout.head') !!}
    </head>

    <body @if (core()->getCurrentLocale()->direction == 'rtl') class="rtl" @endif>
        {!! view_render_event('bagisto.shop.layout.body.before') !!}
            <b class="main-heading">Hello Darusalam Theme</b>
        {!! view_render_event('bagisto.shop.layout.body.after') !!}
    </body>
</html>
