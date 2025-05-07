<div class="player" id="player" style="display:none;">
    <img id="player-thumb" src="https://photo-resize-zmp3.zmdcdn.me/w94_r1x1_webp/cover/7/2/2/2/7222e2e2e2e2e2e2e2e2e2e2e2e2e2e2.jpg" alt="">
    <div class="info">
        <h4 id="player-title">Chúng Ta Của Hiện Tại</h4>
        <p id="player-artist">Sơn Tùng M-TP</p>
    </div>
    <audio id="audio" src="https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3"></audio>
    <div class="controls">
        <button onclick="prevSong()"><i class="fas fa-step-backward"></i></button>
        <button id="repeat-btn" onclick="toggleRepeat()" title="Lặp lại" style="color:#fff;"><i class="fas fa-redo"></i></button>
        <button onclick="togglePlay()" id="play-btn"><i class="fas fa-play"></i></button>
        <button onclick="nextSong()"><i class="fas fa-step-forward"></i></button>
    </div>
    <input type="range" id="progress" value="0" max="100">
    <span id="current-time">00:00</span> / <span id="duration">00:00</span>
    <input type="range" id="volume" min="0" max="1" step="0.01" value="1">
    <!-- Nút Xem thêm -->
    @if(auth()->check())
    <button id="more-btn" style="margin-left:10px;background:#a259ff;color:#fff;border:none;border-radius:6px;padding:6px 16px;cursor:pointer;">Xem thêm</button>
    @endif
</div>
<!-- Popup Thêm vào Playlist -->
@if(auth()->check())
<div id="add-to-playlist-modal" style="display:none;position:fixed;z-index:300;left:0;top:0;width:100vw;height:100vh;background:rgba(23,15,35,0.7);backdrop-filter:blur(2px);justify-content:center;align-items:center;">
    <div style="background:#221f35;padding:32px 24px;border-radius:16px;min-width:320px;max-width:90vw;">
        <button id="close-add-playlist" style="float:right;background:none;border:none;color:#fff;font-size:22px;cursor:pointer;">&times;</button>
        <h3 style="color:#fff;margin-bottom:18px;">Thêm vào playlist của tôi</h3>
        <div id="playlist-list">
            @php
                $playlist = App\Modules\Playlist\Models\Playlist::orderByDesc('order_id')->where('user_id', auth()->user()->id)->get();
            @endphp
            <ul style="list-style:none;padding:0;margin:0;">
                @forelse($playlist as $item)
                    <li style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid #333;">
                        <div style="display:flex;align-items:center;gap:12px;">
                            <img src="{{ asset($item->photo) }}" alt="{{ $item->title }}" style="width:38px;height:38px;border-radius:8px;object-fit:cover;">
                            <span style="color:#fff;font-size:1rem;">{{ $item->title }}</span>
                        </div>
                        <button onclick="addSongToPlaylist({{ $item->id }})" style="background:#7c3aed;color:#fff;border:none;border-radius:50%;width:32px;height:32px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;cursor:pointer;">+</button>
                    </li>
                @empty
                    <li style="color:#aaa;">Bạn chưa có playlist nào.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endif
<div id="auth-modal" style="display:none;position:fixed;z-index:200;left:0;top:0;width:100vw;height:100vh;background:rgba(23,15,35,0.7);backdrop-filter:blur(2px);justify-content:center;align-items:center;">
    <div>
        <button id="close-auth"><i class="fas fa-times"></i></button>
        <div class="tab-row">
            <button id="tab-login" class="active">Đăng nhập</button>
            <button id="tab-register">Đăng ký</button>
        </div>
        <form id="login-form" method="post" action="{{ route('admin.checklogin') }}">
            @csrf
            <input type="text" id="login-username" name="email" placeholder="Tên đăng nhập" required>
            <input type="password" id="login-password" name="password" placeholder="Mật khẩu" required>
            <button type="submit">Đăng nhập</button>
        </form>
        <form id="register-form" style="display:none;" method="post" action="{{ route('user.register') }}">
            @csrf
            <input type="text" id="register-username" name="email" placeholder="Tên đăng nhập" required>
            <input type="password" id="register-password" name="password" placeholder="Mật khẩu" required>
            <input type="text" id="register-name" name="name" placeholder="Tên hiển thị" required>
            <button type="submit">Đăng ký</button>
        </form>
        <div id="auth-message"></div>
    </div>
</div>
<!-- Nút Bộ lọc cố định góc phải -->
<button id="filter-btn" style="position:fixed;bottom:32px;right:32px;z-index:401
;background:#7c3aed;color:#fff;border:none;border-radius:50px;padding:12px 28px;font-size:1.1rem;
box-shadow:0 2px 8px #0002;cursor:pointer;">
  <i class="fas fa-filter"></i> Bộ lọc
