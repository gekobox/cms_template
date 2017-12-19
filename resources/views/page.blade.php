@extends('layouts.app')

@section('content')
    <hs-side-nav>        
        <div slot="links">
            <div id="sideNavLinksContainer">
            <div id="sideNavLinks"></div>
            </div>
        </div>
    </hs-side-nav>
    
    <hs-header></hs-header>
    {{--<keep-alive>--}}
        <router-view></router-view>
    {{--</keep-alive>--}}
        
@endsection