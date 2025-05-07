<style>
    .sidebar nav h4{
        margin: 0 0 8px 32px;
        font-size: 12px;
        color: #a0a0a0;
        letter-spacing: 1px;
    }
</style>

<aside class="sidebar">
    <div class="logo"><img src="{{ asset('images/Miquinn.png') }}" alt="Logo"></div>
    <nav>
    <h4>Khám phá</h4>
        <ul>
            <a href="/" style="text-decoration:none;color:#fff;"><li class="{{ request()->is('/') ? 'active' : '' }}"><i class="fas fa-home"></i> Trang Chủ</li></a>
            <a href="/zingchart" style="text-decoration:none;color:#fff;"><li class="{{ request()->is('zingchart') ? 'active' : '' }}"><i class="fas fa-chart-line"></i> Bảng xếp hạng</li></a>
            {{-- <li><i class="fas fa-music"></i> <a href="/thuvien" style="text-decoration:none;color:#fff;">Thư Viện</a></li>
            <li><i class="fas fa-star"></i> <a href="/top100" style="text-decoration:none;color:#fff;">Top 100</a></li> --}}
        </ul>
    </nav>
    <div class="library">
        <ul>
    @if(auth()->check())
        <h4>CHỦ ĐỀ</h4>
        <a href="{{ route('front.blog.index',['id' => auth()->user()->id]) }}" style="text-decoration:none;color:#fff;"><li class="{{ request()->is('blog') ? 'active' : '' }}"><i class="fa-solid fa-blog"></i> Cộng đồng</li></a>
            @endif
            <h4 style="margin-top: 10px;">THƯ VIỆN</h4>
            <a href="{{ route('front.playlist') }}" style="text-decoration:none;color:#fff;"><li class="{{ request()->is('playlist') ? 'active' : '' }}" ><i class="fas fa-list"></i> Playlist</li></a>

            <a href="{{ route('front.fanclub') }}" style="text-decoration:none;color:#fff;"><li class="{{ request()->is('fanclub') ? 'active' : '' }}" ><i class="fas fa-compact-disc"></i> Fanclub</li></a>
           
        </ul>
    </div>
 
</aside>