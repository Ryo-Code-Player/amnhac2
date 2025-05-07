@extends('frontend.layouts.master')
@section('content')
<section class="blog-section" id="blog-section" style="margin-bottom: 100px !important; padding:20px;">
    <div style="display:flex;justify-content:space-between;align-items:center;">
        <h2>Bài viết nổi bật</h2>
        <a href="{{ route('front.blog') }}" style="color:#fff;text-decoration:none;font-size:16px;font-weight:600;">Xem thêm</a>
    </div>
    <div class="blog-list">
        @foreach($blogs as $b)
        
            <div class="blog-card">
                <img src="{{ asset($b->photo) }}" alt="Blog 1">
                <div class="blog-info">
                    <h3 class="blog-title">{{ $b->title }}</h3>
                    <p class="blog-desc">{{ \Illuminate\Support\Str::limit($b->summary, 25) }}</p>
                    <a href="{{ route('front.blog.detail', $b->slug) }}" class="blog-btn">Xem chi tiết</a>
                </div>
            </div>
            
        @endforeach
    </div>
</section>
@endsection
