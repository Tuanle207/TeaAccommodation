@extends('base')

@section('page_title', 'Web app tìm phòng trọ tiện lợi, nhanh chóng')
@section('custom_head')
<script type='text/javascript' src='http://www.bing.com/api/maps/mapcontrol?setLang=vi&callback=getMap&key=Ap2rAZTQv3mCqJbPxwIbJRhxhLjLWduk9rpCfxp8iO5OGHazrY8vBEewDHWWwwCl' async defer></script>
@endsection

@section('header')
    @parent
@endsection

@section('content')
    <div class="mapbox">
        <div class="mapbox__search">
            <div class="mapbox_search--inputWrapper" id="mapbox__search">
                <input class="mapbox__search--input" id="search_box" type="text" placeholder="Nhập địa điểm" value="">
            </div>
            <p class="mapbox__search--text">Tìm kiếm địa điểm</p>
        </div>
        <div class="mapbox__map" id="map"></div>
    </div>
    <script src="/js/app.js" type="text/javascript"></script>
@endsection