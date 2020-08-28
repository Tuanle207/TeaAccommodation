<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TAPARTMENT - @yield('page_title')</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link href="/favicon.ico" rel="icon">
    <!-- Styles -->
    <link href="/css/main.css" rel="stylesheet">
    @section('custom_head')
    @show
</head>
<body>
    @section('header')
       <div class="header">
           <h1 class="header__title">Web tìm phòng trọ</h1>
       </div>
    @show

    @yield('content')
</body>
</html>