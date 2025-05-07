@extends('frontend.layouts.master')
@section('content')
<style>
.playlist-container {
    padding: 32px 48px;
    background: #181526;
    min-height: 100vh;
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
    gap: 32px;
    flex-wrap: wrap;
}
.playlist-card, .playlist-create {
    width: 220px;
    height: 280px;
    background: #221f35;
    border-radius: 16px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-bottom: 24px;
    position: relative;
    overflow: hidden;
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
    color: #fff;
    font-size: 1.1rem;
    font-weight: 500;
    margin-bottom: 4px;
    margin-left:10px;
}
.playlist-author {
    color: #aaa;
    margin-left:10px;
    font-size: 0.95rem;
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
</style>
<div class="playlist-container">
    <div class="playlist-title">Playlist</div>
    <div class="playlist-tabs">
        <div class="playlist-tab active" data-tab="all">TẤT CẢ</div>
        @if(auth()->check())
            <div class="playlist-tab" data-tab="mine">CỦA TÔI</div>
        @endif
    </div>
    <div class="playlist-tab-content active" id="tab-all">
        <div class="playlist-grid">
            @if(auth()->check())
            <div class="playlist-create">
                <div class="plus">+</div>
                Tạo playlist mới
            </div>
            @endif
            @foreach ($playlist as $item)
          
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
            @endforeach
            <!-- Thêm các playlist khác ở đây nếu muốn -->
        </div>
    </div>
    <div class="playlist-tab-content" id="tab-mine">
        <div class="playlist-grid">
            <div class="playlist-create">
                <div class="plus">+</div>
                Tạo playlist mới
            </div>
            @foreach ($playlist_user as $item)
            <div class="playlist-card">
                <img class="playlist-cover" src="{{ asset($item->photo) }}" alt="cover">
                <div class="playlist-overlay">
                    <a  href="{{ route('front.playlist.delete', $item->id) }}"  class="delete-btn" title="Xóa playlist">
                        <svg class="delete-icon" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <line x1="5" y1="5" x2="15" y2="15" stroke="white" stroke-width="2" stroke-linecap="round"/>
                            <line x1="15" y1="5" x2="5" y2="15" stroke="white" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </a>
                    <a class="play-btn" href="{{ route('front.playlist.slug', $item->slug) }}">
                        <svg class="play-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="12" r="12" fill="none"/>
                            <polygon points="9,7 19,12 9,17" fill="white"/>
                        </svg>
                    </a>
                </div>
                <div class="playlist-info">
                    <div class="playlist-name" >{{ \Illuminate\Support\Str::limit($item->title, 25) }}</div>
                        <div class="playlist-author">{{ $item->user->full_name }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
<!-- Popup tạo playlist mới -->
<div id="createPlaylistModal" class="modal-overlay" style="display:none;">
    <div class="modal-content">
        <button class="close-btn" onclick="closeCreatePlaylistModal()">&times;</button>
        <div class="modal-title">Tạo Playlist Mới</div>
        <form id="createPlaylistForm" action="{{ route('front.playlist.create') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="playlistName">Tên Playlist</label>
                <input type="text" id="playlistName" name="playlistName" required>
            </div>
            <div class="form-group">
                <label for="playlistImage">Hình ảnh</label>
                <input type="file" id="playlistImage" name="playlistImage" accept="image/*" required>
                <img id="playlistImagePreview" src="#" alt="Preview" style="display:none;max-width:150px;margin-top:10px;border-radius:8px;" />
            </div>
            <div class="form-group">
                <label for="playlistType">Loại Playlist</label>
                <select id="playlistType" name="playlistType" required>
                    <option value="">Chọn loại</option>
                    <option value="public">Công khai</option>
                    <option value="private">Riêng tư</option>
                </select>
            </div>
            <button type="submit" class="submit-btn">Tạo mới</button>
        </form>
    </div>
</div>
<script>
// Tab switching logic
const tabs = document.querySelectorAll('.playlist-tab');
const tabContents = document.querySelectorAll('.playlist-tab-content');
tabs.forEach(tab => {
    tab.addEventListener('click', function() {
        tabs.forEach(t => t.classList.remove('active'));
        tab.classList.add('active');
        const tabName = tab.getAttribute('data-tab');
        tabContents.forEach(content => {
            content.classList.remove('active');
        });
        document.getElementById('tab-' + tabName).classList.add('active');
    });
});
// Popup logic
document.querySelectorAll('.playlist-create').forEach(btn => {
    btn.addEventListener('click', function() {
        document.getElementById('createPlaylistModal').style.display = 'flex';
    });
});
function closeCreatePlaylistModal() {
    document.getElementById('createPlaylistModal').style.display = 'none';
}
// Xem trước hình ảnh khi chọn file
const playlistImageInput = document.getElementById('playlistImage');
const playlistImagePreview = document.getElementById('playlistImagePreview');
if (playlistImageInput) {
    playlistImageInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                playlistImagePreview.src = e.target.result;
                playlistImagePreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            playlistImagePreview.src = '#';
            playlistImagePreview.style.display = 'none';
        }
    });
}
</script>
@endsection
