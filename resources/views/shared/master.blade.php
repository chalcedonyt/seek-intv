<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>@yield('title')</title>
        <!-- Optional theme -->
        <link rel="stylesheet" href="{{mix('css/app.css')}}">
    </head>
    <body>
        <div class='container'>
            <script src="{{mix('js/manifest.js')}}"></script>
            <script src="{{mix('js/vendor.js')}}"></script>
            @yield('scripts')
        </div>
    </body>
</html>