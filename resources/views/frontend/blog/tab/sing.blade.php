<style>
    .singer-profile-container {
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 2px 16px #0001;
      max-width: 800px;
      margin: 40px auto;
      padding: 32px 36px;
    }
    .singer-header {
      display: flex;
      align-items: center;
      gap: 32px;
    }
    .singer-avatar {
      width: 140px;
      height: 140px;
      border-radius: 50%;
      object-fit: cover;
      border: 4px solid #1877f2;
      box-shadow: 0 2px 12px #0002;
    }
    .singer-basic-info {
      flex: 1;
    }
    .singer-name {
      font-size: 2.2em;
      font-weight: bold;
      margin-bottom: 8px;
    }
    .singer-meta {
      color: #555;
      font-size: 1.08em;
      margin-bottom: 10px;
      display: flex;
      flex-wrap: wrap;
      gap: 18px;
    }
    .active-status {
      color: #1bc47d;
      font-weight: bold;
    }
    .singer-socials a {
      color: #1877f2;
      font-size: 1.3em;
      margin-right: 12px;
      transition: color 0.2s;
    }
    .singer-socials a:hover {
      color: #1250b0;
    }
    .singer-short-desc {
      margin: 18px 0 24px 0;
      color: #888;
      font-size: 1.1em;
      font-style: italic;
    }
    .singer-detail-content {
      margin-bottom: 28px;
    }
    .singer-detail-content h3 {
      color: #1877f2;
      margin-bottom: 10px;
    }
    .singer-gallery h3, .singer-songs h3 {
      color: #1877f2;
      margin-bottom: 10px;
    }
    .gallery-list {
      display: flex;
      gap: 12px;
      flex-wrap: wrap;
    }
    .gallery-list img {
      width: 110px;
      height: 110px;
      object-fit: cover;
      border-radius: 10px;
      box-shadow: 0 1px 6px #0001;
    }
    .singer-songs ul {
      padding-left: 18px;
    }
    .singer-songs li {
      margin-bottom: 6px;
      color: #333;
    }
    .btn-edit-singer, .btn-delete-singer {
      margin-right: 8px;
      padding: 6px 16px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-weight: bold;
    }
    .btn-edit-singer {
      background: #ffc107;
      color: #222;
    }
    .btn-delete-singer {
      background: #e74c3c;
      color: #fff;
    }
    .btn-edit-singer:hover {
      background: #e0a800;
    }
    .btn-delete-singer:hover {
      background: #c0392b;
    }
</style>
<div id="tab-sing" class="tab-content" style="display:none; padding: 20px;">
    @if(auth()->user()->id == $user->id)
      <button id="open-singer-modal" class="btn-create-singer">Tạo hồ sơ ca sĩ</button>
    @endif
    @foreach($singer as $s)
      <div class="singer-profile-container">
        <div class="singer-header">
          <img src="{{ $s->photo }}" class="singer-avatar" alt="avatar">
          <div class="singer-basic-info">
            <h2 class="singer-name" style="color:black">{{ $s->alias }}</h2>
            <div class="singer-meta">
              <span><b>Năm bắt đầu:</b> {{ $s->start_year }}</span>
              <span><b>Thể loại:</b> {{ $s->musicType->title }}</span>
              <span><b>Công ty:</b> {{ $s->company->title }}</span>
              <span><b>Trạng thái:</b> <span class="active-status">Active</span></span>
            </div>
            @if(auth()->user()->id == $user->id)
              {{-- <button class="btn-edit-singer" data-id="{{ $s->id }}">Sửa</button> --}}
              <button class="btn-delete-singer" data-id="{{ $s->id }}">Xóa</button>
            @endif
          </div>
        </div>
        <div class="singer-short-desc">
          <i>"{{ $s->summary }}"</i>
        </div>
        <div class="singer-detail-content">
          <h3>Tiểu sử & Sự nghiệp</h3>
          <p style="color:#888;">
            {{ $s->content }}
          </p>
        </div>
      
        
      </div>
    @endforeach
    <div id="singer-modal" class="singer-modal">
        <div class="singer-modal-content">
          <span class="singer-modal-close" id="close-singer-modal">&times;</span>
          <h2 style="color:black;">Thêm Ca Sĩ</h2>
          <form>
            <label>Tên Ca Sĩ</label>
            <input type="text" placeholder="Tên Ca Sĩ" name="alias">
      
            <label>Ảnh</label>
            <input type="file" accept="image/*" style="color:black;" name="photo">
            <div class="singer-img-preview" style="margin-bottom:10px;" ></div>
      
            <label>Chọn Năm Bắt Đầu</label>
            <input type="number" min="1900" max="2100" value="2025" name="start_year">
      
            <label>Chọn Thể Loại Âm Nhạc</label>
            <select style="width:100%;" name="type_id">
                @foreach ($music_type as $mt)
                    <option value="{{ $mt->id }}">{{ $mt->title }}</option>
                @endforeach
            </select>
      
            <label>Chọn Công ty âm nhạc</label>
            <select style="width:100%;" name="company_id">
                @foreach ($company as $mt)
                    <option value="{{ $mt->id }}">{{ $mt->title }}</option>
                @endforeach
            </select>
      
            <label>Mô tả ngắn</label>
            <textarea rows="2" name="summary"></textarea>
            <label>Mô tả tiểu sử</label>
            <textarea rows="3" name="content"></textarea>
            
      
            <label>Trạng thái</label>
            <select style="width:100%;" name="status">
              <option>Active</option>
              <option>Inactive</option>
            </select>
      
            <button type="submit" class="btn-save-singer">Lưu</button>
          </form>
        </div>
    </div>
