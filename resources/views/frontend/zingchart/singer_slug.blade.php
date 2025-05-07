@extends('frontend.layouts.master')
@section('content')
<div style="background: linear-gradient(135deg, #4e2062 0%, #2d1a4a 100%); padding-bottom: 40px;">
    <div style="max-width: 1100px; margin: 0 auto; padding: 40px 20px 0 20px;">
        <div style="display: flex; align-items: center;">
            <img src=" {{ $Singer->photo ?  asset($Singer->photo) : 'https://i.pinimg.com/736x/bc/43/98/bc439871417621836a0eeea768d60944.jpg' }} " alt="avatar" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid #fff;">
            <div style="margin-left: 30px;">
                <h1 style="color: #fff; font-size: 48px; font-weight: bold;">{{ $Singer->alias }}</h1>
            </div>
        </div>
    </div>
</div>



<div style="max-width: 100%;background: #181028; border-radius: 20px; padding: 40px 30px;">
    <h2 style="color: #fff; font-size: 24px; font-weight: bold;">Bài Hát Nổi Bật</h2>
    <div style="margin-top: 20px;">
        @foreach($Singer->song as $song)
          
                @php
                    $songUrl = str_replace(':8000/', '', $song->resourcesSong[0]->url);  
                @endphp
            <div style="display: flex; align-items: center; background: #232135; border-radius: 12px; padding: 12px 20px; margin-bottom: 16px;">
                <img src="{{ $Singer->photo ?  asset($Singer->photo) : 'https://i.pinimg.com/736x/bc/43/98/bc439871417621836a0eeea768d60944.jpg' }} "" style="width: 44px; height: 44px; border-radius: 8px; object-fit: cover;">
                <div style="margin-left: 16px; flex: 1;">
                    <div style="color: #fff; font-size: 17px; font-weight: bold; letter-spacing: 1px;">{{ $song->title }}</div>
                </div>
                <div style="color: #b3b3b3; font-size: 14px; margin-right: 24px;">{{ $song->created_at->diffForHumans() }}</div>
                <button style="background: linear-gradient(135deg, #a259ff 0%, #6c47ff 100%); border: none; 
                border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; 
                justify-content: center; margin-right: 10px; cursor: pointer;"  onclick="playSong('{{ asset($songUrl) }}',
                     '{{ $song->title }}', '{{ $song->singer->alias }}','{{ $song->singer->photo }}',{{ $song->id }})">
                    <svg width="18" height="18" fill="#fff" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                </button>
                <a 
                
                href="{{ route('front.song.share', [
                    'id' => $song->id,
                    'url' => $songUrl,
                    'ref' => request()->get('ref') // Lấy 'ref' từ request hiện tại,
                    
                ]) }}"
                style="background: #23213a; border: none; border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                <svg width="24" height="24" fill="#a259ff" viewBox="0 0 24 24"><path d="M18 8a3 3 0 1 0-2.83-4A3 3 0 0 0 18 8zm-12 8a3 3 0 1 0 2.83 4A3 3 0 0 0 6 16zm12 0a3 3 0 1 0 2.83 4A3 3 0 0 0 18 16zm-9.71-2.29a1 1 0 0 0 1.42 0l6-6a1 1 0 1 0-1.42-1.42l-6 6a1 1 0 0 0 0 1.42z"/></svg>
                </a>
            </div>
        @endforeach
     
    </div>
   
    <div style="max-width: 100%; margin: 0 auto; padding: 40px 0 0 0;">
        <h2 style="color: #fff; font-size: 26px; font-weight: bold; margin-bottom: 32px;">Bạn Có Thể Thích</h2>
        <div style="display: flex; justify-content: center; gap: 48px; flex-wrap: wrap;">
            @foreach($suggestedSingers as $singer)
                <a href="{{ route('front.zingsinger_slug', $singer->slug) }}" 
                    style="display: flex; flex-direction: column; align-items: center; width: 220px; text-decoration: none;">
                    <img src="{{ $singer->photo ? asset($singer->photo) : 'https://i.pinimg.com/736x/bc/43/98/bc439871417621836a0eeea768d60944.jpg' }}" style="width: 200px; height: 200px; border-radius: 50%; object-fit: cover; margin-bottom: 16px; border: 4px solid #2d1a4a;">
                    <div style="color: #fff; font-size: 20px; font-weight: bold; text-align: center;">{{ $singer->alias }}</div>
                    <!-- <div style="color: #b3b3b3; font-size: 15px; margin: 4px 0 12px 0; text-align: center;">{{ number_format($singer->followers) }} quan tâm</div> -->
                    {{-- <button style="background: #a259ff; color: #fff; border: none; border-radius: 20px; padding: 7px 28px; font-size: 15px; font-weight: 500; cursor: pointer;">QUAN TÂM</button> --}}
                </a>
                
            @endforeach
        </div>
    </div>
    <div style="max-width: 1200px; margin: 0 auto; margin-top: 40px; background: #181028; border-radius: 20px; padding: 40px 40px 32px 40px;">
        <h2 style="color: #fff; font-size: 28px; font-weight: bold; margin-bottom: 24px;">Về {{ $Singer->alias }}</h2>
        <div style="display: flex; gap: 48px; align-items: flex-start;">
            <img src="{{ $Singer->photo ? asset($Singer->photo) : 'https://i.pinimg.com/736x/bc/43/98/bc439871417621836a0eeea768d60944.jpg' }}"
                 alt="avatar"
                 style="width: 420px; height: 420px; border-radius: 16px; object-fit: cover; background: #fff;">
            <div style="flex: 1;">
                <div style="display: flex; align-items: center; gap: 48px;">
                    <div>
                        {{-- <div style="color: #fff; font-size: 32px; font-weight: bold;">2.569.851                        </div>
                        <div style="color: #b3b3b3; font-size: 16px;">Người quan tâm</div> --}}
                    </div>
                </div>
                <hr>
                <div>
                    <h2 style="color: #fff; font-size: 28px; font-weight: bold; margin-bottom: 24px;">Bài giới thiệu ngắn</h2>
                </div>
                <div style="color: #d1c9e6; font-size: 14px; line-height: 1.7; margin-bottom: 32px;">
                    {{ $Singer->content ?? 'Chưa có thông tin về ca sĩ này.' }}
                </div>
                
            </div>
            <hr>
           
        </div>
       
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
let youtubePlayer = document.getElementById('youtube-player');
let youtubeIframe = document.getElementById('youtube-iframe');
let closeVideoBtn = document.getElementById('close-video');
let youtubeBlurBg = document.getElementById('youtube-blur-bg');
let isPlaying = false;
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
        youtubePlayer.style.display = 'block';
        youtubeBlurBg.style.display = 'block';
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
closeVideoBtn.addEventListener('click', function() {
    youtubePlayer.style.display = 'none';
    youtubeBlurBg.style.display = 'none';
    youtubeIframe.src = '';
    isPlaying = false;
});
</script>

@endsection