</button>
<!-- Popup Bộ lọc -->
<div id="filter-modal" style="display:none;position:fixed;z-index:400;left:0;top:0;width:100vw;height:100vh;background:rgba(23,15,35,0.85);backdrop-filter:blur(2px);justify-content:center;align-items:center;">
  <div style="background:#231b2e;padding:36px 32px 28px 32px;border-radius:24px;min-width:340px;max-width:90vw;box-shadow:0 8px 40px #0008;position:relative;">
    <button id="close-filter-modal" style="position:absolute;top:18px;right:18px;background:none;border:none;font-size:28px;cursor:pointer;color:#fff;transition:color 0.2s;">&times;</button>
    <h3 style="margin-bottom:22px;color:#fff;font-size:1.35rem;font-weight:600;">Bộ lọc bài hát</h3>
    <form id="filter-form" action="{{ route('front.zingchart') }}" method="get">
      @csrf
      <div style="margin-bottom:18px;">
        <label style="color:#fff;font-size:1rem;margin-bottom:6px;display:block;">Thời gian:</label>
        <select name="time" style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #3a2d4d;background:#2d2340;color:#fff;outline:none;">
          <option value="">-- Chọn --</option>
          <option value="newest" {{ request()->time == 'newest' ? 'selected' : '' }}>Gần đây nhất</option>
          <option value="oldest" {{ request()->time == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
        </select>
      </div>
      <div style="margin-bottom:18px;">
        <label style="color:#fff;font-size:1rem;margin-bottom:6px;display:block;">Sắp xếp chữ cái:</label>
        <select name="alpha" style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #3a2d4d;background:#2d2340;color:#fff;outline:none;">
          <option value="">-- Chọn --</option>
          <option value="az" {{ request()->alpha == 'az' ? 'selected' : '' }}>A - Z</option>
          <option value="za" {{ request()->alpha == 'za' ? 'selected' : '' }}>Z - A</option>
        </select>
      </div>
      <div style="margin-bottom:18px;">
        <label style="color:#fff;font-size:1rem;margin-bottom:6px;display:block;">Tên ca sĩ:</label>
        <input type="text" name="artist"
         placeholder="Nhập tên ca sĩ..." 
         style="width:92%;padding:10px 12px;border-radius:10px;border:1px solid #3a2d4d;background:#2d2340;color:#fff;outline:none;"
         value="{{ request()->artist }}"
         >
      </div>
      <div style="margin-bottom:24px;">
        <label style="color:#fff;font-size:1rem;margin-bottom:6px;display:block;">Tên bài hát:</label>
        <input type="text" name="title" placeholder="Nhập tên bài hát..." 
        style="width:92%;padding:10px 12px;border-radius:10px;border:1px solid #3a2d4d;background:#2d2340;
        color:#fff;outline:none;"
        value="{{ request()->title }}"
        >
      </div>
      <button type="submit" style="background:#a259ff;color:#fff;border:none;border-radius:12px;padding:12px 0;width:100%;font-size:1.1rem;font-weight:600;box-shadow:0 2px 8px #0002;cursor:pointer;transition:background 0.2s;">Lọc</button>
    </form>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Bộ lọc popup
  const filterBtn = document.getElementById('filter-btn');
  const filterModal = document.getElementById('filter-modal');
  const closeFilterModal = document.getElementById('close-filter-modal');
  const filterForm = document.getElementById('filter-form');
  const filterSubmitBtn = filterForm.querySelector('button[type="submit"]');

  if (filterBtn) {
    filterBtn.onclick = function() {
      filterModal.style.display = 'flex';
    }
  }
  if (closeFilterModal) {
    closeFilterModal.onclick = function() {
      filterModal.style.display = 'none';
    }
  }
  if (filterModal) {
    filterModal.onclick = function(e) {
      if (e.target === filterModal) filterModal.style.display = 'none';
    }
  }
  // Khi bấm nút Lọc thì submit form và đóng popup
  if (filterSubmitBtn) {
    filterSubmitBtn.onclick = function() {
      filterModal.style.display = 'none';
      // Form sẽ tự submit do là button[type=submit] trong form
    }
  }
});

// Hiện popup khi bấm Xem thêm
const moreBtn = document.getElementById('more-btn');
const addToPlaylistModal = document.getElementById('add-to-playlist-modal');
const closeAddPlaylist = document.getElementById('close-add-playlist');
moreBtn.onclick = function() {
    addToPlaylistModal.style.display = 'flex';
    // Gọi AJAX lấy danh sách playlist của user (có thể thay bằng render server nếu muốn)
    fetch('/api/my-playlists')
      .then(res => res.json())
      .then(data => {
        const listDiv = document.getElementById('playlist-list');
        if (data.length === 0) {
          listDiv.innerHTML = '<div style="color:#aaa;">Bạn chưa có playlist nào.</div>';
        } else {
          listDiv.innerHTML = data.map(item => `
            <div style="display:flex;align-items:center;justify-content:space-between;padding:8px 0;border-bottom:1px solid #333;">
              <span style="color:#fff;">${item.title}</span>
              <button onclick="addSongToPlaylist(${item.id})" style="background:#7c3aed;color:#fff;border:none;border-radius:4px;padding:4px 12px;cursor:pointer;">Thêm vào</button>
            </div>
          `).join('');
        }
      });
};
closeAddPlaylist.onclick = function() {
    addToPlaylistModal.style.display = 'none';
};
 
function addSongToPlaylist(playlistId) {
    const currentSongId = $('#player-title').text();
    if (!currentSongId) { alert('Không xác định được bài hát!'); return; }
    $.ajax({
        url: '/playlist/' + playlistId + '/add-song',
        type: 'POST',
        data: { 
            song_id: currentSongId,
            _token: '{{ csrf_token() }}',
            playlistId: currentSongId
        },
        success: function(response) {
            console.log(response);
            if (response.success) {
                Notiflix.Notify.success('Đã thêm vào playlist!');
                addToPlaylistModal.style.display = 'none';
            } else {
                Notiflix.Notify.failure('Lỗi: ' + response.message);
            }
        },
        error: function(xhr, status, error) {
            alert('Lỗi: ' + error);
        }
    })
    // fetch('/playlist/' + playlistId + '/add-song', {
    //   method: 'POST',
    //   headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
    //   body: JSON.stringify({ song_id: currentSongId })
    // })
    // .then(res => res.json())
    // .then(data => {
    //   if (data.success) {
    //     alert('Đã thêm vào playlist!');
    //     addToPlaylistModal.style.display = 'none';
    //   } else {
    //     alert('Lỗi: ' + (data.message || 'Không thể thêm vào playlist.'));
    //   }
    // });
}
</script>