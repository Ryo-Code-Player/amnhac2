<!doctype html>
<html class="no-js" lang="en">
@include('frontend.layouts.head')

<body data-mobile-nav-style="full-screen-menu" data-mobile-nav-bg-color="#353642" class="custom-cursor overflow-x-hidden">
  
    <!-- start cursor -->
    <div class="cursor-page-inner">
        <div class="circle-cursor circle-cursor-inner"></div>
        <div class="circle-cursor circle-cursor-outer"></div>
    </div>
    <!-- end cursor -->

    <!-- start header -->
    @include('frontend.layouts.header')
    <!-- end header -->


    <!-- start section -->
    @include('frontend.layouts.section')

    @include('frontend.layouts.footer')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
</body>


<script>
    // Kiểm tra và hiển thị thông báo
    @if(session('error'))
        if (typeof Notiflix !== 'undefined') {
            Notiflix.Notify.failure('{{ session('error') }}');
        }
    @endif
    @if(session('success'))
        if (typeof Notiflix !== 'undefined') {
            Notiflix.Notify.success('{{ session('success') }}');
        }
    @endif

    const songs = @json($songs ?? []);
    let currentSong = 0;
    const audio = document.getElementById('audio');
    const playerThumb = document.getElementById('player-thumb');
    const playerTitle = document.getElementById('player-title');
    const playerArtist = document.getElementById('player-artist');
    const playBtn = document.getElementById('play-btn');
    const progress = document.getElementById('progress');
    const currentTime = document.getElementById('current-time');
    const duration = document.getElementById('duration');
    const volume = document.getElementById('volume');
    let isRepeat = false;
    const repeatBtn = document.getElementById('repeat-btn');

    function loadSong(index) {
        const song = songs[index];
    
        playBtn.innerHTML = '<i class="fas fa-play"></i>';
        progress.value = 0;
        currentTime.textContent = "00:00";
        duration.textContent = "00:00";
    }

  

    function togglePlay() {
        if (audio.paused) {
            audio.play();
            playBtn.innerHTML = '<i class="fas fa-pause"></i>';
        } else {
            audio.pause();
            playBtn.innerHTML = '<i class="fas fa-play"></i>';
        }
    }

    function prevSong() {
        currentSong = (currentSong - 1 + songs.length) % songs.length;
        
        playSong(songs[currentSong].src, songs[currentSong].title, songs[currentSong].artist, songs[currentSong].thumb);
    }

    function nextSong() {
        currentSong = (currentSong + 1) % songs.length;
       
        playSong(songs[currentSong].src, songs[currentSong].title, songs[currentSong].artist, songs[currentSong].thumb);
    }
    audio.addEventListener('timeupdate', () => {
        if (audio.duration) {
            progress.value = (audio.currentTime / audio.duration) * 100;
            currentTime.textContent = formatTime(audio.currentTime);
            duration.textContent = formatTime(audio.duration);
        }
    });
    progress.addEventListener('input', () => {
        audio.currentTime = (progress.value / 100) * audio.duration;
    });
    audio.addEventListener('ended', () => {
        if (isRepeat) {
            audio.currentTime = 0;
            audio.play();
        } else {
            nextSong();
        }
    });
    volume.addEventListener('input', () => {
        audio.volume = volume.value;
    });

    function formatTime(sec) {
        sec = Math.floor(sec);
        let m = Math.floor(sec / 60);
        let s = sec % 60;
        return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
    }
    loadSong(currentSong);

    // AUTH UI & LOGIC
    const userInfo = document.getElementById('user-info');
    const loginBtn = document.getElementById('login-btn');
    const authModal = document.getElementById('auth-modal');
    const closeAuth = document.getElementById('close-auth');
    const tabLogin = document.getElementById('tab-login');
    const tabRegister = document.getElementById('tab-register');
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');
    const authMessage = document.getElementById('auth-message');

    function showAuthModal(tab = 'login') {
        authModal.style.display = 'flex';
        if (tab === 'login') {
            loginForm.style.display = '';
            registerForm.style.display = 'none';
            tabLogin.classList.add('active');
            tabRegister.classList.remove('active');
        } else {
            loginForm.style.display = 'none';
            registerForm.style.display = '';
            tabLogin.classList.remove('active');
            tabRegister.classList.add('active');
        }
        authMessage.textContent = '';
    }

    function hideAuthModal() {
        authModal.style.display = 'none';
        loginForm.reset();
        registerForm.reset();
        authMessage.textContent = '';
    }
    if (loginBtn) loginBtn.onclick = () => showAuthModal('login');
        tabLogin.onclick = () => showAuthModal('login');
        tabRegister.onclick = () => showAuthModal('register');
        closeAuth.onclick = hideAuthModal;
        authModal.onclick = (e) => {
            if (e.target === authModal) hideAuthModal();
    };

   
    // loginForm.onsubmit = function(e) {
    //     $('#login-form').submit();
      
    // };
    // registerForm.onsubmit = function(e) {
    //     $('#register-form').submit();
       
    // };
    // Hiển thị user nếu đã đăng nhập
    window.addEventListener('DOMContentLoaded', () => {
        const user = JSON.parse(localStorage.getItem('currentUser') || 'null');
        if (user) setUserUI(user);
    });

    // ALBUM DATA & UI
    const albums = [{
            title: "Đây Là Việt Nam (Single)",
            artist: "Phan Duy Anh, NBoro",
            thumb: "https://photo-resize-zmp3.zmdcdn.me/w600_r1x1_webp/cover/7/7/7/7/77777777777777777777777777777777.jpg"
        },
        {
            title: "Tình Yêu Vô Giá (Single)",
            artist: "Hải Đăng Doo",
            thumb: "https://photo-resize-zmp3.zmdcdn.me/w600_r1x1_webp/cover/8/8/8/8/88888888888888888888888888888888.jpg"
        },
        {
            title: "TANCA Season 1:",
            artist: "Bột Màu Khoai Tây Cà Rốt, Tanca",
            thumb: "https://photo-resize-zmp3.zmdcdn.me/w600_r1x1_webp/cover/3/3/3/3/33333333333333333333333333333333.jpg"
        },
        {
            title: "Chàng Chinh Phu (Single)",
            artist: "Niết, Thanh Thuý, TMons",
            thumb: "https://photo-resize-zmp3.zmdcdn.me/w600_r1x1_webp/cover/9/9/9/9/99999999999999999999999999999999.jpg"
        },
        {
            title: "Chỉ Cần Có Em Bên Đời...",
            artist: "Sáng Mơ",
            thumb: "https://photo-resize-zmp3.zmdcdn.me/w600_r1x1_webp/cover/1/0/1/0/10101010101010101010101010101010.jpg"
        }
    ];

    // function renderAlbums() {
    //     const albumList = document.getElementById('album-list');
    //     albumList.innerHTML = albums.map(album => `
    //     <div class="album-card">
    //         <img src="${album.thumb}" alt="${album.title}">
    //         <div class="album-info">
    //             <div class="album-title">${album.title}</div>
    //             <div class="album-artist">${album.artist}</div>
    //         </div>
    //     </div>
    // `).join('');
    // }
    // renderAlbums();

    function toggleRepeat() {
        isRepeat = !isRepeat;
        repeatBtn.style.color = isRepeat ? '#a259e6' : '#fff';
    }

    // SEARCH SUGGESTION DROPDOWN
    const searchInput = document.getElementById('search-input');
    const searchSuggest = document.getElementById('search-suggest');
    @php
        use App\Modules\Song\Models\Song;

        $check = Song::where('status', 'active')->with('singer')
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();
        
        $songs_array = $check->map(function($s) {
            return [
                'title' => $s->title,
                'artist' => $s->singer->alias,
                'src' => asset(str_replace(':8000/', '', $s->resourcesSong[0]->url)),
                'thumb' => asset($s->singer->photo),
            ];
        });
    
    @endphp
    const searchData = @json($songs_array ?? []);

    function renderSuggest(keyword) {
        if (!keyword) {
            searchSuggest.style.display = 'none';
            return;
        }
        const kw = keyword.trim().toLowerCase();
        let html = '';
        // Từ khóa liên quan
        html += `<div class="suggest-title">Từ khóa liên quan</div>`;
        html +=
            `<div class="suggest-item"><span class="suggest-icon"><i class='fas fa-search'></i></span> <span>${kw}</span></div>`;
        html +=
            `<div class="suggest-item"><span class="suggest-icon"><i class='fas fa-search'></i></span> <span>Tìm kiếm "${kw}"</span></div>`;
        // Gợi ý kết quả
        const results = searchData.filter(item => item.title.toLowerCase().includes(kw) || (item.artist && item.artist
            .toLowerCase().includes(kw)));
        if (results.length) {
            html += `<div class="suggest-title">Gợi ý kết quả</div>`;
            for (const item of results) {
                html += `<a class="suggest-item">
                <img class="suggest-thumb" src="${item.thumb}" alt="">
                <div class="suggest-info">
                    <div class="suggest-title-main">${item.title}${item.premium ? '<span class=\'suggest-premium\'>PREMIUM</span>' : ''}</div>
                    <div class="suggest-sub">${item.artist}</div>
                </div>
            </a>`;
            }
        }
        searchSuggest.innerHTML = html;
        searchSuggest.style.display = 'flex';
    }

    searchInput.addEventListener('input', (e) => {
        renderSuggest(e.target.value);
    });

    // Ẩn gợi ý khi click ra ngoài
    document.addEventListener('click', (e) => {
        if (!searchSuggest.contains(e.target) && e.target !== searchInput) {
            searchSuggest.style.display = 'none';
        }
    });

    // Ẩn khi xóa input
    searchInput.addEventListener('blur', () => {
        setTimeout(() => {
            searchSuggest.style.display = 'none';
        }, 200);
    });
</script>

</html>
