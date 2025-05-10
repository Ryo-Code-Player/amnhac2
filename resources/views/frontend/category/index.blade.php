@extends('frontend.layouts.master')
@section('content')

<style>
.category li {
    display: flex;
    align-items: center;
    background: #fff;
    padding: 16px;
    gap: 22px;
    cursor: pointer;
    transition: background 0.2s;
    position: relative;
    height: 45px;
    color: #000000;
    border-top: 1px solid #e5e5e5;
}
.category img {
    width: 50px;
    height: 50px;
    border-radius: 6px;
    object-fit: cover;
}
.category h2{
    font-size: 22px;
    color: #52c2c2;
    text-transform: uppercase;
    margin-bottom: -12px;
}
.category ol {
    padding-left: 0px
}
.category ol li{
    padding-left: 0px
}
.category button {
    position: absolute;
    right: 16px;
    background: #7200a1;
    border: none;
    color: #fff;
    border-radius: 50%;
    width: 32px;
    height: 32px;
    font-size: 14px;
    cursor: pointer;
    transition: background 0.2s;
}
.box-category{
    margin-bottom: 40px
}
.box-left{
    width: 45%;
    margin-right: 64px;
}
.box-right{
    width: 48%;
}
.blog-card {
    background: #231b2e;
    border-radius: 16px;
    overflow: hidden;
    width: 80%;
    min-width: 240px;
    max-width: 100%;
    box-shadow: 0 4px 16px 0 rgba(72, 0, 128, 0.08);
    display: flex;
    flex-direction: column;
    transition: transform 0.15s, box-shadow 0.15s;
}
.blog-card img {
    width: 100%;
    height: 160px;
    object-fit: cover;
    border-radius: 16px 16px 0 0;
}
blog-info {
    padding: 18px 16px 16px 16px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}
</style>
<div class="container">
    <div class="box-left" style="display:block">
        <h2 style="color:#000000;border-bottom:1px solid grey; margin-bottom:32px">THỂ LOẠI BÀI HÁT</h2>
        @foreach ($category as $cat)
            @if ($cat->song->isNotEmpty())
                <div class="box-category">
                    <div class="category">
                        <h2>{{$cat->title}}</h2>
                        <div>
                            <ol>
                                @foreach($cat->song as $key => $s)
                                    @php
                                        $songUrl = str_replace(':8000/', '', $s->resourcesSong[0]->url);
                                
                                    @endphp
                                    <li onclick="playSong('{{ asset($songUrl) }}',
                                    '{{ $s->title }}', '{{ $s->singer->alias ?? (auth()->user()->full_name ?? null) }}','{{ $s->singer->photo ?? ((isset(auth()->user()->photo)) ? 'storage/' . auth()->user()->photo : null ) }}',{{ $s->id }})">
                                        <img src="{{ asset($s->singer->photo ?? ((isset(auth()->user()->photo)) ? 'storage/' . auth()->user()->photo : null )) }}" alt="">
                                        <span>{{ $s->title }}</span>
                                        <button><i class="fas fa-play"></i></button>
                
                                    </li>
                                    
                                @endforeach
                            
                            </ol>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
    <div class="box-right">
        <h2 style="color:#000000;border-bottom:1px solid grey; margin-bottom:32px">DANH MỤC BLOG</h2>
        <div style="margin-left:32px">
            @foreach($blog_cate as $key => $c)
                <h3 style="color:#52c2c2;">{{$c->title}}</h3>
                <?php $blogs = DB::table('blogs')->where('cat_id',$c->id)->orderBy('updated_at','desc')->get(); ?> 
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
            @endforeach
        </div>
    </div>    

</div>



@endsection
