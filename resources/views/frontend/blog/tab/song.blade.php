<div class="tab-content" id="tab-song" style="display:none;">
    <div class="followers-list-container">
      <h2 class="followers-title"><i class="fa fa-music"></i> Danh sách bài hát</h2>

      @if(auth()->user()->id == $user->id)
        <button id="add-song-btn" class="zing-btn-add-song">
          <i class="fa fa-plus"></i> Thêm bài hát mới
        </button>
      @endif
      <div id="add-song-modal" class="zing-modal" style="color:black">
        <div class="zing-modal-content">
          <span class="zing-modal-close" id="close-add-song-modal">&times;</span>
          <h2 class="zing-modal-title" id="infor_form">Thêm Bài Hát Mới</h2>
          <form id="add-song-form" enctype="multipart/form-data">
            <input type="hidden" name="song_id" id="songId">
            <input type="hidden" name="resource_id" id="resourceId">
            <div class="zing-form-group">
              <label>Tên bài hát</label>
              <input type="text" id="songTitle" name="title" class="zing-input" required style="width:95%;">
            </div>
            <div class="zing-form-group">
                <label>Link bài hát</label>
                <input type="text" id="songUrl" name="resource" class="zing-input" style="width:95%;">
            </div>
            @if(Auth::user()->role === 'admin')
              <div class="zing-form-group">
                <label>Ca sĩ</label>
                <select name="singer_id" class="zing-input" required>
                      <option value="">Chọn ca sĩ</option>
                      @foreach ($singer as $s)
                          <option value="{{ $s->id }}">{{ $s->alias }}</option>
                      @endforeach
                </select>
              </div>
            @endif
            
          
            <div class="zing-form-group">
              <label>Mô tả ngắn</label>
              <textarea name="summary" class="zing-input" id="songSummary" style="width:95%;"></textarea>
            </div>
            <div class="zing-form-group">
                <label>Nội dung bài hát</label>
                <textarea row="3" name="content" class="zing-input" id="songContent" style="width:95%;"></textarea>
              </div>
            <div class="zing-form-group">
              <label>Trạng thái</label>
              <select name="status" class="zing-input" >
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>
            <button type="submit" class="zing-btn-save" id="btn-save-form">Lưu</button>
          </form>
        </div>
      </div>

      <!-- <div class="followers-search-bar">
        <input type="text" id="followers-search-input-changer" placeholder="Tìm kiếm theo tên...">
        <button id="followers-search-btn"><i class="fa fa-search"></i> Tìm kiếm</button>
      </div> -->

      <style>
      .zing-btn-add-song {
        background: linear-gradient(90deg, #4f8cff, #38b6ff);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 12px 28px;
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 20px;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(80, 143, 255, 0.15);
        transition: background 0.2s;
      }
      .zing-btn-add-song:hover {
        background: linear-gradient(90deg, #38b6ff, #4f8cff);
      }

      .zing-modal {
        display: none;
        position: fixed; z-index: 9999; left: 0; top: 0; width: 100vw; height: 100vh;
        background: rgba(0,0,0,0.25);
        align-items: center; justify-content: center;
      }
      .zing-modal-content {
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 8px 32px rgba(80, 143, 255, 0.18);
        max-width: 420px;
        width: 95%;
        margin: auto;
        padding: 32px 28px 24px 28px;
        position: relative;
        animation: zingFadeIn 0.2s;
      }
      @keyframes zingFadeIn {
        from { transform: translateY(-40px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
      }
      .zing-modal-title {
        text-align: center;
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 18px;
      }
      .zing-modal-close {
        position: absolute; top: 18px; right: 22px;
        font-size: 28px; color: #888; cursor: pointer;
        transition: color 0.2s;
      }
      .zing-modal-close:hover { color: #4f8cff; }
      .zing-form-group {
        margin-bottom: 16px;
      }
      .zing-form-group label {
        display: block;
        font-weight: 500;
        margin-bottom: 6px;
      }
      .zing-input {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #e0e0e0;
        border-radius: 7px;
        font-size: 15px;
        background: #f8fafd;
        transition: border 0.2s;
      }
      .zing-input:focus {
        border: 1.5px solid #4f8cff;
        outline: none;
        background: #fff;
      }
      .zing-btn-save {
        width: 100%;
        background: linear-gradient(90deg, #4f8cff, #38b6ff);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 12px 0;
        font-size: 17px;
        font-weight: 600;
        margin-top: 10px;
        cursor: pointer;
        transition: background 0.2s;
      }
      .zing-btn-save:hover {
        background: linear-gradient(90deg, #38b6ff, #4f8cff);
      }
      .follower-delete-btn {
        background: #ff4d4f;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 8px 18px;
        font-size: 14px;
        font-weight: 600;
        /* margin-left: 10px; */
        cursor: pointer;
        transition: background 0.2s;
      }
      .editSongBtn{
        background: #8b918f;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 8px 18px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
      }
      .editSongBtn:hover{
        background: #616b68;
      }
      .follower-delete-btn:hover {
        background: #d9363e;
      }
      .follower-share-btn {
        background: #4f8cff;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 8px 18px;
        font-size: 14px;
        font-weight: 600;
        /* margin-left: 10px; */
        cursor: pointer;
        transition: background 0.2s;
      }
      .follower-share-btn:hover {
        background: #38b6ff;
      }
      </style>

      <div class="followers-list" id="followers-list">
        
          @foreach ($allSongs as $s)
        
            @php
                $songUrl = str_replace(':8000/', '', $s->resourcesSong[0]->url);  
            @endphp
            <div class="follower-card-check">
              <img src="{{ ($s->singer->photo ?? 'storage/' . auth()->user()->photo) ? asset($s->singer->photo ?? 'storage/' . auth()->user()->photo) : 'https://i.pinimg.com/736x/bc/43/98/bc439871417621836a0eeea768d60944.jpg' }}" class="follower-avatar" alt="avatar">
              <div class="follower-info">
                <div class="follower-name">{{ $s->title }}</div>
                <div class="follower-email"><i class="fa fa-envelope"></i> {{ $s->singer->alias ?? auth()->user()->full_name }}</div>
              </div>
              <a onclick="playSong('{{ asset($songUrl) }}',
                     '{{ $s->title }}', '{{ $s->singer->alias ?? auth()->user()->full_name }}','{{ $s->singer->photo ?? 'storage/' . auth()->user()->photo }}',{{ $s->id }})" class="follower-view-btn">Nghe nhạc</a>
              
              @if(auth()->user()->id == $user->id)
                <button class="follower-delete-btn" data-id="{{ $s->id }}">Xóa</button>
                <div>
                    <button class="editSongBtn"
                        data-id="{{ $s->id }}",
                        data-resourceid="{{ $s->resourcesSong[0]->id }}"
                        data-title="{{ $s->title }}"
                        data-url="{{ $songUrl }}"
                        data-singer="{{ $s->singer_id }}"
                        data-summary="{{ $s->summary }}"
                        data-content="{{ $s->content }}"
                        data-active="{{ $s->active }}">
                        Chỉnh sửa
                    </button>
                </div>
              @endif
              <!-- <button class="follower-share-btn" data-url="{{ asset($songUrl) }}"><i class="fa-solid fa-square-share-nodes"></i></button> -->
              <a class="follower-view-btn"
              href="{{ route('front.song.share', [
                  'id' => $s->id,
                  'url' => $songUrl,
                  'ref' => request()->get('ref') // Lấy 'ref' từ request hiện tại,
                  
              ]) }}">
              <i class="fa-solid fa-share-from-square"></i>
           </a>
           
            </div>
          @endforeach  
       
      </div>
      <div class="no-followers" style="display:none;">Không tìm thấy người theo dõi nào.</div>
    </div>
    
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        var searchBtn = document.getElementById('followers-search-btn');
        var searchInput = document.getElementById('followers-search-input-changer');
        if (searchBtn && searchInput) {
          searchBtn.onclick = function() {
            var keyword = searchInput.value.toLowerCase();
            var cards = document.querySelectorAll('.follower-card-check');
            var found = false;
            cards.forEach(function(card) {
              var name = card.querySelector('.follower-name').textContent.toLowerCase();
              if (name.includes(keyword)) {
                card.style.display = '';
                found = true;
              } else {
                card.style.display = 'none';
              }
            });
            document.querySelector('.no-followers').style.display = found ? 'none' : 'block';
          };
          searchInput.oninput = function() {
            searchBtn.click();
          };
        }
      });


      document.getElementById('add-song-btn').onclick = function() {
        document.getElementById('add-song-modal').style.display = 'flex';
      };
      document.getElementById('close-add-song-modal').onclick = function() {
        document.getElementById('add-song-modal').style.display = 'none';
      };
      window.onclick = function(event) {
        var modal = document.getElementById('add-song-modal');
        if (event.target == modal) {
          modal.style.display = "none";
        }
      };

      // Sự kiện nhất nút chỉnh sửa
      document.querySelectorAll('.editSongBtn').forEach(button => {
          button.addEventListener('click', function() {
              // Lấy dữ liệu từ data-attributes
              const id = this.dataset.id;
              const resource_id = this.dataset.resourceid;
              const title = this.dataset.title;
              const url = this.dataset.url;
              const singer = this.dataset.singer;
              const summary = this.dataset.summary;
              const content = this.dataset.content;
              const active = this.dataset.active;

              document.getElementById('songId').value = id;
              document.getElementById('resourceId').value = resource_id;
              document.getElementById('songTitle').value = title;
              document.getElementById('songUrl').value = url;
              document.getElementById('songSummary').value = summary;
              document.getElementById('songContent').value = content;
              document.getElementById('infor_form').innerHTML = "Chỉnh sửa bài hát";
              document.getElementById('btn-save-form').innerHTML = "Lưu chỉnh sửa";
              document.getElementById('add-song-modal').style.display = 'flex';
          });
      });

      // Sự kiện submit form thêm bài hát mới
      const addSongForm = document.getElementById('add-song-form');
      addSongForm.onsubmit = function(e) {
        e.preventDefault();
        var formData = new FormData(addSongForm);
        fetch('{{ route('front.song.store') }}', {
          method: 'POST',
          body: formData,
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          }
        })
        .then(response => response.json())
        .then(data => {
          if(data.success){
            document.getElementById('add-song-modal').style.display = 'none';
            addSongForm.reset();
            Notiflix.Notify.success(data.message);
            setTimeout(() => {
              window.location.reload();
            }, 1000);
          } else {
            Notiflix.Notify.failure('Thêm bài hát thất bại!');
          }
        })
        .catch(error => {
          Notiflix.Notify.failure('Lỗi kết nối hoặc dữ liệu không hợp lệ!');
      
        });
      };

      // Sự kiện xóa bài hát
      document.querySelectorAll('.follower-delete-btn').forEach(function(btn) {
        btn.onclick = function() {
          Notiflix.Confirm.show(
            'Xác nhận xóa',
            'Bạn có chắc chắn muốn xóa bài hát này?',
            'Xóa',
            'Hủy',
            function() {
              fetch('{{ route('front.song.destroy', ['id' => 'SONG_ID']) }}'.replace('SONG_ID', btn.dataset.id), {
                method: 'POST',
                headers: {
                  'X-CSRF-TOKEN': '{{ csrf_token() }}',
                  'Accept': 'application/json'
                }
              })
              .then(response => response.json())
              .then(data => {
                if(data.success){
                  Notiflix.Notify.success('Xóa bài hát thành công!');
                  setTimeout(() => {
                    window.location.reload();
                  }, 1000);
                } else {
                  Notiflix.Notify.failure('Xóa bài hát thất bại!');
                }
              })
              .catch(error => {
                Notiflix.Notify.failure('Lỗi kết nối hoặc dữ liệu không hợp lệ!');
              });
            },
            function() {
              // Không làm gì khi bấm Hủy
            }
          );
        }
      });

      // Sự kiện chia sẻ bài hát
      document.querySelectorAll('.follower-share-btn').forEach(function(btn) {
        btn.onclick = function() {
          const url = btn.dataset.url;
          navigator.clipboard.writeText(url).then(function() {
            Notiflix.Notify.success('Đã copy link bài hát!');
          }, function() {
            Notiflix.Notify.failure('Không thể copy link!');
          });
        }
      });
    </script>
  </div>