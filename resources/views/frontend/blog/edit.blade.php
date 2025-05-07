@extends('frontend.layouts.master')
@section('content')
<link rel="stylesheet" href="/frontend/css/blog-detail.css">
<div class="blog-detail-container">
    <div class="blog-detail-card">
        <div class="blog-detail-header">
            <img class="blog-detail-cover" src="{{ $blogs->photo ? (is_array(json_decode($blogs->photo, true)) 
            ? asset(json_decode($blogs->photo, true)[0]) : asset($blogs->photo)) : '/frontend/images/default.jpg' }}" alt="{{ $blogs->title }}">
            <div class="blog-detail-author">
             
                <img class="author-avatar" src="{{ $blogs->user->photo ? asset('storage/' . $blogs->user->photo) : 'https://i.pinimg.com/736x/bc/43/98/bc439871417621836a0eeea768d60944.jpg' }}" alt="Tác giả">
                <div>
                    <div class="author-name" style="color:#555">{{ $blogs->user->full_name ?? 'Ẩn danh' }}</div>
                    <div class="blog-date">{{ \Carbon\Carbon::parse($blogs->created_at)->diffForHumans() }}</div>
                </div>
            </div>
        </div>
        <div class="blog-detail-content">
            <h1 class="blog-title" style="color:black">{{ $blogs->title }}</h1>
            <!-- <div class="blog-tags">
                @php
                    use App\Models\Tag;
                    $tags = Tag::whereIn('id', json_decode($blogs->tags, true))->get();

                @endphp
                @foreach($tags as $tag)
                    <a href="#" class="blog-tag" style="color:black">#{{ $tag->title }}</a>
                @endforeach
            </div> -->
            <div class="blog-body">
                {!! $blogs->summary !!}
            </div>
            <div class="blog-body">
                {!! $blogs->content !!}
            </div>
        </div>
    </div>
</div>
<style>
.blog-detail-container {
    display: flex;
    justify-content: center;
    padding: 32px 8px;
   
}
.blog-detail-card {
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 4px 32px #0001;
    max-width: 1000px;
    width: 100%;
    overflow: hidden;
    padding-bottom: 32px;
}
.blog-detail-header {
    position: relative;
}
.blog-detail-cover {
    width: 100%;
    height: 320px;
    object-fit: cover;
    border-bottom: 1px solid #eee;
}
.blog-detail-author {
    display: flex;
    align-items: center;
    gap: 14px;
    position: absolute;
    left: 24px;
    bottom: -32px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 8px #0002;
    padding: 10px 18px;
}
.author-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #eee;
}
.author-name {
    font-weight: bold;
    font-size: 1.1em;
}
.blog-date {
    color: #888;
    font-size: 0.95em;
}
.blog-detail-content {
    padding: 48px 32px 0 32px;
}
.blog-title {
    font-size: 2.1em;
    font-weight: bold;
    margin-bottom: 12px;
}
.blog-tags {
    margin-bottom: 18px;
}
.blog-tag {
    display: inline-block;
    background: #e3f2fd;
    color: #1976d2;
    border-radius: 6px;
    padding: 4px 12px;
    margin-right: 6px;
    font-size: 0.98em;
    text-decoration: none;
    transition: background 0.2s;
}
.blog-tag:hover {
    background: #bbdefb;
}
.blog-body {
    font-size: 1.15em;
    line-height: 1.7;
    color: #222;
    margin-bottom: 32px;
}
.blog-detail-footer {
    padding: 0 32px;
    margin-top: 32px;
}
.blog-related-title {
    font-weight: bold;
    margin-bottom: 12px;
    font-size: 1.1em;
}
.blog-related-list {
    display: flex;
    gap: 18px;
    overflow-x: auto;
}
.blog-related-item {
    display: flex;
    flex-direction: column;
    min-width: 180px;
    background: #f8fafc;
    border-radius: 10px;
    box-shadow: 0 2px 8px #0001;
    text-decoration: none;
    color: #222;
    transition: box-shadow 0.2s;
}
.blog-related-item:hover {
    box-shadow: 0 4px 16px #0002;
}
.blog-related-item img {
    width: 100%;
    height: 100px;
    object-fit: cover;
    border-radius: 10px 10px 0 0;
}
.blog-related-info {
    padding: 10px;
}
.blog-related-title {
    font-size: 1em;
    font-weight: bold;
    margin-bottom: 4px;
}
.blog-related-date {
    color: #888;
    font-size: 0.95em;
}
.blog-comments {
    padding: 0 32px;
    margin-top: 32px;
}
@media (max-width: 600px) {
    .blog-detail-card { padding-bottom: 0; }
    .blog-detail-content, .blog-detail-footer, .blog-comments { padding: 18px 8px 0 8px; }
    .blog-detail-cover { height: 180px; }
    .blog-detail-author { left: 8px; bottom: -24px; padding: 6px 10px; }
}
</style>
@endsection
