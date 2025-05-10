
@php
  use App\Models\Post;
  use App\Modules\Singer\Models\Singer;




@endphp
<div class="tab-content" id="tab-profile" @if($user->id == auth()->user()->id) style="display:block;" @else style="display:none;" @endif>
    <div class="profile-content">
      <div class="profile-sidebar">
        <h3>Thông tin cá nhân</h3>
        <p>{{ $user->description ?? 'Chưa cập nhật' }}</p>
        <ul>
          <li>🎓 {{ $user->role == 'customer' ? 'Tài khoản người dùng' : 'Quản trị viên' }}</li>
          <li>✉️ {{ $user->email }}</li>
    
          <li><i class="fa fa-phone"></i> {{ $user->phone ?? 'Chưa cập nhật' }}</li>
          <li>📍 {{ $user->taxaddress ?? 'Chưa cập nhật' }}</li>
        </ul>
        <h3>Hình ảnh liên quan</h3>
        <div class="photos">
          @foreach ($image_user as $image)
            <div class="photo-item" style="position:relative;display:inline-block;">
              <img src="{{ asset('storage/' . $image->image) }}" alt="Ảnh" class="modern-gallery-img">
            </div>
          @endforeach
        </div>
      </div>
      <div class="profile-main">
        <div class="status-box">
          <textarea placeholder="{{ $user->full_name }}, Bạn đang nghĩ gì?"></textarea>
          <div id="youtube-preview" style="display:none; margin-top:10px;"></div>
          <div class="status-actions">
            <button type="button" id="photo-btn">Tải hình ảnh</button>
            <input type="file" id="photo-input" accept="image/*" style="display:none;">
            
            <button class="post-btn" id="post-btn">Đăng bài</button>
          </div>
          <div id="photo-preview" style="display:none; margin-top:10px;"></div>
        </div>
        @foreach ($posts as $post)
      
          <div class="post">
            <a href="{{ route('front.blog.index', ['id' => $post->user->id]) }}" 
              class="post-header" 
              style="display: flex; align-items: center; justify-content: space-between; text-decoration:none;">
              <div style="display: flex; align-items: center; gap: 10px;">
                <img src="{{ $post->user->photo ? asset('storage/' . $post->user->photo) : 'https://i.pinimg.com/736x/bc/43/98/bc439871417621836a0eeea768d60944.jpg' }}" alt="Avatar" class="avatar-small">
                <div>
                  <strong>{{ $post->user->full_name }}</strong>
                  <span>{{ $post->created_at->diffForHumans() }}</span>
                  @if($post->post_form)
                      @php 
                            $post =   Post::with('user')->find($post->post_form);
                            
                      @endphp
                   <span> <br> (Bài viết chia sẽ từ: {{ $post->user->full_name }})
                  
                  </span>
                  @endif
                  @if($post->post_singer)
                      @php 
                            $Sing =  Singer::find($post->post_singer)->alias;
                            
                      @endphp
                   <span> <br> (Bài viết chia sẽ từ ca sĩ: {{ $Sing }})
                  
                  </span>
                  @endif
                </div>
              </div>
              
            </a>
            <div class="post-content">
              <p >{!! preg_replace('~(https?://[^"]+)~', '<button onClick="playSong1(\'$1\')" 
              style="color:#1877f2;cursor:pointer; border:none; background:none;"
              >$1</button>', $post->description) !!}</p>
             
              @if ($post->image)
                <div style="background:#f46c3b; width:100%; max-width:680px; min-height:380px; max-height:680px; display:flex; align-items:center; justify-content:center; color:#222; font-size:2em; margin:15px auto; border-radius:8px;">
                  <img src="{{ $post->image }}" alt="Image" class="post-image" style="width:100%; height:100%; object-fit:cover; cursor:pointer;">
                </div>
              @endif
              @if(isset($post->link) && $post->link)
                    <div id="youtube-preview-{{ $post->id }}" style="display: block; margin-top: 10px;"></div>
                    <script>
                      document.addEventListener('DOMContentLoaded', function() {
                        var link = @json($post->link);
                
                        var previewId = 'youtube-preview-{{ $post->id }}';
                        if (link && (link.includes('youtube.com') || link.includes('youtu.be'))) {
                          fetch('https://www.youtube.com/oembed?url=' + encodeURIComponent(link) + '&format=json')
                            .then(res => res.json())
                            .then(data => {
                              document.getElementById(previewId).innerHTML = `
                                <div onClick="playSong1('${link}')" style="
                                background:#222;border-radius:10px;overflow:hidden;max-width:480px;box-shadow:0 2px 8px #0003;position:relative; cursor:pointer;">
                                  <img src="${data.thumbnail_url}" style="width:100%;display:block;max-height:240px;object-fit:cover;">
                                  <div style="padding:16px 18px 12px 18px;">
                                    <div style="color:#aaa;font-size:13px;letter-spacing:1px;margin-bottom:2px;">YOUTUBE.COM</div>
                                    <div style="font-weight:bold;font-size:1.15em;color:#fff;margin-bottom:6px;">${data.title}</div>
                                    <div style="color:#ccc;font-size:0.98em;line-height:1.4;">${data.author_name}</div>
                                  </div>
                                </div>
                              `;
                            })
                            .catch(() => {
                              document.getElementById(previewId).innerHTML = '<div style="color:#f00;">Không lấy được thông tin video!</div>';
                            });
                        }
                      });
                    </script>
              @endif
            </div>
            <div class="post-actions" style="display:flex; align-items:center; gap:20px; margin-top:10px;">
              <span class="like-btn" data-id="{{ $post->id }}" style="display:flex; align-items:center; gap:5px; cursor:pointer;">
                  
                @if ($post->postUser->contains('user_id', $user->id))
                  <i class="fa fa-thumbs-up" style="transition:color 0.2s; color:rgb(24, 119, 242);"></i>
                @else
                  <i class="fa fa-thumbs-up" style="transition:color 0.2s;"></i>
                @endif
                @if ($post->like)
                  <b>{{ $post->like }}</b>
                @else
                  <b>0</b>
                @endif
              </span>
              <span class="comment-btn" data-id="{{ $post->id }}" style="display:flex; align-items:center; gap:5px; cursor:pointer;">💬 <b>{{ $post->comment }}</b></span>
              <span class="share-btn" style="display:flex; align-items:center; gap:5px; cursor:pointer;" data-id="{{ $post->id }}" >↗️ <b>{{ $post->share }}</b></span>
            </div>
            <div class="comments" style="margin-top:15px;">
              
              @if ($post->commentUser->count() > 0)
                @foreach ($post->commentUser->take(2) as $comment)
                  <div class="comment" style="display:flex; gap:10px; margin-bottom:10px;">
                    <img src="{{  asset('storage/' . optional($comment->user)->photo) }}" alt="" class="avatar-small">
                    <div>
                      <b>{{ optional($comment->user)->full_name }}</b> <span style="color:#888; font-size:0.9em;">{{ $comment->created_at->diffForHumans() }}</span>
                      <div class="comment-box" id="comment1" data-id="1">
                        <div id="content-show-comment" style="padding: 10px;">{{ $comment->content }}</div>

                        <div class="comment-input-box-alter" id="comment-input-alter-{{ $comment->id }}" style="display:none; margin-top:10px; margin-bottom:30px;">
                          <textarea id="text-content-comment" rows="2" style="width:97%;border-radius:8px;padding:8px 12px;border:1px solid #ddd;resize:none;" placeholder="Nhập bình luận..."></textarea>
                          <button class="cancel-comment-alt" id="cancel-comment-alt" onclick="cancelCommentEdit({{ $comment->id }})">Huỷ</button>
                          <button class="send-comment-alter-btn" data-id="{{ $comment->id }}" style="margin-top:6px;
                          background:#1877f2;color:#fff;border:none;padding:7px 18px;
                          border-radius:8px;cursor:pointer;float:right; margin-right:10px;" onclick="commentEdit({{$comment->id}})">Bình luận</button>
                        </div>

                        <div class="more-menu" id="more-menu">
                            <div class="more-button">⋮</div>
                            <div class="dropdown-menu">
                              <ul>
                                <li class="edit-comment-btn" data-id="{{ $comment->id }}"data-title="{{ $comment->content }}">Chỉnh sửa</li>
                                <li class="delete-comment-btn" data-id="{{ $comment->id }}">Xoá</li>
                              </ul>
                            </div>
                        </div>
                      </div>
                      
                      <div style="margin-top:4px;display:flex;gap:12px;align-items:center;">
                        <button class="comment-like-btn" data-id="{{ $comment->id }}" data-liked="{{ $comment->like }}" data-count="{{ $comment->like }}" style="background:none;border:none;color:#888;cursor:pointer;font-size:1em;display:flex;align-items:center;gap:4px;">
                          @if ($comment->commentChildrenUser->contains('user_id', $user->id))
                            <i class="fa fa-thumbs-up" style="transition:color 0.2s; color:rgb(24, 119, 242);"></i>
                          @else
                            <i class="fa fa-thumbs-up" style="transition:color 0.2s;"></i>
                          @endif
                          <span class="like-count">
                            {{ $comment->like }}
                          </span> Lượt thích</button>
                        <button class="comment-reply-btn" data-id="{{ $comment->id }}" style="background:none;border:none;color:#888;cursor:pointer;font-size:1em;display:flex;align-items:center;gap:4px;"> {{ $comment->reply }} Lượt Trả lời</button>
                      </div>
                    </div>
                  </div>
                  <!-- Khung trả lời chỉ cho comment này -->
                  <div class="reply-input-box" id="reply-input-{{ $comment->id }}" style="display:none; margin-top:10px; margin-left:50px;">
                    <b style="margin:5px 0px;">{{ optional($comment->user)->full_name }}</b>
                    <textarea rows="2" style="width:95%;border-radius:8px;padding:8px 12px;border:1px solid #ddd;resize:none; margin-left:10px;" placeholder="Nhập trả lời..."></textarea>
                    <button class="send-reply-btn" data-id="{{ $comment->id }}" style="margin-top:6px;background:#1877f2;color:#fff;border:none;padding:7px 18px;border-radius:8px;cursor:pointer;float:right; margin-right:10px;">Gửi</button>
                  </div>
          
                  @if($comment->replyComment && $comment->replyComment->count())
                    @foreach($comment->replyComment->take(2) as $reply)
                      <div class="comment reply-comment" style="display:flex; gap:10px; margin-bottom:10px; margin-left:50px;">
                        <img src="{{  asset('storage/' . optional($reply->user)->photo) }}" alt="" class="avatar-small">
                        <div>
                          <b>{{ optional($reply->user)->full_name }}</b> <span style="color:#888; font-size:0.9em;">{{ $reply->created_at->diffForHumans() }}</span>
                          <div style="padding: 10px;">{{ $reply->content }}</div>
                        </div>
                      </div>
                    @endforeach
                  @endif
                @endforeach
              @endif
              <div class="comment-input-box" id="comment-input-{{ $post->id }}" style="display:none; margin-top:10px; margin-bottom:30px;">
                <textarea rows="2" style="width:97%;border-radius:8px;padding:8px 12px;border:1px solid #ddd;resize:none;" placeholder="Nhập bình luận..."></textarea>
                <button class="send-comment-btn" data-id="{{ $post->id }}" style="margin-top:6px;
                background:#1877f2;color:#fff;border:none;padding:7px 18px;
                border-radius:8px;cursor:pointer;float:right; margin-right:10px;">Gửi</button>
              </div>
            
            </div>
            <button class="show-more-comments">Xem thêm bình luận</button>
          </div>
        @endforeach
      </div>
      <div id="confirmPopup" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.5); 
    justify-content:center; align-items:center;">
          <div style="background:white; padding:20px; border-radius:8px; text-align:center;">
              <p>Bạn có chắc chắn muốn xoá comment này không?</p>
              <button class="confirm-delete" id="confirmDelete">Xoá</button>
              <button class="cancel-delete" id="cancelDelete">Huỷ</button>
          </div>
      </div>
    </div>
  </div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.share-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var postId = this.getAttribute('data-id');
            fetch('{{ route('front.share.post') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ id: postId })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    this.querySelector('b').textContent = data.share_count;
                    Notiflix.Notify.success('Chia sẻ thành công!');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    Notiflix.Notify.failure('Có lỗi xảy ra!');
                }
            })
            .catch(error => {
                Notiflix.Notify.failure('Lỗi kết nối!');
            });
        });
    });
    // Xử lý nút "Xem thêm bình luận"
    document.querySelectorAll('.show-more-comments').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var postId = this.closest('.post').querySelector('.comment-btn').getAttribute('data-id');
            
            fetch('{{ route('front.get.comments') }}?post_id=' + postId)
                .then(res => res.json())
                .then(data => {
                    let html = '';
                    data.posts.comment_user.forEach(function(comment) {
                      console.log(comment);
                        html += `
                        <div style=\"display:flex; gap:10px; margin-bottom:18px;\">
                            <img src=\"${comment.user.photo ? '/storage/' + comment.user.photo : 'https://i.pinimg.com/736x/bc/43/98/bc439871417621836a0eeea768d60944.jpg'}\" alt=\"\" style=\"width:40px;height:40px;border-radius:50%;object-fit:cover;\">
                            <div>
                                <b style="color:black; margin-right:10px;">${comment.user.full_name}</b> <span style="color:#888; ">${comment.time}</span>
                                <div style=\"color:black; margin:4px 0 6px 0;\">${comment.content}</div>
                                <div style=\"display:flex; gap:18px; align-items:center; font-size:0.98em; color:#1877f2; margin-bottom:4px;\">
                                    <button class=\"popup-comment-like-btn\" data-id=\"${comment.id}\" style=\"background:none;border:none;color:#1877f2;cursor:pointer;\">
                                        <i class=\"fa fa-thumbs-up\"></i> <span class=\"like-count\">${comment.like}</span> Lượt thích
                                    </button>
                                    <button class=\"popup-comment-reply-btn\" data-id=\"${comment.id}\" style=\"background:none;border:none;color:#1877f2;cursor:pointer;\">
                                        ${comment.reply_comment.length} Lượt Trả lời
                                    </button>
                                </div>
                                <div class=\"popup-reply-list\" >
                                    ${comment.reply_comment.map(reply => `
                                        <div style="display:flex; gap:10px; margin-bottom:10px; color:black; padding:5px 0px;">
                                            <img src="${reply.user.photo ? '/storage/' + reply.user.photo : 'https://i.pinimg.com/736x/bc/43/98/bc439871417621836a0eeea768d60944.jpg'}\" alt="" style="width:34px;height:34px;border-radius:50%;object-fit:cover;">
                                            <div>
                                                <b style="color:black; margin-right:10px;">${reply.user.full_name}</b> <span style="color:#888;">${reply.time}</span>
                                                <div style="color:black; margin:4px 0 0 0;">${reply.content}</div>
                                               
                                            </div>
                                        </div>
                                    `).join('')}
                                </div>
                                <div class=\"popup-reply-input-box\" id=\"popup-reply-input-${comment.id}\" style=\"display:none; margin-top:10px;\">
                                    <textarea rows=\"2\" style=\"width:90%;border-radius:8px;padding:8px 12px;border:1px solid #ddd;resize:none;\" placeholder=\"Nhập trả lời...\"></textarea>
                                    <button class=\"popup-send-reply-btn\" data-id=\"${comment.id}\" style=\"margin-top:6px;background:#1877f2;color:#fff;border:none;padding:7px 18px;border-radius:8px;cursor:pointer;\">Gửi</button>
                                </div>
                            </div>
                        </div>
                        `;
                    });
                    html += `<div style=\"text-align:center; margin-top:18px;\">
                        <textarea id=\"modal-comment-input\" data-id=\"${postId}\" style=\"width:90%;border-radius:8px;padding:8px 12px;border:1px solid #ddd;resize:none;\" placeholder=\"Nhập bình luận...\"></textarea>
                        <button id=\"modal-send-comment\" style=\"margin-top:6px;background:#1877f2;color:#fff;border:none;padding:7px 18px;border-radius:8px;cursor:pointer;\">Gửi</button>
                    </div>`;
                    document.getElementById('modal-comments-content').innerHTML = html;
                    document.getElementById('comment-modal').style.display = 'flex';

                    // Like comment
                    document.querySelectorAll('.popup-comment-like-btn').forEach(function(btn) {
                        btn.onclick = function() {
                            var commentId = this.getAttribute('data-id');
                            fetch('{{ route('front.blog.likeComment') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ id: commentId })
                            })
                            .then(res => res.json())
                            .then(data => {
                                if(data.success) {
                                    
                                    // this.querySelector('.like-count').textContent = data.like_count;
                                    Notiflix.Notify.success('Bình luận đã được like');
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 1000);
                                }else{
                                    Notiflix.Notify.failure('Bình luận đã hủy like');
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 1000);
                                }
                            });
                        };
                    });
                    // Hiện khung trả lời
                    document.querySelectorAll('.popup-comment-reply-btn').forEach(function(btn) {
                        btn.onclick = function() {
                            var commentId = this.getAttribute('data-id');
                            var box = document.getElementById('popup-reply-input-' + commentId);
                            if (box) box.style.display = 'block';
                        };
                    });
                    // Gửi trả lời
                    document.querySelectorAll('.popup-send-reply-btn').forEach(function(btn) {
                        btn.onclick = function() {
                            var commentId = this.getAttribute('data-id');
                            var textarea = this.previousElementSibling;
                            var content = textarea.value;
                            fetch('{{ route('front.blog.replyComment') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ comment_id: commentId, content: content })
                            })
                            .then(res => res.json())
                            .then(data => {
                                if(data.success) {
                                  
                                    Notiflix.Notify.success('Trả lời đã được gửi');
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 1000);
                                }
                            });
                        };
                    });
                    // Like reply
                    document.querySelectorAll('.popup-reply-like-btn').forEach(function(btn) {
                        btn.onclick = function() {
                            var replyId = this.getAttribute('data-id');
                            fetch('/api/like-reply', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ id: replyId })
                            })
                            .then(res => res.json())
                            .then(data => {
                                if(data.success) {
                                    this.querySelector('.like-count').textContent = data.like_count;
                                }
                            });
                        };
                    });
                    console.log(data.posts);

                    let postImgHtml = '';
                    if(data.posts.image) {
                        postImgHtml = `<img src="${data.posts.image}" alt="Post Image" style="max-width:100%; max-height:500px; border-radius:10px; box-shadow:0 2px 12px #0002;">`;
                    } else {
                        postImgHtml = `<div style='width:100%;height:300px;display:flex;align-items:center;justify-content:center;color:#888;background:#f4f4f4;border-radius:10px;'>Không có ảnh</div>`;
                    }
                    document.getElementById('modal-post-image').innerHTML = postImgHtml;
                });
        });
    });
    // Đóng modal
    document.getElementById('close-comment-modal').onclick = function() {
        document.getElementById('comment-modal').style.display = 'none';
    };
    // Gửi bình luận mới trong modal (cần bổ sung code gửi AJAX thực tế)
    document.addEventListener('click', function(e) {
        if (e.target && e.target.id === 'modal-send-comment') {
            var postId = document.getElementById('modal-comment-input').getAttribute('data-id');
            var content = document.getElementById('modal-comment-input').value;
            fetch('{{ route('front.blog.comment') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ post_id: postId, content: content })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    Notiflix.Notify.success('Bình luận đã được gửi');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }
            });
        }
    });
});

    document.addEventListener('DOMContentLoaded', function() {
        // Toggle menu ba chấm
        document.querySelectorAll('.more-button').forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                const dropdown = this.nextElementSibling;
                dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
            });
        });

        // Đóng menu khi click ra ngoài
        window.addEventListener('click', function() {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.style.display = 'none';
            });
        });
    });

    let commentIdToDelete = null;
    document.querySelectorAll('.delete-comment-btn').forEach(button => {
        button.addEventListener('click', function() {
            commentIdToDelete = this.dataset.id;
            document.getElementById('confirmPopup').style.display = 'flex';
        });
    });
    document.getElementById('cancelDelete').addEventListener('click', function() {
        document.getElementById('confirmPopup').style.display = 'none';
        commentIdToDelete = NULL;
    });

    document.getElementById('confirmDelete').addEventListener('click', function() {
      console.log(commentIdToDelete);
      
      if (commentIdToDelete) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(`/blog-delete-comment/${commentIdToDelete}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => {
            if (response.ok) {
              Notiflix.Notify.success('Bình luận đã bị xoá');
              setTimeout(() => {
                  window.location.reload();
              }, 1000);
            
            } else {
                return response.json().then(data => {
                    throw new Error(data.message || 'Xoá thất bại!');
                });
            }
        })
        .catch(error => {
            console.error('Lỗi:', error);
            Notiflix.Notify.failure('Xoá thất bại!');
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        });
      }  
    });  

    document.querySelectorAll('.edit-comment-btn').forEach(button => {
        button.addEventListener('click', function() {
            // Lấy dữ liệu từ data-attributes
            const id = this.dataset.id;
            const contentComment = this.dataset.title;
            document.getElementById('text-content-comment').innerHTML = contentComment;
            document.getElementById('content-show-comment').style.display = 'none';
            document.getElementById('comment-input-alter-'+id).style.display = 'block';
            document.getElementById('more-menu').style.display = 'none';
        });
    });
    function commentEdit(commentId){
      const contentComment = document.getElementById('text-content-comment').value;
      if(contentComment){
        fetch('{{ route('front.blog.comment') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ comment_id: commentId, comment_content: contentComment })
            })
        .then(response => {
            if (response.ok) {
              Notiflix.Notify.success('Chỉnh sửa bình luận thành công');
              setTimeout(() => {
                  window.location.reload();
              }, 1000);
            
            } else {
                return response.json().then(data => {
                    throw new Error(data.message || 'Chỉnh sửa bình luận thất bại!');
                });
              }
            })
        .catch(error => {
            console.error('Lỗi:', error);
            Notiflix.Notify.failure('Chỉnh sửa bình luận thất bại!');
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        });
      }

    }

    function cancelCommentEdit(id){
      document.getElementById('content-show-comment').style.display = 'block';
      document.getElementById('comment-input-alter-'+id).style.display = 'none';
      document.getElementById('more-menu').style.display = 'block';
    }
</script>
<!-- Modal hiển thị bình luận -->
<div id="comment-modal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.7); z-index:9999; align-items:center; justify-content:center;">
  <div style="background:#fff; border-radius:10px; max-width:900px; width:95vw;  max-height:90vh;
   overflow:auto; position:relative; padding:24px; display:flex; gap:32px;">
    <button id="close-comment-modal" style="position:absolute; top:10px; right:10px; background:none; border:none; font-size:1.5em; cursor:pointer;">&times;</button>
    <div id="modal-post-image" style="flex:0 0 340px; display:flex; align-items:center; justify-content:center;">
      <!-- Ảnh bài post sẽ được render ở đây -->
    </div>
    <div id="modal-comments-content" style="flex:1; min-width:0;">
      <!-- Nội dung bình luận sẽ được render ở đây -->
    </div>
  </div>
</div>

<style>
  .more-menu {
    position: relative;
    display: inline-block;
  }

  .more-button {
      font-size: 24px;
      cursor: pointer;
      user-select: none;
  }

  .dropdown-menu {
      display: none;
      position: absolute;
      right: 0;
      background-color: white;
      min-width: 120px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      z-index: 1;
      border-radius: 4px;
  }

  .dropdown-menu a {
      color: black;
      padding: 10px 16px;
      text-decoration: none;
      display: block;
  }

  .dropdown-menu a:hover {
      background-color: #f0f0f0;
  }

  .comment-box {
      display: flex
  }
  .dropdown-menu ul{
      list-style: none;
      padding-left: 16px;
  }
  .dropdown-menu ul li{
      cursor: pointer;
      margin-bottom: 10px
  }
  .confirm-delete{
    background: linear-gradient(90deg, #4f8cff, #38b6ff);
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 8px 18px;
    font-size: 1em;
    font-weight: 500;
    text-decoration: none;
    transition: background 0.2s;
    margin-left: 10px;
    cursor: pointer;

  }
  .cancel-delete{
    background: #ff4d4f;
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 8px 18px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
  }
  .cancel-comment-alt{
    margin-top: 6px;
    float: right;
    background: #ff4d4f;
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 8px 18px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
    margin-right: -14px;
  }
</style>