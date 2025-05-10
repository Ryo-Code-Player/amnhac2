@extends('frontend.layouts.master')
@section('content')
    <div class="container">
        <div class="content">
            <div class="box-container">
                <div class="box-left">
                <!-- start header -->
                    @include('frontend.layouts.slider')
                <!-- end header -->

                <!-- Featured Songs -->
                    <section class="featured" id="featured-section">
                        <div class="title_h2">
                            <h2>Bài hát nổi bật</h2>
                            <a href="/category" style="color: #000; font-size:12px; font-weight:bold;padding-top:16px; padding-right:20px; text-decoration: none">Xem thêm</a>
                            {{-- <span style="color: #000; font-size:12px; font-weight:bold;padding-top:16px; padding-right:20px"></span> --}}
                        </div>
                        <div class="song-list">
                            @foreach($song as $key => $s)
                                @php
                                    $songUrl = str_replace(':8000/', '', $s->resourcesSong[0]->url);  
                                @endphp
                                <div class="song-card" onclick="playSong('{{ asset($songUrl) }}',
                                '{{ $s->title }}', '{{ $s->user->full_name ?? null }}','{{ $s->user->photo??null }}',{{ $s->id }})">
                                   <img src="{{ isset($s->user->role)? ($s->user->role == 'admin' ? (asset($s->user->photo)):(asset('storage/'.$s->user->photo))): null }}" alt="noImage">
                                    <div>
                                        <h4 style="font-size: 14px;font-weight: 600;"> {{Str::limit($s->title, 15) }}</h4>
                                        <p>{{ $s->user->full_name ?? null }}</p>
                                    </div>
                                    <button><i class="fas fa-play"></i></button>
                                </div>
                            @endforeach
                        </div>
                        
                    </section>
                </div>
                <div class="box-right">
                    <!-- ZingChart -->
                    <section class="zingchart" id="zingchart-section" style="margin-bottom: 100px !important;">
                        <div class="song_newest" style="display:flex;justify-content:space-between;align-items:center;">
                            <h2>Ca khúc mới nhất</h2>
                            <a href="{{ route('front.zingchart') }}" style="color:#000000;text-decoration:none;font-size:16px;font-weight:600;">Xem thêm</a>
                        </div>
                        <ol>
                            @foreach($song as $key => $s)
                                @php
                                    $songUrl = str_replace(':8000/', '', $s->resourcesSong[0]->url);
                            
                                @endphp
                                <li onclick="playSong('{{ asset($songUrl) }}',
                                '{{ $s->title }}', '{{ $s->singer->alias ?? (auth()->user()->full_name ?? null) }}','{{ $s->singer->photo ?? ((isset(auth()->user()->photo)) ? 'storage/' . auth()->user()->photo : null ) }}',{{ $s->id }})">
                                    <span class="rank">{{ $key + 1 }}</span>
                                    <img src="{{ asset($s->singer->photo ?? ((isset(auth()->user()->photo)) ? 'storage/' . auth()->user()->photo : null )) }}" alt="">
                                    <span>{{ $s->title }}</span>
                                    <button><i class="fas fa-play"></i></button>
            
                                    @if(auth()->check())
                                    <a 
                                    
                                    href="{{ route('front.song.share', [
                                        'id' => $s->id,
                                        'url' => $songUrl,
                                        'ref' => request()->get('ref') // Lấy 'ref' từ request hiện tại,
                                        
                                    ]) }}"
                                    
                                    class="zc-share-btn" title="Chia sẻ" style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;background:none;border:none;cursor:pointer;padding:0;"
                                    onclick="event.stopPropagation();">
                                    <svg width="24" height="24" fill="#a259ff" viewBox="0 0 24 24"><path d="M18 8a3 3 0 1 0-2.83-4A3 3 0 0 0 18 8zm-12 8a3 3 0 1 0 2.83 4A3 3 0 0 0 6 16zm12 0a3 3 0 1 0 2.83 4A3 3 0 0 0 18 16zm-9.71-2.29a1 1 0 0 0 1.42 0l6-6a1 1 0 1 0-1.42-1.42l-6 6a1 1 0 0 0 0 1.42z"/></svg>
                                    </a>
                                    @endif
                                </li>
                                
                            @endforeach
                        
                        </ol>
                    
                    </section>
                </div>
            </div>
            
            <!-- Album Section (luôn hiển thị) -->
            <section class="album-section" id="album-section">
                <h2>Ca sĩ nổi bật</h2>                  
                    <div class="album-list" id="album-list">
                        @foreach($Singer as $s)
                        <a href="{{ route('front.zingsinger_slug', $s->slug) }}"  class="album-card" style="text-decoration:none;color:#fff;">
                            <img src="{{ asset($s->photo) }}" alt="{{ $s->alias }}">
                            <div class="album-info">
                                <div class="album-title">{{ $s->alias }}</div>
                                <div class="album-artist">{{ $s->alias }}</div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                
                <div style="display:flex;justify-content:center;margin-top:24px;">
                    <a href="{{ route('front.miquinn-singer') }}" class="btn-xem-them" style="text-decoration:none;color:#fff;"><i class="fas fa-redo"></i> XEM THÊM</a>
                </div>
            </section>
    
            <div class="box-container" style="border-top: 1px solid #e5e5e5">
                <div class="box-left">
                    <h2 style="font-size: 22px; text-transform:uppercase; color:#000"><a class="title_content" href="/category">Top 5 bảng xếp hạng</a> </h2>

                    <table class="zc-table">
                        <thead>
                            <tr>
                                <th style="padding-left:50px">CA SĨ</th>
                                <th>BÀI HÁT</th>
                                <th>PHÁT HÀNH</th>
                                <th>HÀNH ĐỘNG</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($song_rank as $key => $s)
                        
                                @php
                                    $songUrl = str_replace(':8000/', '', $s->resourcesSong[0]->url);  
                                @endphp
                                <tr style="height: 90px">
                                    <td style="display:flex;align-items:center;">
                                        <span style="vertical-align: middle; margin-right: 12px;">
                                            <svg width="24" height="24" fill="#a259ff" viewBox="0 0 24 24"><path d="M9 17.5A2.5 2.5 0 1 1 4 17.5a2.5 2.5 0 0 1 5 0zm10-2.5V6a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v9.5a4.5 4.5 0 1 0 2 0V8h8v7a4.5 4.5 0 1 0 2 0z"></path></svg>
                                        </span>
                                        <img class="zc-song-img"  height="50" width="50"  src="{{ asset($s->singer->photo ?? ((isset(auth()->user()->photo)) ? 'storage/' . auth()->user()->photo : null )) }}" alt="" />
                                    </td>
                                    <td><span class="zc-song-title">{{Str::limit($s->title, 25) }}</span><br><span style="color:#aaa;font-size:13px;">{{ $s['artist'] }}</span></td>
                                    <td style="font-size: 14px">{{ $s->created_at->diffForHumans() }}</td>
                                    <td>
                                        <button style="background:none;border:none;cursor:pointer;padding:0;margin-right:8px;" onclick="playSong('{{ asset($songUrl) }}',
                                        '{{ $s->title }}', '{{ $s->singer->alias ?? (auth()->user()->full_name ?? null) }}','{{ $s->singer->photo ?? ((isset(auth()->user()->photo)) ? 'storage/' . auth()->user()->photo : null ) }}',{{ $s->id }})">
                                            <svg width="28" height="28" fill="#fff" viewBox="0 0 24 24"><circle cx="12" cy="12" r="12" fill="#a259ff"/><polygon points="10,8 16,12 10,16" fill="#fff"/></svg>
                                        </button>
                                        <button style="background:none;border:none;cursor:pointer;padding:0;margin-right:8px;" onclick="openAddPlaylistPopup('{{ $s->id }}', '{{ $s->title }}', '{{ $s->singer->photo ?? ((isset(auth()->user()->photo)) ? 'storage/' . auth()->user()->photo : null ) }}')">
                                            <svg width="28" height="28" fill="#fff" viewBox="0 0 24 24"><circle cx="12" cy="12" r="12" fill="#44406a"/><line x1="12" y1="8" x2="12" y2="16" stroke="#fff" stroke-width="2" stroke-linecap="round"/><line x1="8" y1="12" x2="16" y2="12" stroke="#fff" stroke-width="2" stroke-linecap="round"/></svg>
                                        </button>
                                        @if(auth()->check())
                                        <a 
                                        
                                        href="{{ route('front.song.share', [
                                            'id' => $s->id,
                                            'url' => $songUrl,
                                            'ref' => request()->get('ref') // Lấy 'ref' từ request hiện tại,
                                            
                                        ]) }}"
                                        
                                        class="zc-share-btn" title="Chia sẻ" style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;background:none;border:none;cursor:pointer;padding:0;">
                                            <svg width="24" height="24" fill="#a259ff" viewBox="0 0 24 24"><path d="M18 8a3 3 0 1 0-2.83-4A3 3 0 0 0 18 8zm-12 8a3 3 0 1 0 2.83 4A3 3 0 0 0 6 16zm12 0a3 3 0 1 0 2.83 4A3 3 0 0 0 18 16zm-9.71-2.29a1 1 0 0 0 1.42 0l6-6a1 1 0 1 0-1.42-1.42l-6 6a1 1 0 0 0 0 1.42z"/></svg>
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach 
                        </tbody>
                    </table>

                    @if(auth()->check())
                        <div id="addPlaylistPopup" style="display:none;position:fixed;z-index:300;left:0;top:0;width:100vw;height:100vh;background:rgba(23,15,35,0.7);backdrop-filter:blur(2px);justify-content:center;align-items:center;">
                            <div style="background:#221f35;padding:32px 24px;border-radius:16px;min-width:320px;max-width:90vw;">
                                <button onclick="closeAddPlaylistPopup()" style="float:right;background:none;border:none;color:#fff;font-size:22px;cursor:pointer;">&times;</button>
                                <h3 style="color:#fff;margin-bottom:18px;">Thêm vào playlist của tôi</h3>
                                <div id="playlist-list">
                                    @php
                                        $playlist1 = App\Modules\Playlist\Models\Playlist::orderByDesc('order_id')->where('user_id', auth()->user()->id)->get();
                                    @endphp
                                    <input type="hidden" id="songId" value="">
                                    <ul style="list-style:none;padding:0;margin:0;">
                                        @forelse($playlist1 as $item)
                                            <li style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid #333;">
                                                <div style="display:flex;align-items:center;gap:12px;">
                                                    <img src="{{ asset($item->photo) }}" alt="{{ $item->title }}" style="width:38px;height:38px;border-radius:8px;object-fit:cover;">
                                                    <span style="color:#fff;font-size:1rem;">{{ $item->title }}</span>
                                                </div>
                                                <button onclick="addSongToPlaylist2({{ $item->id }})" style="background:#7c3aed;color:#fff;border:none;border-radius:50%;width:32px;height:32px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;cursor:pointer;">+</button>
                                            </li>
                                        @empty
                                            <li style="color:#aaa;">Bạn chưa có playlist nào.</li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="box-right">
                    <h2 style="color: #000; text-transform:uppercase;font-size:22px; padding-top: 5px"><a class="title_content" href="/category">Thể loại hot</a></h2>
                   @foreach($music_type as $key => $s)
                        <div class="image-container">
                            <img width="100%" height="135px" src="{{asset($s->photo)}}" alt="">
                            <div class="centered-text">{{$s->title}}</div>
                        </div>
                   @endforeach
                </div>

            </div>
    
            <!-- Blog/News Section -->
            <section class="blog-section" id="blog-section" style="margin-bottom: 30px !important;">
                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <h2><a class="title_content" href="/category">Bài viết nổi bật</a></h2>
                    <a href="{{ route('front.blog') }}" style="color:#fff;text-decoration:none;font-size:16px;font-weight:600;">Xem thêm</a>
                </div>
                <div class="blog-list">
                    @foreach($blog as $b)
                    
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
            {{-- PlayList --}}
            <div class="box-container" style="border-top: 1px solid #e5e5e5;">
                <div class="box-left">
                    <div><h2 style="font-size: 22px;color:#000; text-transform:uppercase"><a class="title_content" href="/playlist">PlayList</a></h2></div>
                    <div class="playlist-tab-content active" id="tab-all">
                        <div class="playlist-grid">
                            @foreach ($playlist as $key => $item)
                                @if($key == 0)
                                    <div class="first-playlist">
                                        <div class="playlist-card" style="width: 100%; margin-bottom:0px">
                                            <img class="playlist-cover" src="{{ asset($item->photo) }}" alt="cover">
                                            <div class="playlist-overlay">
                                                @if(auth()->check())
                                                @if($item->user_id == auth()->user()->id)
                                                    <a href="{{ route('front.playlist.delete', $item->id) }}" class="delete-btn" title="Xóa playlist">
                                                        <svg class="delete-icon" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <line x1="5" y1="5" x2="15" y2="15" stroke="white" stroke-width="2" stroke-linecap="round"/>
                                                            <line x1="15" y1="5" x2="5" y2="15" stroke="white" stroke-width="2" stroke-linecap="round"/>
                                                        </svg>
                                                    </a>
                                                    @endif
                                                @endif
                                                <a href="{{ route('front.playlist.slug', $item->slug) }}" class="play-btn">
                                                    <svg class="play-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <circle cx="12" cy="12" r="12" fill="none"/>
                                                        <polygon points="9,7 19,12 9,17" fill="white"/>
                                                    </svg>
                                                </a>
                                            </div>
                                            <div class="playlist-info">
                                                <div class="playlist-name">{{ \Illuminate\Support\Str::limit($item->title, 25) }}</div>
                                                <div class="playlist-author">{{ $item->user->full_name }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="playlist-row-3">
                                        <div class="playlist-card">
                                            <img class="playlist-cover" src="{{ asset($item->photo) }}" alt="cover">
                                            <div class="playlist-overlay">
                                                @if(auth()->check())
                                                @if($item->user_id == auth()->user()->id)
                                                    <a href="{{ route('front.playlist.delete', $item->id) }}" class="delete-btn" title="Xóa playlist">
                                                        <svg class="delete-icon" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <line x1="5" y1="5" x2="15" y2="15" stroke="white" stroke-width="2" stroke-linecap="round"/>
                                                            <line x1="15" y1="5" x2="5" y2="15" stroke="white" stroke-width="2" stroke-linecap="round"/>
                                                        </svg>
                                                    </a>
                                                    @endif
                                                @endif
                                                <a href="{{ route('front.playlist.slug', $item->slug) }}" class="play-btn">
                                                    <svg class="play-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <circle cx="12" cy="12" r="12" fill="none"/>
                                                        <polygon points="9,7 19,12 9,17" fill="white"/>
                                                    </svg>
                                                </a>
                                            </div>
                                            <div class="playlist-info">
                                                <div class="playlist-name">{{ \Illuminate\Support\Str::limit($item->title, 25) }}</div>
                                                <div class="playlist-author">{{ $item->user->full_name }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endif    
                            @endforeach
                            <!-- Thêm các playlist khác ở đây nếu muốn -->
                        </div>
                    </div>
                </div>
                {{-- fanclub --}}
                <div class="box-right">
                    <h2 style="font-size:22px; color:#000; text-transform:uppercase"><a class="title_content" href="/fanclub">Fanclub cộng đồng</a></h2>
                    <div class="fanclub-list" id="tab-all">
                        @foreach ($fanclubs as $fanclub)
                            <div class="fanclub-card" data-id="{{ $fanclub->id }}">
                            <img src="{{ $fanclub->photo }}" alt="Fanclub Image">
                            <div class="fanclub-info">
                                <h3 style="margin:0px"><a href="{{ route('front.fanclub.get',['slug'=>$fanclub->slug]) }}">{{ $fanclub->title }}</a></h3>
                                <p>Chủ fanclub: {{  $fanclub->user->full_name }}</p>
                                @if(auth()->check())
                                @if($fanclub->check_fanclub)  
                                    <button class="follow-btn followed" style="width:100%;">Đã quan tâm</button>
                                @else
                                    <button class="follow-btn" style="width:100%;">Quan tâm</button>
                                @endif
                                @endif
                                <div class="follow-count">{{  $fanclub->quantity }} lượt quan tâm</div>
                            </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
        <!-- Audio Player Controls -->
        <div id="audio-player-controls" style="position:fixed;bottom:0;left:0;width:100%;background:#222;padding:16px;z-index:9999;display:none;">
            <div style="display:flex;align-items:center;">
                <img id="player-singer-photo" src="" alt="" style="width:48px;height:48px;border-radius:50%;object-fit:cover;margin-right:16px;">
                <div style="flex:1;">
                    <div id="player-song-title" style="color:#fff;font-weight:bold;"></div>
                    <div id="player-singer-alias" style="color:#ccc;"></div>
                </div>
                <button id="play-pause-btn" style="margin-left:16px;font-size:24px;background:none;border:none;color:#fff;">
                    <i class="fas fa-pause"></i>
                </button>
            </div>
        </div>
        <!-- YouTube Video Player Blur Background -->
        <div id="youtube-blur-bg"></div>
        <!-- YouTube Video Player -->
        <div id="youtube-player">
            <div style="position:relative;width:100%;height:100%;">
                <button id="close-video" style="position:absolute;top:10px;right:10px;background:rgba(0,0,0,0.5);color:#fff;border:none;border-radius:50%;width:30px;height:30px;cursor:pointer;">×</button>
                <iframe id="youtube-iframe" width="100%" height="100%" frameborder="0" allowfullscreen></iframe>
            </div>
        </div>
    </div>
    <style>
        #youtube-blur-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(12px);
            z-index: 9999;
            display: none;
            transition: opacity 0.3s;
        }
        #youtube-player {
            position: fixed;
            top: 50%;
            left: 50%;
            width: 90vw;
            max-width: 640px;
            height: 50vw;
            max-height: 360px;
            background: #000;
            z-index: 10000;
            display: none;
            transform: translate(-50%, -50%);
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.5);
            padding: 0;
        }
        #youtube-iframe {
            width: 100%;
            height: 100%;
            border-radius: 12px;
            display: block;
        }
        #close-video {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(0,0,0,0.5);
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            cursor: pointer;
            z-index: 2;
        }
        .box-container {
            display:flex;
            border-bottom: 1px solid #e5e5e5

        }
        .box-left{
            width: 58%;
            margin-right: 32px;
        }
        .box-right{
            width:40%
        }
        .zingchart h2{
            margin-top: 0px;
        }
        .image-container {
            position: relative;
            width: 100%;
            margin-bottom: 16px
        }

        .image-container img {
            display: block;
            /* width: 100%; */
            /* height: auto; */
             transition: transform 0.5s ease, opacity 0.5s ease;
        }

        .centered-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white; /* màu chữ */
            font-size: 32px;
            font-weight: bold;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 1); /* bóng đậm hơn */
            transition: transform 0.5s ease;
        }
        .image-container img {
             opacity: 0.6; /* ảnh mờ đi */
             cursor: pointer;

        }
        /* Hiệu ứng khi hover vào container */
        .image-container:hover img {
            transform: scale(1.1); /* ảnh phóng to nhẹ */
            opacity: 0.6;          /* ảnh đậm hơn chút */
        }

        .image-container:hover .centered-text {
            transform: translate(-50%, -50%) scale(1.1); /* chữ phóng to nhẹ */
        }
        .zc-tabs {
        display: flex;
        gap: 16px;
        margin-bottom: 32px;
    }
    .zc-tab {
        background: #2d2d44;
        color: #fff;
        border: none;
        padding: 10px 28px;
        border-radius: 20px;
        cursor: pointer;
        font-weight: 500;
        font-size: 16px;
        transition: background 0.2s;
    }
    .zc-tab.active, .zc-tab:hover {
        background: #a259ff;
        color: #fff;
    }
    .zc-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        color: #000
    }
    .zc-table th, .zc-table td {
        padding: 8px 12px;
        text-align: left;
    }
    .zc-table th {
        color: #a0a0c0;
        font-size: 14px;
        font-weight: 600;
        border-bottom: 1px solid #33334d;
    }
    .zc-table tr {
        border-bottom: 1px solid #23234a;
    }
    .zc-table tr:last-child {
        border-bottom: none;
    }
    .zc-song-img {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        object-fit: cover;
        margin-right: 12px;
    }
    .zc-song-title {
        font-weight: 600;
        font-size: 16px;
    }
    .zc-premium {
        background: #ffb800;
        color: #181828;
        font-size: 12px;
        font-weight: bold;
        border-radius: 6px;
        padding: 2px 8px;
        margin-left: 8px;
    }
    .zc-chartjs-container {
        background: #181828;
        border-radius: 18px;
        padding: 32px 24px 24px 24px;
        margin-bottom: 32px;
        box-shadow: 0 2px 16px 0 rgba(0,0,0,0.12);
        width: 100%;
        max-width: 1050px;
        margin-left: auto;
        margin-right: auto;
    }
    .zc-chartjs-title {
        color: #fff;
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 18px;
    }
    .zc-table td {
        width: 139px ;
    }  
    
    .playlist-container {
    padding: 32px 48px;
    background: #181526;
    min-height: 100vh;
    max-width: 1000px;
    margin-left: auto;
    margin-right: auto;
    margin-top: 16px;
    margin-bottom: 32px;
    border-radius: 16px;
    border: 1px solid white; 
}
.playlist-title {
    color: #fff;
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 16px;
}
.playlist-tabs {
    display: flex;
    gap: 32px;
    margin-bottom: 32px;
}
.playlist-tab {
    color: #fff;
    font-size: 1.1rem;
    padding-bottom: 4px;
    border-bottom: 2px solid transparent;
    cursor: pointer;
    transition: border 0.2s;
}
.playlist-tab.active {
    border-bottom: 2px solid #a259ff;
    color: #a259ff;
}
.playlist-grid {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
}
.playlist-card, .playlist-create {
    width: 175px;
    height: 280px;
    background: #fff;
    border-radius: 16px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    margin-bottom: 24px;
    position: relative;
    overflow: hidden;
}
.playlist-card{
    width: 175px;
    height: 220px;
}
.playlist-create {
    color: #fff;
    font-size: 1.1rem;
    cursor: pointer;
    border: 2px dashed #444;
    transition: border 0.2s;
}
.playlist-create:hover {
    border: 2px solid #a259ff;
}
.playlist-create .plus {
    font-size: 2.5rem;
    margin-bottom: 12px;
    color: #a259ff;
}
.playlist-cover {
    width: 100%;
    height: 160px;
    border-radius: 12px 12px 0 0;
    object-fit: cover;
}
.playlist-info {
    padding: 16px 12px 0 12px;
    width: 100%;
}
.playlist-name {
    color: #000;
    font-size: 1.1rem;
    font-weight: 500;
    margin-bottom: 4px;
    margin-left:10px;
}
.playlist-author {
    color: #464444;;
    margin-left:10px;
    font-size: 0.95rem;
    margin-bottom: 16px
}
.playlist-card .playlist-overlay {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(24, 21, 38, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.2s;
    z-index: 2;
}
.playlist-card:hover .playlist-overlay {
    opacity: 1;
}
.playlist-overlay .play-btn {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: rgba(255,255,255,0.15);
    border: 2px solid #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.2s;
    position: relative;
}
.playlist-overlay .play-btn:hover {
    background: #a259ff;
    border-color: #a259ff;
}
.playlist-overlay .play-icon {
    width: 24px;
    height: 24px;
    display: block;
    color: #fff;
    margin-left: 3px;
}
.playlist-overlay .delete-btn {
    position: absolute;
    top: 16px;
    left: 16px;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: rgba(255,255,255,0.12);
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.2s;
    z-index: 3;
}
.playlist-overlay .delete-btn:hover {
    background: #ff4d4f;
}
.playlist-overlay .delete-icon {
    width: 18px;
    height: 18px;
    color: #fff;
    pointer-events: none;
}
.playlist-tab-content { display: none; }
.playlist-tab-content.active { display: block; }
/* Popup CSS */
.modal-overlay {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(24, 21, 38, 0.85);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}
.modal-content {
    background: #221f35;
    border-radius: 16px;
    padding: 32px 32px 24px 32px;
    padding-right:50px;
    min-width: 350px;
    max-width: 90vw;
    box-shadow: 0 2px 16px rgba(0,0,0,0.25);
    position: relative;
    color: #fff;
}
.close-btn {
    position: absolute;
    top: 16px;
    right: 16px;
    background: none;
    border: none;
    color: #fff;
    font-size: 2rem;
    cursor: pointer;
}
.modal-title {
    font-size: 1.5rem;
    font-weight: bold;
    margin-bottom: 24px;
    text-align: center;
}
.form-group {
    margin-bottom: 18px;
}
.form-group label {
    display: block;
    margin-bottom: 6px;
    color: #fff;
    font-size: 1rem;
}
.form-group input[type="text"],
.form-group input[type="file"],
.form-group select {
    width: 100%;
    padding: 10px 12px;
    border-radius: 8px;
    border: none;
    background: #181526;
    color: #fff;
    font-size: 1rem;
    margin-bottom: 4px;
}
.submit-btn {
    width: 100%;
    padding: 12px 0;
    background: #a259ff;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: bold;
    cursor: pointer;
    margin-top: 10px;
    transition: background 0.2s;
}
.submit-btn:hover {
    background: #7c3aed;
}
.first-playlist{
    width: 100%;
}
.box-left-playlist{
    width: 70%;
}
/* fanclub */
.fanclub-list {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
}
.fanclub-card {
  background: #fff;
  border-radius: 16px;
  width: 100%;
  padding: 16px;
  display: flex;
  align-items: center;
  box-shadow: 0 2px 8px rgba(0,0,0,0.2);
  transition: box-shadow 0.25s, transform 0.18s, border 0.18s, background 0.18s;
  border: 2px solid transparent;
  position: relative;
  cursor: pointer;
}
.fanclub-card:hover {
  box-shadow: 0 8px 32px #a259ff55, 0 2px 8px #0003;
  transform: translateY(-6px) scale(1.04);
  border: 2px solid #a259ff;
  background: #cecee2;
  z-index: 2;
}
.fanclub-card img {
  width: 70px;
  height: 70px;
  border-radius: 12px;
  object-fit: cover;
  margin-bottom: 15px;
}
.fanclub-info h3 a{
  margin: 0 0 8px 0;
  font-size: 1.1rem;
  color: #000;
  text-decoration: none;
}
.fanclub-info p {
  margin: 0 0 12px 0;
  font-size: 0.95rem;
  color: #464444;
}
.fanclub-info{
    margin-left: 32px;
}
.follow-btn {
  background: #a259ff;
  color: #fff;
  border: none;
  border-radius: 8px;
  padding: 8px 18px;
  cursor: pointer;
  font-size: 1rem;
  transition: background 0.2s, box-shadow 0.2s, color 0.2s;
  box-shadow: 0 2px 8px #a259ff33;
}
.follow-btn:hover:not(.followed) {
  background: #fff;
  color: #a259ff;
  box-shadow: 0 4px 16px #a259ff55;
}
.follow-btn.followed {
  background: #444;
  color: #a259ff;
  cursor: default;
}
.plus-icon {
  font-size: 2.5rem;
  margin-bottom: 12px;
  color: #a259ff;
}
.create-text {
  font-size: 1.15rem;
  color: #a259ff;
  text-align: center;
}
.follow-count {
  margin-top: 8px;
  font-size: 0.95rem;
  color: #a259ff;
  font-weight: 500;
  text-align: center;
}
.modal-fanclub {
  position: fixed;
  z-index: 1000;
  left: 0; top: 0; right: 0; bottom: 0;
  background: rgba(0,0,0,0.5);
  display: flex;
  align-items: center;
  justify-content: center;
}
.modal-content {
  background: #23234a;
  color: #fff;
  padding: 32px 28px 24px 28px;
  border-radius: 16px;
  min-width: 350px;
  max-width: 95vw;
  box-shadow: 0 4px 32px #0005;
  position: relative;
}
.close-modal {
  position: absolute;
  right: 18px;
  top: 12px;
  font-size: 2rem;
  color: #a259ff;
  cursor: pointer;
}

.delete-btn {
  background: #ff4d6d;
  color: #fff;
  border: none;
  border-radius: 8px;
  padding: 8px 18px;
  cursor: pointer;
  font-size: 1rem;
  margin-top: 10px;
  transition: background 0.2s, box-shadow 0.2s, color 0.2s;
  box-shadow: 0 2px 8px #ff4d6d33;
}
.delete-btn:hover {
  background: #d90429;
  color: #fff;
}
.title_h2{
    display: flex;
    justify-content: space-between;
    align-items: center; /* căn giữa theo chiều dọc */
}
.title_h2 a:hover{
    color: red !important;
}
.song_newest a:hover{
    color: red !important;
}
.title_content{
    color: #000;
    text-decoration: none;
}
.title_content:hover{
     color: red !important;
}
</style>
    <script>
    let playerControls = document.getElementById('audio-player-controls');
    let playPauseBtn = document.getElementById('play-pause-btn');
    let playerSongTitle = document.getElementById('player-song-title');
    let playerSingerAlias = document.getElementById('player-singer-alias');
    let playerSingerPhoto = document.getElementById('player-singer-photo');
    let youtubePlayer = document.getElementById('youtube-player');
    let youtubeIframe = document.getElementById('youtube-iframe');
    let closeVideoBtn = document.getElementById('close-video');
    let isPlaying = false;
    let youtubeBlurBg = document.getElementById('youtube-blur-bg');

    function playSong(url, title, singer, photo,id = null) {
    
        // Extract YouTube video ID from URL
        let videoId = '';
        if (url.includes('youtube.com/watch?v=')) {
            videoId = url.split('v=')[1].split('&')[0];
        } else if (url.includes('youtu.be/')) {
            videoId = url.split('youtu.be/')[1].split('?')[0];
        }

        if (videoId) {
            youtubeIframe.src = `https://www.youtube.com/embed/${videoId}?autoplay=1`;
            playerSongTitle.textContent = title;
            playerSingerAlias.textContent = singer;
            playerSingerPhoto.src = photo;
            youtubePlayer.style.display = 'block';
            youtubeBlurBg.style.display = 'block';
            playerControls.style.display = 'block';
            isPlaying = true;
        }
        if (id) {
            fetch(`{{ route('front.song.view') }}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    song_id: id,
                    viewed_at: new Date().toISOString()
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('View updated', data);
            })
            .catch(error => {
                console.error('Error updating view:', error);
            });
        }

    }

    // Close video player
    closeVideoBtn.addEventListener('click', function() {
        youtubePlayer.style.display = 'none';
        youtubeBlurBg.style.display = 'none';
        youtubeIframe.src = '';
        playerControls.style.display = 'none';
        isPlaying = false;
    });

    // Play/Pause
    playPauseBtn.addEventListener('click', function() {
        if (isPlaying) {
            youtubePlayer.style.display = 'none';
            youtubeBlurBg.style.display = 'none';
            youtubeIframe.src = '';
            playerControls.style.display = 'none';
            isPlaying = false;
        } else {
            youtubePlayer.style.display = 'block';
            youtubeBlurBg.style.display = 'block';
            playerControls.style.display = 'block';
            isPlaying = true;
        }
    });

    // Thêm hàm chia sẻ
    function openAddPlaylistPopup(songId, songTitle, songImg) {
 
    document.getElementById('addPlaylistPopup').style.display = 'flex';
    
   
    $('#songId').val(songId);
}
function closeAddPlaylistPopup() {
    document.getElementById('addPlaylistPopup').style.display = 'none';
}

function addSongToPlaylist2(playlistId) {
    const songId = document.getElementById('songId').value;
    if (!songId) { Notiflix.Notify.failure('Không xác định được bài hát!'); return; }
    $.ajax({
        url: '/playlist/' + playlistId + '/add-song',
        type: 'POST',
        data: { 
            song_id: songId,
            _token: '{{ csrf_token() }}',
            playlistId: playlistId
        },
        success: function(response) {
            console.log(response);
            if (response.success) {
                Notiflix.Notify.success('Đã thêm vào playlist!');
                addToPlaylistModal.style.display = 'none';
                document.getElementById('addPlaylistPopup').style.display = 'none';

            } else {
                Notiflix.Notify.failure('Lỗi: ' + response.message);
            }
        },
        error: function(xhr, status, error) {
            alert('Lỗi: ' + error);
        }
    })
}

//fanclub
function showTab(tab) {
  document.getElementById('tab-all').style.display = tab === 'all' ? 'flex' : 'none';
  document.getElementById('tab-mine').style.display = tab === 'mine' ? 'flex' : 'none';
  document.getElementById('tab-followed').style.display = tab === 'followed' ? 'flex' : 'none';
  document.querySelectorAll('.tab').forEach(btn => btn.classList.remove('active'));
  if(tab === 'all') document.querySelector('.tab:nth-child(1)').classList.add('active');
  if(tab === 'mine') document.querySelector('.tab:nth-child(2)').classList.add('active');
  if(tab === 'followed') document.querySelector('.tab:nth-child(3)').classList.add('active');
}
document.querySelectorAll('.create-fanclub').forEach(card => {
  card.onclick = function() {
    document.getElementById('modal-create-fanclub').style.display = 'flex';
  }
});
function closeModal() {
  document.getElementById('modal-create-fanclub').style.display = 'none';
}
document.querySelector('.form-create-fanclub')?.addEventListener('submit', async function(e) {
  e.preventDefault();
  const form = e.target;
  const formData = new FormData(form);
  try {
    const response = await fetch('{{ route('front.fanclub.store') }}', {
      method: 'POST',
      body: formData,
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
      }
    });
    if (response.ok) {
      closeModal();
        Notiflix.Notify.success('Tạo fanclub thành công!');
        setTimeout(() => {
          window.location.reload();
        }, 1000);
    } else {
      const data = await response.json();
        Notiflix.Notify.failure('Tạo fanclub thất bại! ' + (data.message || ''));
    }
  } catch (err) {
    Notiflix.Notify.failure('Có lỗi xảy ra khi gửi dữ liệu!');
  }
});
document.addEventListener('click', async function(e) {
  if (e.target.classList.contains('follow-btn')) {
    const card = e.target.closest('.fanclub-card');
    const fanclubId = card.getAttribute('data-id');
    if (!fanclubId) {
      Notiflix.Notify.failure('Không tìm thấy fanclub!');
      return;
    }
    // Nếu là nút Đã quan tâm (unfollow)
    if (e.target.classList.contains('followed')) {
      Notiflix.Confirm.show(
        'Xác nhận',
        'Bạn có chắc muốn bỏ quan tâm fanclub này?',
        'Đồng ý',
        'Hủy',
        async () => {
          try {
            const response = await fetch('{{ route('front.fanclub.follow') }}', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
              },
              body: JSON.stringify({ fanclub_id: fanclubId })
            });
            if (response.ok) {
              Notiflix.Notify.success('Đã bỏ quan tâm!');
              setTimeout(() => window.location.reload(), 1000);
            } else {
              const data = await response.json();
              Notiflix.Notify.failure(data.message || 'Bỏ quan tâm thất bại!');
            }
          } catch (err) {
            Notiflix.Notify.failure('Có lỗi xảy ra!');
          }
        },
        () => {
          // Không làm gì khi bấm Hủy
        }
      );
      return;
    }
    // Nếu là nút Quan tâm (follow)
    try {
      const response = await fetch('{{ route('front.fanclub.follow') }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ fanclub_id: fanclubId })
      });
      if (response.ok) {
        Notiflix.Notify.success('Thao tác thành công!');
        e.target.classList.add('followed');
        e.target.textContent = 'Đã quan tâm';
        // Tăng số lượt quan tâm trên giao diện
        const countDiv = card.querySelector('.follow-count');
        if(countDiv) {
          let text = countDiv.textContent.trim();
          let num = parseInt(text.replace(/\D/g, ''));
          if (!isNaN(num)) {
            num++;
            countDiv.textContent = num + ' lượt quan tâm';
          }
        }
        setTimeout(() => window.location.reload(), 1000);
      } else {
        const data = await response.json();
        Notiflix.Notify.failure(data.message || 'Quan tâm thất bại!');
        setTimeout(() => window.location.reload(), 1000);
      }
    } catch (err) {
      Notiflix.Notify.failure('Có lỗi xảy ra!');
      setTimeout(() => window.location.reload(), 1000);
    }
  }
  // Xử lý nút xóa fanclub
  if (e.target.classList.contains('delete-btn')) {
    const card = e.target.closest('.fanclub-card');
    const fanclubId = card.getAttribute('data-id');
    if (!fanclubId) {
      Notiflix.Notify.failure('Không tìm thấy fanclub!');
      return;
    }
    // Truyền data-id xuống, ví dụ hiển thị confirm
    Notiflix.Confirm.show(
      'Xác nhận',
      'Bạn có chắc muốn xóa fanclub này?',
      'Xóa',
      'Hủy',
      async () => {
        // Gọi API xóa fanclub
        try {
          const response = await fetch(`{{ url('/fanclub-delete') }}/${fanclubId}`, {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
              'X-Requested-With': 'XMLHttpRequest',
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({})
          });
          if (response.ok) {
            Notiflix.Notify.success('Đã xóa fanclub thành công!');
            setTimeout(() => window.location.reload(), 1000);
          } else {
            const data = await response.json();
            Notiflix.Notify.failure(data.message || 'Xóa fanclub thất bại!');
          }
        } catch (err) {
          Notiflix.Notify.failure('Có lỗi xảy ra khi xóa!');
        }
      },
      () => {
        // Không làm gì khi bấm Hủy
      }
    );
  }
});
    </script>
@endsection
