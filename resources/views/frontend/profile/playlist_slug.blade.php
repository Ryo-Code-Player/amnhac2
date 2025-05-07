@extends('frontend.layouts.master')
@section('content')
<style>
.playlist-container {
    display: flex;
    gap: 32px;
    color: #fff;
    background: #181828;
    padding: 40px 60px;
    border-radius: 20px;
    margin: 40px auto;
    max-width: 1100px;
}
.playlist-cover {
    width: 220px;
    height: 220px;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 24px #0005;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #222;
}
.playlist-cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.playlist-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.playlist-title {
    font-size: 2.2rem;
    font-weight: bold;
    margin-bottom: 8px;
}
.playlist-meta {
    color: #aaa;
    margin-bottom: 24px;
}
.playlist-actions {
    display: flex;
    align-items: center;
    gap: 16px;
}
.playlist-actions button {
    background: #7c3aed;
    color: #fff;
    border: none;
    border-radius: 24px;
    padding: 10px 32px;
    font-size: 1.1rem;
    cursor: pointer;
    font-weight: 500;
    transition: background 0.2s;
}
.playlist-actions button:hover {
    background: #a78bfa;
}
.song-table {
    width: 90%;
    margin: 40px auto 0 auto;
    border-collapse: collapse;
    background: transparent;
}
.song-table th, .song-table td {
    padding: 14px 12px;
    text-align: left;
}
.song-table th {
    color: #aaa;
    font-weight: 500;
    border-bottom: 1px solid #333;
}
.song-table tr {
    transition: background 0.2s;
}
.song-table tr:hover {
    background: #23234a;
}
.song-title {
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 10px;
}
.song-title img {
    width: 40px;
    height: 40px;
    object-fit: cover;
    border-radius: 8px;
}
.song-album, .song-time {
    color: #aaa;
    font-size: 0.98rem;
}
.play-btn {
    background: #a259f7;
    border: none;
    outline: none;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.2s;
    float: right;
}
.play-btn:hover {
    background: #7c3aed;
}
</style>

<div class="playlist-container">
    <div class="playlist-cover">
      <img src="{{ asset($playlist->photo) }}" alt="Playlist Cover">
    </div>
    <div class="playlist-info">
      <div class="playlist-title">{{ $playlist->title }}</div>
      <div class="playlist-meta">
        Tạo bởi <b>{{ $playlist->user->full_name }}</b> · 
        @if ($playlist->type == 'public')
            Công khai
        @else
            Riêng tư
        @endif
      </div>
      {{-- <div class="playlist-actions">
        <button id="main-play-btn" class="main-play-btn">BẮT ĐẦU PHÁT</button>
      </div> --}}
    </div>
</div>

<table class="song-table">
    <thead>
      <tr>
        <th>BÀI HÁT</th>
        <th>CA SĨ</th>
        <th></th>
       
      </tr>
    </thead>
    <tbody>
        @foreach ($array_song as $item)
            @php
                $songUrl = str_replace(':8000/', '', $item->resourcesSong[0]->url);

            @endphp
            <tr>
                <td class="song-title">
                    <img src="{{ asset($item->singer->photo) }}" alt="">
                    {{ $item->title }}
                </td>
                <td class="song-album">{{ $item->singer->alias }}</td>
           
                <td>
                    <button class="play-btn"  onclick="playSong('{{ asset($songUrl) }}',
                     '{{ $item->title }}', '{{ $item->singer->alias }}','{{ $item->singer->photo }}')">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="white">
                            <polygon points="7,5 15,10 7,15" fill="white"/>
                        </svg>
                    </button>
                </td>
            </tr>
        @endforeach
     

    </tbody>
</table>

<!-- Thanh phát nhạc dưới cùng -->
<div id="music-bar" style="display:none;align-items:center;gap:24px;padding:16px 32px;background:#2a233a;position:fixed;bottom:0;left:0;right:0;z-index:100;">
    <img id="bar-img" src="" alt="" style="width:60px;height:60px;object-fit:cover;border-radius:12px;">
    <div>
        <div id="bar-title" style="font-weight:bold;font-size:1.1rem;">Tên bài hát</div>
        <div id="bar-singer" style="color:#aaa;">Tên ca sĩ</div>
    </div>
    <audio id="audio-bar" controls style="flex:1;margin:0 24px;max-width:600px;"></audio>
</div>

<audio id="audio-player" style="display:none"></audio>

<script>
    var playlistSongs = [
        @foreach ($array_song as $item)
            {
                url: "{{ asset(str_replace(':8000/', '', $item->resourcesSong[0]->url)) }}",
                title: "{{ $item->title }}",
                singer: "{{ $item->singer->alias }}",
                photo: "{{ $item->singer->photo }}"
            }@if (!$loop->last),@endif
        @endforeach
    ];

    let isPlaylistPlaying = false;
    let currentSongIndex = 0;
    const audioPlayer = document.getElementById('audio-player');
    const mainPlayBtn = document.getElementById('main-play-btn');

    function playCurrentSong() {
        if (playlistSongs.length === 0) return;
        const song = playlistSongs[currentSongIndex];
        audioPlayer.src = song.url;
        audioPlayer.play();
       
    }

    mainPlayBtn.addEventListener('click', function() {
        if (!isPlaylistPlaying) {
            isPlaylistPlaying = true;
            mainPlayBtn.innerHTML = '⏸ Tạm dừng';
            
            currentSongIndex = 0;
            const firstSong = playlistSongs[0];
            playSong(firstSong.url, firstSong.title, firstSong.singer, firstSong.photo);
        } else {
            isPlaylistPlaying = false;
            mainPlayBtn.innerHTML = 'BẮT ĐẦU PHÁT';
            audioPlayer.pause();
            togglePlay()
            // Dừng luôn thanh phát nhạc dưới cùng
            document.getElementById('audio-bar').pause();
            // Đảm bảo reset thời gian về đầu (nếu muốn)
            audioPlayer.currentTime = 0;
            document.getElementById('audio-bar').currentTime = 0;
        }
    });

    audioPlayer.addEventListener('ended', function() {
        if (isPlaylistPlaying) {
            currentSongIndex++;
            if (currentSongIndex < playlistSongs.length) {
                playCurrentSong();
            } else {
                isPlaylistPlaying = false;
                mainPlayBtn.innerHTML = 'BẮT ĐẦU PHÁT';
            }
        }
    });

    // Hàm phát nhạc và cập nhật thanh phát nhạc dưới cùng
    function playSong(url, title, singer, photo) {
        // alert(1);
        // Hiện thanh phát nhạc nếu đang ẩn
        // document.getElementById('music-bar').style.display = 'flex';
        // // Cập nhật thông tin
        // document.getElementById('bar-img').src = photo;
        // document.getElementById('bar-title').innerText = title;
        // document.getElementById('bar-singer').innerText = singer;
        // // Phát nhạc
        // const audio = document.getElementById('audio-bar');
        // audio.src = url;
        // audio.play();
    }
</script>
@endsection
