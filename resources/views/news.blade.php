@extends('layouts.noox')

@section('content')
<div id="maincontentwrapper">
<div class="news-date">{{ $news->date }}</div>
    <div class="news-title">{{ $news->title }}</div>
    <div class="news-asset">
        <div class="news-source">
            <div class="news-source-icon">
                <img src="http://s2.googleusercontent.com/s2/favicons?domain_url={{ $news->source->base_url }}">
            </div>
            <div class="news-source-text">
                {{ $news->source->source_name }}
            </div>
        </div>
        <div class="news-category national">
            <span class="text">{{ $news->category->name }}</span>
            <span class="vertical-mid-helper"></span>
        </div>
    </div>
    <div class="news-picture">
        <img src="{{ asset( 'img/news/'.$news->id.'.jpg' ) }}" onerror="this.onerror=null;this.src=base_url+'/img/img-unavailable.png'"/>
    </div>
    <div class="news-author">Author : <span class="author">{{ ucwords(strtolower($news->author)) }}</span></div>
    <div class="news-separator"></div>
    <div class="news-text">
        {!! $news->content !!}
    </div>
    <div class="news-others-heading">Other Top Stories</div>
    <div class="news-others">
        @foreach ($otherNews as $data)
        <div class="news-others-container" data-id="{{ $data->id }}">
            <div class="no-photo">
                <img src="{{ asset( 'img/news/'.$data->id.'.jpg' ) }}" onerror="this.onerror=null;this.src=base_url+'/img/img-unavailable.png'" />
            </div>
            <div class="no-title">{{ $data->title }}</div>
            <div class="no-asset">
                <div class="no-time">{{ Carbon\Carbon::parse($data->pubtime)->diffForHumans() }}</div>
                <div class="no-category business">
                    <span class="text">{{ $data->category->name }}</span>
                    <span class="vertical-mid-helper"></span>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection