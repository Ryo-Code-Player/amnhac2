@extends('frontend.layouts.master')
@section('content')
<div class="fanclub-container">

  <main class="main-content">
    <h1>Fanclub</h1>
    <div class="tabs">
      <button class="tab active" onclick="showTab('all')">FANCLUB CỘNG ĐỒNG</button>
      @if(auth()->check())
        <button class="tab" onclick="showTab('mine')">FANCLUB CỦA TÔI</button>
        <button class="tab" onclick="showTab('followed')">FANCLUB ĐÃ QUAN TÂM</button>
      @endif
    </div>
    <div class="fanclub-list" id="tab-all">
      @if(auth()->check())
      <div class="fanclub-card create-fanclub">
        <div class="plus-icon">+</div>
        <div class="create-text">Tạo fanclub mới</div>
      </div>
      @endif
      @foreach ($fanclubs as $fanclub)
        <div class="fanclub-card" data-id="{{ $fanclub->id }}">
          <img src="{{ $fanclub->photo }}" alt="Fanclub Image">
          <div class="fanclub-info">
            <h3><a href="{{ route('front.fanclub.get',['slug'=>$fanclub->slug]) }}">{{ $fanclub->title }}</a></h3>
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
    <div class="fanclub-list" id="tab-mine" style="display:none;">
      <!-- Card fanclub của tôi -->
      <div class="fanclub-card create-fanclub">
        <div class="plus-icon">+</div>
        <div class="create-text">Tạo fanclub mới</div>
      </div>
      @foreach ($fanclubs as $fanclub)
      @if(auth()->check())
        @if($fanclub->user_id == auth()->user()->id)
        <div class="fanclub-card" data-id="{{ $fanclub->id }}">
          <img src="{{ $fanclub->photo }}" alt="Fanclub Image">
          <div class="fanclub-info">
            <h3><a href="{{ route('front.fanclub.get',['slug'=>$fanclub->slug]) }}">{{ $fanclub->title }}</a></h3>
            <p>Chủ fanclub: {{  $fanclub->user->full_name }}</p>
            @if($fanclub->check_fanclub)
              <button class="follow-btn followed" style="width:100%;">Đã quan tâm</button>
            @else
              <button class="follow-btn" style="width:100%;">Quan tâm</button>
            @endif
            <button class="delete-btn" style="width:100%; margin-top: 10px;">Xóa</button>

            <div class="follow-count">{{  $fanclub->quantity }} lượt quan tâm</div>
          </div>
        </div>
        @endif
      @endif
      @endforeach
    </div>
    @if(auth()->check())
      <div class="fanclub-list" id="tab-followed" style="display:none;">
      
          @foreach ($fanclubs_user as $fanclub)
            <div class="fanclub-card" data-id="{{ $fanclub->fanclub->id }}">
              <img src="{{ $fanclub->fanclub->photo }}" alt="Fanclub Image">
              <div class="fanclub-info">
                <h3><a href="{{ route('front.fanclub.get',['slug'=>$fanclub->fanclub->slug]) }}">{{ $fanclub->fanclub->title }}</a></h3>
                <p>Chủ fanclub: {{  $fanclub->fanclub->user->full_name }}</p>
              
                  <button class="follow-btn followed" style="width:100%;">Đã quan tâm</button>
              
                <div class="follow-count">{{  $fanclub->fanclub->quantity }} lượt quan tâm</div>
              </div>
            </div>
        @endforeach
      </div>
    @endif
  </main>
</div>

<!-- Modal Tạo Fanclub -->
<div id="modal-create-fanclub" class="modal-fanclub" style="display:none;">
  <div class="modal-content">
    <span class="close-modal" onclick="closeModal()">&times;</span>
    <h2>Tạo Fanclub mới</h2>
    <form class="form-create-fanclub">
      <label>Tên fanclub</label>
      <input type="text" placeholder="Nhập tên fanclub" name="name" required>
      <label>Hình ảnh</label>
      <input type="file" accept="image/*" name="image" required>
      <label>Mô tả ngắn</label>
      <input type="text" placeholder="Nhập mô tả ngắn" name="summary" required>
      <label>Mô tả chi tiết</label>
      <textarea rows="4" placeholder="Nhập mô tả chi tiết" name="content" required></textarea>
      <button type="submit">Tạo fanclub</button>
    </form>
  </div>
</div>

<style>
.fanclub-container {
  display: flex;
  background: #18182a;
  min-height: 80vh;
  color: #fff;
}
.sidebar {
  width: 200px;
  /* background: #18182a; */
  padding: 20px 0;
}
.sidebar .logo {
  font-size: 2rem;
  font-weight: bold;
  color: #fff;
  margin-bottom: 30px;
  text-align: center;
}
.sidebar nav ul {
  list-style: none;
  padding: 0;
}
.sidebar nav ul li {
  padding: 15px 30px;
  cursor: pointer;
  color: #fff;
}
.sidebar nav ul li.active {
  color: #a259ff;
  font-weight: bold;
}
.main-content {
  flex: 1;
  padding: 40px;
  position: relative;
}
.tabs {
  display: flex;
  gap: 20px;
  margin-bottom: 30px;
}
.tab {
  background: none;
  border: none;
  color: #bbaaff;
  font-size: 1.1rem;
  padding: 10px 20px;
  cursor: pointer;
  border-bottom: 2px solid transparent;
  transition: border 0.2s;
}
.tab.active {
  color: #fff;
  border-bottom: 2px solid #a259ff;
}
.fanclub-list {
  display: flex;
  gap: 30px;
  flex-wrap: wrap;
}
.fanclub-card {
  background: #23234a;
  border-radius: 16px;
  width: 220px;
  padding: 18px;
  display: flex;
  flex-direction: column;
  align-items: center;
  box-shadow: 0 2px 8px #0002;
  transition: box-shadow 0.25s, transform 0.18s, border 0.18s, background 0.18s;
  border: 2px solid transparent;
  position: relative;
  cursor: pointer;
}
.fanclub-card:hover {
  box-shadow: 0 8px 32px #a259ff55, 0 2px 8px #0003;
  transform: translateY(-6px) scale(1.04);
  border: 2px solid #a259ff;
  background: #28285a;
  z-index: 2;
}
.fanclub-card img {
  width: 120px;
  height: 120px;
  border-radius: 12px;
  object-fit: cover;
  margin-bottom: 15px;
}
.fanclub-info h3 a{
  margin: 0 0 8px 0;
  font-size: 1.1rem;
  color: #fff;
  text-decoration: none;
}
.fanclub-info p {
  margin: 0 0 12px 0;
  font-size: 0.95rem;
  color: #bbb;
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
.create-fanclub {
  border: 2px dashed #a259ff;
  background: none;
  color: #a259ff;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 220px;
  width: 220px;
  cursor: pointer;
  box-shadow: none;
  transition: border 0.2s, background 0.2s;
}
.create-fanclub:hover {
  background: #23234a44;
  border-color: #fff;
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
.form-create-fanclub {
  display: flex;
  flex-direction: column;
  gap: 14px;
  margin-top: 18px;
}
.form-create-fanclub label {
  font-weight: 500;
  color: #a259ff;
}
.form-create-fanclub input[type="text"],
.form-create-fanclub textarea {
  border: 1px solid #444;
  border-radius: 8px;
  padding: 8px 12px;
  background: #18182a;
  color: #fff;
  font-size: 1rem;
}
.form-create-fanclub input[type="file"] {
  background: #18182a;
  color: #fff;
  border: none;
}
.form-create-fanclub button[type="submit"] {
  background: #a259ff;
  color: #fff;
  border: none;
  border-radius: 8px;
  padding: 10px 0;
  font-size: 1.1rem;
  margin-top: 10px;
  cursor: pointer;
  transition: background 0.2s;
}
.form-create-fanclub button[type="submit"]:hover {
  background: #7a1fff;
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
</style>

<script>
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
