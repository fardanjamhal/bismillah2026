
<!doctype html>
<html lang="id" translate="no" class="{{ isset($setting) && $setting->sidebar_color ? 'color-sidebar '.$setting->sidebar_color : '' }} {{ isset($setting) && $setting->header_color ? 'color-header '.$setting->header_color : '' }}">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!--favicon-->
        <link rel="icon" href="{{ isset($setting) && $setting->favicon ? asset('storage/upload_files/settings/'.$setting->favicon) : asset('assets/images/logo.png') }}" type="image/png" />
        <!--plugins-->
        <link href="{{ asset('assets/plugins/vectormap/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet"/>
        <link href="{{ asset('assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />
        <!-- Bootstrap CSS -->
        <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/bootstrap-extended.css') }}" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
        <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/header-colors.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/42.0.1/ckeditor5-editor.css" />
        <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/42.0.1/ckeditor5-content.css" />

        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="{{ $setting->meta_description ?? 'Deskripsi Aplikasi CAT' }}">
        <meta name="keywords" content="{{ $setting->meta_keywords ?? 'isi keywords' }}">
        
        <meta property="og:title" content="{{ $setting->meta_title ?? 'Aplikasi CAT' }}">
        <meta property="og:description" content="{{ $setting->meta_description ?? 'Deskripsi Aplikasi CAT' }}">
        <meta property="og:image" content="{{ $setting->meta_image ? asset('storage/upload_files/settings/'.$setting->meta_image) : asset('assets/images/logo.png') }}">
        <meta property="og:url" content="{{ $setting->meta_url ?? url()->current() }}">
        <meta property="og:type" content="{{ $setting->meta_type ?? 'website' }}">

        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $setting->meta_title ?? 'Aplikasi CAT' }}">
        <meta name="twitter:description" content="{{ $setting->meta_description ?? 'Deskripsi Aplikasi CAT' }}">
        <meta name="twitter:image" content="{{ $setting->meta_favicon ? asset('storage/upload_files/settings/'.$setting->meta_favicon) : asset('assets/images/logo.png') }}">
        <meta name="twitter:url" content="{{ $setting->meta_url ?? url()->current() }}">
        
        <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

        <style>
            .tox-notification  {display: none !important;}
        </style>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @inertiaHead
    </head>

    <body>
        @inertia

        <!-- Bootstrap JS -->
        <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
        <!--plugins-->
        <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>

        @if (isset($setting) && isset($setting->payment_methods) && collect($setting->payment_methods)->contains(fn($method) => $method['code'] === 'automatic_transfer_midtrans' && $method['is_active'] === 1))
            @php
                $midtransUrl = $setting->payment_gateway_mode == 1
                    ? 'https://app.sandbox.midtrans.com/snap/snap.js'
                    : 'https://app.midtrans.com/snap/snap.js';
            @endphp
            <script type="text/javascript" src="{{ $midtransUrl }}" data-client-key="{{ config('services.midtrans.clientKey') }}"></script>
        @endif
    </body>
</html>
