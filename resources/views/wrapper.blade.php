<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('vitepress.title', 'Documentation') }}</title>
</head>
<body>
    {{--
        This is an optional wrapper template.
        You can use this to add Laravel-specific elements around your VitePress documentation.

        For most use cases, the VitePress documentation is served directly without this wrapper.

        To use this wrapper, customize the controller to return this view instead of serving
        the static files directly.
    --}}

    <div id="vitepress-content">
        {!! $content !!}
    </div>
</body>
</html>
