@extends('frontend.layouts.master')
@section('content')

@include('frontend.blog.css.css')

<div class="profile-container">
  <div class="profile-header-modern" style="background: url('{{ asset('images/background.jpg') }}') no-repeat center center; background-size: cover;">
    <img src="{{$user->photo ? asset('storage/' . $user->photo) : 'https://i.pinimg.com/736x/bc/43/98/bc439871417621836a0eeea768d60944.jpg'}}" alt="Avatar" class="avatar-modern">
    <div class="profile-info-modern">
      <h2 style="display:flex; width:100%; justify-content:space-between;">
        {{ $user->full_name }} 
        @if(auth()->user()->id != $user->id)
          <div>
            <button class="follow-btn" data-id="{{ $user->id }}">
                @if ($following->contains('user_id', auth()->user()->id))
                  <i class="fa fa-user-check" style="margin-left:10px;"></i>Đang theo dõi
                @else
                  <i class="fa fa-user-plus" style="margin-left:10px;"></i> Theo dõi
                @endif
              </button>
          </div>
        @endif
      </h2>
      <div class="job-title">{{ $user->role == 'customer' ? '(Người dùng)' : '(Quản trị viên)' }}</div>
      <div class="profile-stats-modern">
        <div><strong>{{ $post_user->count() }}</strong><span> Bài đăng của tôi</span></div>
        <div><strong>{{ $user->monitor ?? 0 }}</strong><span>Người theo dõi</span></div>
        <div><strong>{{ $user->following ?? 0 }}</strong><span>Đang theo dõi</span></div>
      </div>
    
        
    </div>
  </div>
  <div class="profile-header-modern-bg" >

  </div>
  @if($user->id == auth()->user()->id)
    <div class="profile-tabs" id="profile-tabs">
      <a href="#" class="tab-link active" data-tab="profile">Trang chủ</a>
      <a href="#" class="tab-link" data-tab="followers">{{ $follow->count() }} Người đang theo dõi</a>
      <a href="#" class="tab-link" data-tab="friends">Bài viết của {{ $user->full_name }}</a>
      <a href="#" class="tab-link" data-tab="albums">Ảnh của {{ $user->full_name }}</a>
      <a href="#" class="tab-link" data-tab="gallery">Thông tin cá nhân</a>
      {{-- <a href="#" class="tab-link" data-tab="sing">Hồ sơ ca sĩ</a> --}}
      <a href="#" class="tab-link" data-tab="song">Danh sách bài hát</a>

    </div>
  @else
    <div class="profile-tabs" id="profile-tabs">
      <a href="#" class="tab-link active" data-tab="friends">Bài viết của {{ $user->full_name }}</a>
      <a href="#" class="tab-link" data-tab="followers">{{ $follow->count() }} Người đang theo dõi</a> 
      <a href="#" class="tab-link" data-tab="albums">Ảnh của {{ $user->full_name }}</a>
      {{-- <a href="#" class="tab-link" data-tab="sing">Hồ sơ ca sĩ</a> --}}
      <a href="#" class="tab-link" data-tab="song">Danh sách bài hát</a>
    </div>
  @endif
  <!-- <div class="profile-search-bar">
    <form id="profile-search-form" style="display:flex;align-items:center;gap:10px;">
      <input type="text" id="profile-search-input" name="q" placeholder="Tìm kiếm bài viết, bạn bè, nhạc..." autocomplete="off">
      <button type="submit"><i class="fa fa-search"></i> Tìm kiếm</button>
    </form>
  </div> -->
  
  @include('frontend.blog.tab.profile')
  @include('frontend.blog.tab.followers')
  @include('frontend.blog.tab.friend')
  @include('frontend.blog.tab.gallery')
  @include('frontend.blog.tab.albums')
  @include('frontend.blog.tab.sing')
  @include('frontend.blog.tab.song')


  <div id="image-lightbox" style="display:none;position:fixed;z-index:9999;left:0;top:0;width:100vw;height:100vh;background:rgba(24,13,38,0.85);align-items:center;justify-content:center;">
    <span id="close-lightbox" style="position:absolute;top:30px;right:40px;font-size:40px;color:#fff;cursor:pointer;z-index:10001;">&times;</span>
    <img id="lightbox-img" src="" style="max-width:90vw;max-height:80vh;border-radius:12px;box-shadow:0 8px 32px rgba(0,0,0,0.25);">
  </div>
  <script>
  
    document.addEventListener('DOMContentLoaded', function() {
      const tabLinks = document.querySelectorAll('.tab-link');
      const tabContents = document.querySelectorAll('.tab-content');
      tabLinks.forEach(link => {
        link.addEventListener('click', function(e) {
          e.preventDefault();
          tabLinks.forEach(l => l.classList.remove('active'));
          this.classList.add('active');
          const tab = this.getAttribute('data-tab');
          tabContents.forEach(content => {
            content.style.display = content.id === 'tab-' + tab ? 'block' : 'none';
          });
        });
      });

      const textarea = document.querySelector('.status-box textarea');
      const preview = document.getElementById('youtube-preview');
      let lastUrl = '';

      // Photo/Video upload logic
      const photoBtn = document.getElementById('photo-btn');
      const photoInput = document.getElementById('photo-input');
      const photoPreview = document.getElementById('photo-preview');
      const postBtn = document.getElementById('post-btn');

      photoBtn.addEventListener('click', function() {
        photoInput.click();
      });

      photoInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
          const reader = new FileReader();
          reader.onload = function(e) {
            photoPreview.innerHTML = `<div style='position:relative;display:inline-block;'><img src='${e.target.result}' style='max-width:320px;max-height:200px;border-radius:8px;box-shadow:0 2px 8px #0002;'><button id='close-photo-preview' style='position:absolute;top:8px;right:8px;background:rgba(0,0,0,0.5);color:#fff;border:none;border-radius:50%;width:28px;height:28px;cursor:pointer;font-size:16px;'>&times;</button></div>`;
            photoPreview.style.display = 'block';
            preview.style.display = 'none';
            lastUrl = '';
            setTimeout(() => {
              const closeBtn = document.getElementById('close-photo-preview');
              if (closeBtn) {
                closeBtn.onclick = function() {
                  photoPreview.style.display = 'none';
                  photoInput.value = '';
                }
              }
            }, 100);
          }
          reader.readAsDataURL(this.files[0]);
        }
      });

      function extractYoutubeUrl(text) {
        const regex = /(https?:\/\/(?:www\.)?(?:youtube\.com\/watch\?v=|youtu\.be\/)[^\s]+)/;
        const match = text.match(regex);
        return match ? match[1] : null;
      }

      textarea.addEventListener('input', function() {
        if (photoPreview.style.display === 'block') return; // Ưu tiên ảnh
        const url = extractYoutubeUrl(this.value);
        if (url && url !== lastUrl) {
          lastUrl = url;
          fetch(`https://www.youtube.com/oembed?url=${encodeURIComponent(url)}&format=json`)
            .then(res => res.json())
            .then(data => {
              preview.innerHTML = `
                <div style=\"background:#222;border-radius:10px;overflow:hidden;max-width:480px;box-shadow:0 2px 8px #0003;position:relative;\">
                  <button id=\"close-preview\" style=\"position:absolute;top:10px;right:10px;background:rgba(0,0,0,0.5);color:#fff;border:none;border-radius:50%;width:32px;height:32px;cursor:pointer;font-size:20px;z-index:2;\">&times;</button>
                  <img src=\"${data.thumbnail_url}\" style=\"width:100%;display:block;max-height:240px;object-fit:cover;\">
                  <div style=\"padding:16px 18px 12px 18px;\">
                    <div style=\"color:#aaa;font-size:13px;letter-spacing:1px;margin-bottom:2px;\">YOUTUBE.COM</div>
                    <div style=\"font-weight:bold;font-size:1.15em;color:#fff;margin-bottom:6px;\">${data.title}</div>
                    <div style=\"color:#ccc;font-size:0.98em;line-height:1.4;\">${data.author_name}</div>
                  </div>
                </div>
              `;
              preview.style.display = 'block';
              setTimeout(() => {
                const closeBtn = document.getElementById('close-preview');
                if (closeBtn) {
                  closeBtn.onclick = function() {
                    preview.style.display = 'none';
                    lastUrl = '';
                  }
                }
              }, 100);
            })
            .catch(() => {
              preview.style.display = 'none';
            });
        } else if (!url) {
          preview.style.display = 'none';
          lastUrl = '';
        }
      });

      postBtn.addEventListener('click', function() {
        const text = textarea.value;
        // Regex lấy link nhạc phổ biến
        const musicRegex = /(https?:\/\/(?:www\.)?(?:youtube\.com\/watch\?v=|youtu\.be\/|soundcloud\.com\/|zingmp3\.vn\/|nhaccuatui\.com\/)[^\s]+)/gi;
        const links = text.match(musicRegex) || [];
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('content', text);
        formData.append('music_links', JSON.stringify(links));
        if (photoInput.files && photoInput.files[0]) {
          formData.append('image', photoInput.files[0]);
        }
        $.ajax({
          url: '{{ route("front.blog.post") }}',
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function(res) {
            Notiflix.Notify.success('Đăng bài thành công!');
            setTimeout(() => {
              window.location.reload();
            }, 1000);
          },
          error: function(xhr) {
            Notiflix.Notify.failure('Lỗi xảy ra!');
          }
        });
      });

      // Like button AJAX
      document.querySelectorAll('.like-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
          const postId = this.getAttribute('data-id');
          $.ajax({
            url: '{{ route("front.blog.like") }}', // Đổi thành route xử lý like của bạn
            type: 'POST',
            data: {
              _token: '{{ csrf_token() }}',
              post_id: postId,
              status: 'like'
            },
            success: function(res) {
              Notiflix.Notify.success('Đã like bài viết!');
           
              const likeNumber = btn.querySelector('b');
              if (likeNumber) {
                let current = parseInt(likeNumber.textContent.replace(/\D/g, '')) || 0;
                likeNumber.textContent = current + 1;
              }
            
              const icon = btn.querySelector('i');
              if (icon) {
                icon.style.color = '#1877f2';
              }
              setTimeout(() => {
                window.location.reload();
              }, 1000);
            },
            error: function(xhr) {
              const likeNumber = btn.querySelector('b');
              if (likeNumber) {
                let current = parseInt(likeNumber.textContent.replace(/\D/g, '')) || 0;
                likeNumber.textContent = current - 1;
              }
              Notiflix.Notify.success('Đã hủy like bài viết!');
              const icon = btn.querySelector('i');
              if (icon) {
                icon.style.color = '#555';
              }
              setTimeout(() => {
                window.location.reload();
              }, 1000);
            }
          });
        });
      });

      // Lightbox cho ảnh post
      document.querySelectorAll('.post-image').forEach(function(img) {
        img.addEventListener('click', function() {
          document.getElementById('lightbox-img').src = this.src;
          document.getElementById('image-lightbox').style.display = 'flex';
        });
      });
      document.getElementById('close-lightbox').onclick = function() {
        document.getElementById('image-lightbox').style.display = 'none';
        document.getElementById('lightbox-img').src = '';
      };
      document.getElementById('image-lightbox').onclick = function(e) {
        if (e.target === this) {
          this.style.display = 'none';
          document.getElementById('lightbox-img').src = '';
        }
      };

      // Hiện textarea nhập bình luận khi bấm vào nút bình luận
      document.querySelectorAll('.comment-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
          var postId = this.getAttribute('data-id');
          // Ẩn tất cả các box bình luận khác
          document.querySelectorAll('.comment-input-box').forEach(function(box) {
            box.style.display = 'none';
          });
          // Hiện box bình luận của post này
          var inputBox = document.getElementById('comment-input-' + postId);
          if (inputBox) {
            inputBox.style.display = (inputBox.style.display === 'none' || inputBox.style.display === '') ? 'block' : 'none';
            if (inputBox.style.display === 'block') {
              inputBox.querySelector('textarea').focus();
            }
          }
        });
      });
      document.querySelectorAll('.comment-btn-about').forEach(function(btn) {
        btn.addEventListener('click', function() {
          var postId = this.getAttribute('data-id');
          // Ẩn tất cả các box bình luận khác
          document.querySelectorAll('.comment-input-box').forEach(function(box) {
            box.style.display = 'none';
          });
          // Hiện box bình luận của post này
          var inputBox = document.getElementById('comment-input-about-' + postId);
      
          if (inputBox) {
            inputBox.style.display = (inputBox.style.display === 'none' || inputBox.style.display === '') ? 'block' : 'none';
            if (inputBox.style.display === 'block') {
              inputBox.querySelector('textarea').focus();
            }
          }
        });
      });
      $(document).on('click', '.send-comment-btn-about', function() {
        var btn = $(this);
        var postId = btn.data('id');
        var inputBox = $('#comment-input-about-' + postId);
        var textarea = inputBox.find('textarea');
        var content = textarea.val().trim();
        if (!content) return;
        btn.prop('disabled', true);
        $.ajax({
          url: '{{ route('front.blog.comment') }}',
          type: 'POST',
          data: {
            _token: '{{ csrf_token() }}',
            post_id: postId,
            content: content
          },
          success: function(res) {
         
            btn.prop('disabled', false);
            if(res.success) {
              console.log(res.data);
              Notiflix.Notify.success('Bình luận thành công!');
              console.log(res.data.user.photo);
              var html = `<div class=\"comment\" style=\"display:flex; gap:10px; margin-bottom:10px;\">`
                + `<img src="{{ asset('storage/') }}/${res.data.user.photo || 'https://randomuser.me/api/portraits/men/32.jpg'}\" alt=\"\" class=\"avatar-small\">`
                + `<div>`
                + `<b>${res.data.user_name || 'Bạn'}</b> <span style=\"color:#888; font-size:0.9em;\">Vừa xong</span>`
                + `<div style="padding:10px;">${$('<div>').text(res.data.content).html()}</div>`
                + `<div style=\"margin-top:4px;display:flex;gap:12px;align-items:center;\">`
                + `<button data-id=\"${res.data.id}\" data-liked=\"0\" data-count=\"0\" class=\"comment-like-btn\" style=\"background:none;border:none;color:#888;cursor:pointer;font-size:1em;display:flex;align-items:center;gap:4px;\"><i class=\"fa fa-thumbs-up\"></i> <span class=\"like-count\">0</span> Lượt thích</button>`
                + `<button data-id=\"${res.data.id}\" class=\"comment-reply-btn\" style=\"background:none;border:none;color:#888;cursor:pointer;font-size:1em;display:flex;align-items:center;gap:4px;\">0 Lượt Trả lời</button>`
                + `</div>`
                + `</div>`
                + `</div>`;
              // Thêm vào đầu .comments
              btn.closest('.comments').prepend(html);
              textarea.val('');
              inputBox.hide();
              setTimeout(() => {
                window.location.reload();
              }, 1000);
            } else {
              Notiflix.Notify.failure('Bình luận thất bại!');
            }
          },
          error: function(xhr) {
            btn.prop('disabled', false);
            Notiflix.Notify.failure('Bình luận thất bại!');
          }
        });
      });
      // Xử lý gửi bình luận AJAX cho từng post
      $(document).on('click', '.send-comment-btn', function() {
        var btn = $(this);
        var postId = btn.data('id');
        var inputBox = $('#comment-input-' + postId);
        var textarea = inputBox.find('textarea');
        var content = textarea.val().trim();
        if (!content) return;
        btn.prop('disabled', true);
        $.ajax({
          url: '{{ route('front.blog.comment') }}',
          type: 'POST',
          data: {
            _token: '{{ csrf_token() }}',
            post_id: postId,
            content: content
          },
          success: function(res) {
         
            btn.prop('disabled', false);
            if(res.success) {
              console.log(res.data);
              Notiflix.Notify.success('Bình luận thành công!');
              console.log(res.data.user.photo);
              var html = `<div class=\"comment\" style=\"display:flex; gap:10px; margin-bottom:10px;\">`
                + `<img src="{{ asset('storage/') }}/${res.data.user.photo || 'https://randomuser.me/api/portraits/men/32.jpg'}\" alt=\"\" class=\"avatar-small\">`
                + `<div>`
                + `<b>${res.data.user_name || 'Bạn'}</b> <span style=\"color:#888; font-size:0.9em;\">Vừa xong</span>`
                + `<div style="padding:10px;">${$('<div>').text(res.data.content).html()}</div>`
                + `<div style=\"margin-top:4px;display:flex;gap:12px;align-items:center;\">`
                + `<button data-id=\"${res.data.id}\" data-liked=\"0\" data-count=\"0\" class=\"comment-like-btn\" style=\"background:none;border:none;color:#888;cursor:pointer;font-size:1em;display:flex;align-items:center;gap:4px;\"><i class=\"fa fa-thumbs-up\"></i> <span class=\"like-count\">0</span> Lượt thích</button>`
                + `<button data-id=\"${res.data.id}\" class=\"comment-reply-btn\" style=\"background:none;border:none;color:#888;cursor:pointer;font-size:1em;display:flex;align-items:center;gap:4px;\">0 Lượt Trả lời</button>`
                + `</div>`
                + `</div>`
                + `</div>`;
              // Thêm vào đầu .comments
              btn.closest('.comments').prepend(html);
              textarea.val('');
              inputBox.hide();
              setTimeout(() => {
                window.location.reload();
              }, 1000);
            } else {
              Notiflix.Notify.failure('Bình luận thất bại!');
            }
          },
          error: function(xhr) {
            btn.prop('disabled', false);
            Notiflix.Notify.failure('Bình luận thất bại!');
          }
        });
      });

      // Xử lý xoá bài viết bằng AJAX
      $(document).on('click', '.delete-post-btn', function() {
        var postId = $(this).data('id');
        if (!confirm('Bạn có chắc muốn xóa bài viết này?')) return;
        var btn = $(this);
        $.ajax({
          url: '{{ route("front.blog.delete") }}', // Cần tạo route xoá bài viết POST/DELETE
          type: 'DELETE',
          data: {
            _token: '{{ csrf_token() }}',
            post_id: postId
          },
          success: function(res) {
            Notiflix.Notify.success('Đã xoá bài viết!');
            // Xoá post khỏi DOM
            btn.closest('.post').remove();
            setTimeout(() => {
                  window.location.reload();
                }, 1000);
          },
          error: function(xhr) {
            Notiflix.Notify.failure('Xoá bài viết thất bại!');
          }
        });
      });

      // Chỉnh sửa mô tả bài viết tại chỗ
      $(document).on('click', '.post-description', function() {
        var postId = $(this).data('id');
        $(this).hide();
        $('#edit-description-' + postId).show();
      });
      $(document).on('click', '.cancel-description-btn', function() {
        var postId = $(this).data('id');
        $('#edit-description-' + postId).hide();
        $('.post-description[data-id="' + postId + '"]').show();
      });
      $(document).on('click', '.save-description-btn', function() {
        var postId = $(this).data('id');
        var box = $('#edit-description-' + postId);
        var textarea = box.find('textarea');
        var newContent = textarea.val().trim();
        var photoInput = box.find('.edit-photo-input')[0];
        if (!newContent) return Notiflix.Notify.failure('Nội dung không được để trống!');
        var btn = $(this);
        btn.prop('disabled', true).text('Đang lưu...');
        // Regex lấy link nhạc phổ biến
        const musicRegex = /(https?:\/\/(?:www\.)?(?:youtube\.com\/watch\?v=|youtu\.be\/|soundcloud\.com\/|zingmp3\.vn\/|nhaccuatui\.com\/)[^\s]+)/gi;
        const links = newContent.match(musicRegex) || [];
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('post_id', postId);
        formData.append('description', newContent);
        formData.append('music_links', JSON.stringify(links));
        if (photoInput && photoInput.files && photoInput.files[0]) {
          formData.append('image', photoInput.files[0]);
        }
        $.ajax({
          url: '{{ route("front.blog.edit") }}',
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function(res) {
            Notiflix.Notify.success('Đã cập nhật bài viết!');
            $('.post-description[data-id="' + postId + '"]').html(newContent).show();
            box.hide();
            btn.prop('disabled', false).text('Lưu');
            // Reset preview
            $('#edit-photo-preview-' + postId).hide().html('');
            box.find('.edit-photo-input').val('');
            box.find('.edit-youtube-preview').hide().html('');
            box.find('.edit-photo-preview').hide().html('');
            setTimeout(() => {
                  window.location.reload();
                }, 1000);
          },
          error: function(xhr) {
            Notiflix.Notify.failure('Cập nhật thất bại!');
            btn.prop('disabled', false).text('Lưu');
          }
        });
      });

      // Khi click vào icon chỉnh sửa (bút), hiện textarea sửa mô tả
      $(document).on('click', '.edit-post-btn', function(e) {
        e.preventDefault();
        var postId = $(this).data('id');
        // Ẩn mọi form sửa khác nếu có
        $('.edit-description-box').hide();
        $('.post-description').show();
        // Hiện form sửa cho post này
        $('#edit-description-' + postId).show();
        $('.post-description[data-id="' + postId + '"]').hide();
      });

      // --- Photo/Video upload logic for EDIT (giống status-box) ---
      $(document).on('click', '.edit-photo-btn', function() {
        var postId = $(this).data('id');
        var input = document.querySelector('.edit-photo-input[data-id="' + postId + '"]');
        if (input) input.click();
      });
      $(document).on('change', '.edit-photo-input', function() {
        var postId = $(this).data('id');
        var input = this;
        var previewBox = document.getElementById('edit-photo-preview-' + postId);
        var youtubePreview = $(previewBox).closest('.edit-description-box').find('.edit-youtube-preview');
        if (input.files && input.files[0]) {
          const reader = new FileReader();
          reader.onload = function(e) {
            previewBox.innerHTML = `<div style='position:relative;display:inline-block;'><img src='${e.target.result}' style='max-width:320px;max-height:200px;border-radius:8px;box-shadow:0 2px 8px #0002;'><button class='close-edit-photo-preview' data-id='${postId}' style='position:absolute;top:8px;right:8px;background:rgba(0,0,0,0.5);color:#fff;border:none;border-radius:50%;width:28px;height:28px;cursor:pointer;font-size:16px;'>&times;</button></div>`;
            previewBox.style.display = 'block';
            // Ẩn preview ảnh từ link nếu có
            $(previewBox).closest('.edit-description-box').find('.edit-photo-preview').hide();
            // Ẩn luôn preview youtube nếu có link
            youtubePreview.hide();
          }
          reader.readAsDataURL(input.files[0]);
        }
      });
      $(document).on('click', '.close-edit-photo-preview', function() {
        var postId = $(this).data('id');
        var previewBox = document.getElementById('edit-photo-preview-' + postId);
        previewBox.style.display = 'none';
        previewBox.innerHTML = '';
        var input = document.querySelector('.edit-photo-input[data-id="' + postId + '"]');
        if (input) input.value = '';
        // Cho phép hiện lại preview youtube nếu có link
        var youtubePreview = $(previewBox).closest('.edit-description-box').find('.edit-youtube-preview');
        youtubePreview.show();
      });

      // Preview YouTube và ảnh cho textarea chỉnh sửa bài viết
      $(document).on('input', '.edit-description-textarea', function() {
        var textarea = $(this);
        var box = textarea.closest('.edit-description-box');
        var text = textarea.val();
        var preview = box.find('.edit-youtube-preview');
        var photoPreview = box.find('.edit-photo-preview');
        var postId = box.attr('id').replace('edit-description-', '');
        var uploadPreviewBox = document.getElementById('edit-photo-preview-' + postId);
        // Nếu có ảnh upload thì ẩn luôn preview youtube
        if (uploadPreviewBox && uploadPreviewBox.style.display !== 'none') {
          preview.hide();
        } else {
          // YouTube preview
          function extractYoutubeUrl(text) {
            const regex = /(https?:\/\/(?:www\.)?(?:youtube\.com\/watch\?v=|youtu\.be\/)[^\s]+)/;
            const match = text.match(regex);
            return match ? match[1] : null;
          }
          var url = extractYoutubeUrl(text);
          if (url) {
            fetch(`https://www.youtube.com/oembed?url=${encodeURIComponent(url)}&format=json`)
              .then(res => res.json())
              .then(data => {
                preview.html(`
                  <div style=\"background:#222;border-radius:10px;overflow:hidden;max-width:480px;box-shadow:0 2px 8px #0003;position:relative;\">
                    <button class=\"close-edit-preview\" style=\"position:absolute;top:10px;right:10px;background:rgba(0,0,0,0.5);color:#fff;border:none;border-radius:50%;width:32px;height:32px;cursor:pointer;font-size:20px;z-index:2;\">&times;</button>
                    <img src=\"${data.thumbnail_url}\" style=\"width:100%;display:block;max-height:240px;object-fit:cover;\">
                    <div style=\"padding:16px 18px 12px 18px;\">
                      <div style=\"color:#aaa;font-size:13px;letter-spacing:1px;margin-bottom:2px;\">YOUTUBE.COM</div>
                      <div style=\"font-weight:bold;font-size:1.15em;color:#fff;margin-bottom:6px;\">${data.title}</div>
                      <div style=\"color:#ccc;font-size:0.98em;line-height:1.4;\">${data.author_name}</div>
                    </div>
                  </div>
                `);
                preview.show();
                photoPreview.hide();
               
              })
              .catch(() => {
                preview.hide();
              });
          } else {
            preview.hide();
          }
        }
        // Ảnh preview nếu có link ảnh, nhưng chỉ hiện nếu chưa chọn ảnh upload
        const imgRegex = /(https?:\/\/(?:www\.)?[^\s]+\.(?:jpg|jpeg|png|gif))/i;
        const imgMatch = text.match(imgRegex);
        if (imgMatch && (!uploadPreviewBox || uploadPreviewBox.style.display === 'none')) {
          photoPreview.html(`<img src='${imgMatch[1]}' style='max-width:320px;max-height:200px;border-radius:8px;box-shadow:0 2px 8px #0002;'>`);
          photoPreview.show();
        } else {
          photoPreview.hide();
        }
      });

      // Đóng preview
      $(document).on('click', '.close-edit-preview', function() {
        $(this).closest('.edit-youtube-preview').hide();
      });

      // Like button AJAX cho comment
      $(document).on('click', '.comment-like-btn', function() {
        var btn = $(this);
        var icon = btn.find('i');
        var countSpan = btn.find('.like-count');
        var liked = btn.hasClass('liked');
        var count = parseInt(countSpan.text()) || 0;
        var commentId = btn.data('id');
        $.ajax({
          url: '{{ route('front.blog.likeComment') }}',
          type: 'POST',
          data: {
            _token: '{{ csrf_token() }}',
            comment_id: commentId
          },
          success: function(res) {
            console.log(res);
            if (res.success) {
              count++;
              btn.addClass('liked');
              icon.css('color', '#1877f2');
              Notiflix.Notify.success('Bình luận đã được like!');
              setTimeout(() => {
                window.location.reload();
              }, 1000);
            } else {
              count = Math.max(0, count - 1);
              btn.removeClass('liked');
              icon.css('color', '#888');
              Notiflix.Notify.failure('Bình luận đã hủy like!');
              setTimeout(() => {
                window.location.reload();
              }, 1000);
            }
            countSpan.text(count);
          },
          error: function(xhr) {
              count--;
              btn.removeClass('liked');
              icon.css('color', '#888');
              Notiflix.Notify.failure('Bình luận đã hủy like!');
              setTimeout(() => {
                window.location.reload();
              }, 1000);
          }
        });
      });

      // Preview ảnh đại diện khi chọn file
      $(document).on('change', '#profile-photo-input', function() {
        var input = this;
        var img = document.getElementById('profile-photo-img');
        if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload = function(e) {
            img.src = e.target.result;
          }
          reader.readAsDataURL(input.files[0]);
        }
      });

      // Submit cập nhật thông tin cá nhân bằng AJAX
      $(document).on('submit', '#profile-update-form', function(e) {
        e.preventDefault();
        var form = this;
        var formData = new FormData(form);
        $.ajax({
          url: '{{ route('front.profile.updateProfile') }}', // Đảm bảo có route này ở backend
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function(res) {
            Notiflix.Notify.success('Cập nhật thông tin thành công!');
            setTimeout(function() { window.location.reload(); }, 1000);
          },
          error: function(xhr) {
            Notiflix.Notify.failure('Cập nhật thất bại!');
          }
        });
      });

      // Xóa ảnh trong sidebar
      $(document).on('click', '.photo-delete-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var btn = $(this);
        var id = btn.data('id');
        if (!confirm('Bạn có chắc muốn xóa ảnh này?')) return;
        $.ajax({
          url: '{{ route("front.profile.deletePhoto") }}',
          type: 'POST',
          data: {
            _token: '{{ csrf_token() }}',
            id: id
          },
          success: function(res) {
            Notiflix.Notify.success('Đã xóa ảnh!');
            btn.closest('.photo-item').remove();
          },
          error: function(xhr) {
            Notiflix.Notify.failure('Xóa ảnh thất bại!');
          }
        });
      });

      // Xóa ảnh trong modern-gallery
      $(document).on('click', '.modern-gallery-delete', function(e) {
        e.stopPropagation();
        var btn = $(this);
        var id = btn.data('id');
        Notiflix.Confirm.show(
          'Xác nhận xóa',
          'Bạn có chắc muốn xóa ảnh này?',
          'Xóa',
          'Hủy',
          function() {
            $.ajax({
              url: '{{ route("front.profile.deletePhoto") }}',
              type: 'POST',
              data: {
                _token: '{{ csrf_token() }}',
                id: id
              },
              success: function(res) {
                Notiflix.Notify.success('Đã xóa ảnh!');
                btn.closest('.modern-gallery-item').remove();
                window.location.reload();
              },
              error: function(xhr) {
                Notiflix.Notify.failure('Xóa ảnh thất bại!');
              }
            });
          },
          function() {
            // Không làm gì khi hủy
          }
        );
      });

      // Hiển thị lightbox khi click vào ảnh trong avatar-gallery (có prev/next)
      const images = Array.from(document.querySelectorAll('#avatar-gallery .modern-gallery-img'));
      let currentIndex = 0;
      function showLightbox(index) {
        if (index < 0) index = images.length - 1;
        if (index >= images.length) index = 0;
        currentIndex = index;
        document.getElementById('avatar-lightbox-img').src = images[currentIndex].src;
        document.getElementById('avatar-lightbox').style.display = 'flex';
      }
      images.forEach((img, idx) => {
        img.addEventListener('click', function() {
          showLightbox(idx);
        });
      });
      document.getElementById('close-avatar-lightbox').onclick = function() {
        document.getElementById('avatar-lightbox').style.display = 'none';
        document.getElementById('avatar-lightbox-img').src = '';
      };
      document.getElementById('avatar-lightbox').onclick = function(e) {
        if (e.target === this) {
          this.style.display = 'none';
          document.getElementById('avatar-lightbox-img').src = '';
        }
      };
      document.getElementById('avatar-lightbox-prev').onclick = function(e) {
        e.stopPropagation();
        showLightbox(currentIndex - 1);
      };
      document.getElementById('avatar-lightbox-next').onclick = function(e) {
        e.stopPropagation();
        showLightbox(currentIndex + 1);
      };

      // Highlight link in post description when clicked
      document.addEventListener('click', function(e) {
        if (e.target.tagName === 'A' && e.target.closest('.post-content')) {
          document.querySelectorAll('.post-content a').forEach(function(link) {
            link.style.backgroundColor = '';
            link.style.color = '';
          });
          e.target.style.backgroundColor = '#d0f5e8';
          e.target.style.color = '#007bff';
        }
      });

      // Hiện textarea trả lời khi bấm "Lượt Trả lời"
      $(document).on('click', '.comment-reply-btn', function() {
        var commentId = $(this).data('id');
        $('.reply-input-box').hide();
        $('#reply-input-' + commentId).toggle();
        if ($('#reply-input-' + commentId).is(':visible')) {
          $('#reply-input-' + commentId + ' textarea').focus();
        }
      });
      // Gửi trả lời
      $(document).on('click', '.send-reply-btn', function() {
        var btn = $(this);
        var commentId = btn.data('id');
        var inputBox = $('#reply-input-' + commentId);
        var textarea = inputBox.find('textarea');
        var content = textarea.val().trim();
        if (!content) return;
        btn.prop('disabled', true);
        $.ajax({
          url: '{{ route('front.blog.replyComment') }}', // Tạo route này ở backend
          type: 'POST',
          data: {
            _token: '{{ csrf_token() }}',
            comment_id: commentId,
            content: content
          },
          success: function(res) {
            btn.prop('disabled', false);
            if(res.success) {
              Notiflix.Notify.success('Trả lời thành công!');
              textarea.val('');
              inputBox.hide();
              setTimeout(() => {
                window.location.reload();
              }, 1000);
            } else {
              Notiflix.Notify.failure('Trả lời thất bại!');
            }
          },
          error: function(xhr) {
            btn.prop('disabled', false);
            Notiflix.Notify.failure('Trả lời thất bại!');
          }
        });
      });

      $(document).on('click', '.follow-btn', function() {
        var btn = $(this);
        var userId = btn.data('id');
        if (btn.prop('disabled')) return;
        btn.prop('disabled', true);
        $.ajax({
          url: '{{ route("front.profile.toggleFollow") }}',
          type: 'POST',
          data: {
            _token: '{{ csrf_token() }}',
            user_id: userId
          },
          success: function(res) {
            console.log(res);
            if (res.success && res.following) {
              btn.html('<i class="fa fa-user-check" style="margin-left:10px;"></i> Đã theo dõi');
              btn.addClass('followed');
              Notiflix.Notify.success('Đã theo dõi!');
            } else if (res.success && !res.following) {
              btn.html('<i class="fa fa-user-plus" style="margin-left:10px;"></i> Theo dõi');
              btn.removeClass('followed');
              Notiflix.Notify.failure('Đã hủy theo dõi!');
            } else {
              Notiflix.Notify.failure('Có lỗi xảy ra!');
            }
            btn.prop('disabled', false);
            setTimeout(() => {
              window.location.reload();
            }, 1000);
          },
          error: function() {
            Notiflix.Notify.failure('Có lỗi xảy ra!');
            btn.prop('disabled', false);
          }
        });
      });

    });
    

    function playSong1(url) {
   
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
            playerControls.style.display = 'block';
            isPlaying = true;
        }
    }
  </script>
   
</div>




<script>
// Mở popup
  document.getElementById('open-singer-modal').onclick = function() {
    document.getElementById('singer-modal').style.display = 'flex';
  };
// Đóng popup
  document.getElementById('close-singer-modal').onclick = function() {
    document.getElementById('singer-modal').style.display = 'none';
  };
// Đóng khi click nền
  document.getElementById('singer-modal').onclick = function(e) {
    if (e.target === this) this.style.display = 'none';
  };
// Preview ảnh
  document.querySelector('#singer-modal input[type="file"]').onchange = function(e) {
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
</script>

@endsection