</div>
<script>
// Sự kiện mở/đóng popup (nếu chưa có)
document.getElementById('open-singer-modal').onclick = function() {
  document.getElementById('singer-modal').style.display = 'flex';
};
document.getElementById('close-singer-modal').onclick = function() {
  document.getElementById('singer-modal').style.display = 'none';
};
document.getElementById('singer-modal').onclick = function(e) {
  if (e.target === this) this.style.display = 'none';
};
// Preview ảnh
const fileInput = document.querySelector('#singer-modal input[type="file"]');
if (fileInput) {
  fileInput.onchange = function(e) {
    const preview = document.querySelector('.singer-img-preview');
    if (this.files && this.files[0]) {
      const reader = new FileReader();
      reader.onload = function(ev) {
        preview.innerHTML = '<img src="' + ev.target.result + '">';
      }
      reader.readAsDataURL(this.files[0]);
    } else {
      preview.innerHTML = '';
    }
  };
}
// Bắt sự kiện submit form và gửi AJAX
const singerForm = document.querySelector('.singer-modal-content form');
if (singerForm) {
  singerForm.onsubmit = function(e) {
    e.preventDefault();
    var form = this;
    var formData = new FormData(form);
    // Gửi AJAX
    fetch('{{ route('front.singer.store') }}', { // Thay bằng route backend thực tế của bạn
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if(data.success){
        
        document.getElementById('singer-modal').style.display = 'none';
        form.reset();
        document.querySelector('.singer-img-preview').innerHTML = '';
        Notiflix.Notify.success('Tạo ca sĩ thành công!');
        setTimeout(() => {
          window.location.reload();
        }, 1000);
        // Có thể reload lại danh sách ca sĩ nếu muốn
      }else{
        Notiflix.Notify.failure('Tạo ca sĩ thất bại!');
      }
    })
    .catch(() => {
      // alert('Có lỗi xảy ra!');
      Notiflix.Notify.failure('Tạo ca sĩ thất bại!');

    });
  };
}
// Xử lý nút xóa ca sĩ với Notiflix Confirm
const deleteButtons = document.querySelectorAll('.btn-delete-singer');
deleteButtons.forEach(btn => {
  btn.onclick = function() {
    const singerId = this.dataset.id;
    console.log(singerId);
    Notiflix.Confirm.show(
      'Notiflix Confirm',
      'Bạn có chắc muốn xóa ca sĩ này?',
      'Yes',
      'No',
      () => {
        fetch('{{ route('front.singer.destroy') }}', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ id: singerId })
        })
        .then(res => res.json())
        .then(data => {
          if(data.success){
            Notiflix.Notify.success('Đã xóa ca sĩ!');
            setTimeout(() => window.location.reload(), 800);
          }else{
            Notiflix.Notify.failure('Xóa thất bại!');
          }
        })
        .catch(() => Notiflix.Notify.failure('Xóa thất bại!'));
      },
      () => {
        // Không làm gì nếu chọn No
      },
      {}
    );
  }
});
</script>