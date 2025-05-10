<style>
    .menu_header {
      margin: 0;
      font-family: Arial, sans-serif;
    }
    .tabs_menu {
      
      color: white;
      padding: 10px 20px;
      display: flex;
      align-items: center;
      /* justify-content: space-between; */
    }
    .logo {
      font-size: 24px;
      font-weight: bold;
    }
    nav ul {
      list-style: none;
      margin: 0;
      padding: 0;
      display: flex;
    }
    nav ul li {
      margin-left: 15px;
    }
    nav ul li a {
      color: white;
      text-decoration: none;
      font-size: 16px;
      transition: color 0.3s;
    }
    nav ul li a:hover {
      color: #ff4081!important;
    }
    nav ul li.active {
      color: #ff4081!important;
    }
    nav ul li.active a{
      color: #ff4081!important;
    }
</style>

<div class="menu_header">
    <div class="tabs_menu">
        <div class="logo"><img src="{{ asset('images/Miquinn.png') }}" alt="Logo" height="100px"></div>
      <nav>
        <ul>
            <li><a href="/" style="text-decoration:none;"><li class="{{ request()->is('/') ? 'active' : '' }}"><i class="fas fa-home"></i> Trang Chủ</li></a></li>
            <li><a href="/zingchart" style="text-decoration:none;color:#fff;"><li class="{{ request()->is('zingchart') ? 'active' : '' }}"><i class="fas fa-chart-line"></i> Bảng xếp hạng</li></a></li>
            @if(auth()->check())
                <li><a href="{{ route('front.blog.index',['id' => auth()->user()->id]) }}" style="text-decoration:none;color:#fff;"><li class="{{ request()->is('blog') ? 'active' : '' }}"><i class="fa-solid fa-blog"></i> Cộng đồng</li></a></li>
            @endif
            <li><a href="{{ route('front.playlist') }}" style="text-decoration:none;color:#fff;"><li class="{{ request()->is('playlist') ? 'active' : '' }}" ><i class="fas fa-list"></i> Playlist</li></a></li>
            <li><a href="{{ route('front.fanclub') }}" style="text-decoration:none;color:#fff;"><li class="{{ request()->is('fanclub') ? 'active' : '' }}" ><i class="fas fa-compact-disc"></i> Fanclub</li></a></li>
        </ul>
      </nav>
    </div>
</div>