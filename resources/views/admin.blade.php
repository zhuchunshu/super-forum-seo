@extends('app')
@section('title','Seo设置')
@section('content')

    <div class="col-md-12">

        <div class="row row-cards">

            @include('Seo::admin.sitemap')
            @include('Seo::admin.baidu')

        </div>

    </div>

@endsection