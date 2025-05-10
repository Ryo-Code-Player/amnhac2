@extends('frontend.layouts.master')
@section('content')
    <div class="container">
        <div class="content">
            <!-- start header -->
                {{-- @include('frontend.layouts.slider') --}}
            <!-- end header -->
            
            <!-- Featured Songs -->
            <section class="featured" id="featured-section">
                <h2>Bài hát nổi bật</h2>
                <div class="song-list">
                    @foreach($song as $key => $s)
                     
                        @php
                            $songUrl = str_replace(':8000/', '', $s->resourcesSong[0]->url);  
                        @endphp
                        <div class="song-card" onclick="playSong('{{ asset($songUrl) }}',
                         '{{ $s->title }}', '{{ $s->singer->alias ?? (auth()->user()->full_name ?? null) }}','{{ $s->singer->photo ?? ((isset(auth()->user()->photo)) ? 'storage/' . auth()->user()->photo : null ) }}',{{ $s->id }})">
                            <img src="{{ asset($s->singer->photo ?? ((isset(auth()->user()->photo)) ? 'storage/' . auth()->user()->photo : null )) }}" alt="">
                            <div>
                                <h4 style="font-size: 14px;font-weight: 600;"> {{Str::limit($s->title, 15) }}</h4>
                                <p>{{ $s->singer->alias ?? (auth()->user()->full_name ?? null) }}</p>
                            </div>
                            <button><i class="fas fa-play"></i></button>
                        </div>
                    @endforeach
                </div>
                
            </section>
    
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
    
            <!-- ZingChart -->
            <section class="zingchart" id="zingchart-section" style="margin-bottom: 100px !important;">
                <div style="display:flex;justify-content:space-between;align-items:center;">
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
    
            <!-- Blog/News Section -->
            <section class="blog-section" id="blog-section" style="margin-bottom: 100px !important;">
                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <h2>Bài viết nổi bật</h2>
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

    </script>
@endsection
