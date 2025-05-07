<div class="tab-content" id="tab-friends"  @if($user->id != auth()->user()->id) style="display:block;" @else style="display:none;" @endif >
    <div class="profile-content">
      
      <div class="profile-main">
        @php
        use App\Models\Post;
  use App\Modules\Singer\Models\Singer;
      
      
      @endphp
        @foreach ($post_user as $post)
        <div class="post">
          
            <div class="post-header" style="display: flex; align-items: center; justify-content: space-between;">
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
              @if (auth()->user()->id == $post->user_id)
                <div style="display: flex; gap: 8px; align-items: center;">
                  <a href="#" class="edit-post-btn" data-id="{{ $post->id }}" title="Chỉnh sửa" style="color:#ffc107;font-size:1.3em;display:inline-block;"><i class="fa fa-edit"></i></a>
                  <form method="POST" action="#" class="delete-post-form" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="delete-post-btn" data-id="{{ $post->id }}" title="Xóa" style="background:none;border:none;color:#dc3545;font-size:1.3em;cursor:pointer;padding:0 4px;"><i class="fa fa-trash"></i></button>
                  </form>
                </div>
              @endif
            </div>
        
          <div class="post-content">
            <p class="post-description" data-id="{{ $post->id }}">{!! preg_replace('~(https?://[^"]+)~', '<button onClick="playSong1(\'$1\')" 
            style="color:#1877f2;cursor:pointer; border:none; background:none;"
            >$1</button>', $post->description) !!}</p>
              <div class="edit-description-box" id="edit-description-{{ $post->id }}" style="display:none;">
                <textarea class="edit-description-textarea" placeholder="{{ $user->full_name }}, Bạn đang nghĩ gì?" style="width:98%;border-radius:6px;padding:8px 12px;border:1px solid #ddd;resize:vertical;min-height:50px;">{{ strip_tags($post->description) }}</textarea>
                <div class="edit-youtube-preview" style="display:none; margin-top:10px;"></div>
                <div class="status-actions">
                  <button type="button" class="edit-photo-btn" data-id="{{ $post->id }}">Tải hình ảnh</button>
                  <input type="file" class="edit-photo-input" data-id="{{ $post->id }}" accept="image/*" style="display:none;">
                  <button class="save-description-btn" data-id="{{ $post->id }}" style="background:#1877f2;color:#fff;border:none;padding:7px 18px;border-radius:8px;cursor:pointer;">Lưu</button>
                  <button class="cancel-description-btn" data-id="{{ $post->id }}" style="background:#888;color:#fff;border:none;padding:7px 18px;border-radius:8px;cursor:pointer;margin-left:8px;">Hủy</button>
                </div>
                <div class="edit-photo-preview-box" id="edit-photo-preview-{{ $post->id }}" style="display:none; margin-top:10px;"></div>
              </div>
            @if ($post->image)
              <div style="background:#f46c3b; width:100%; max-width:680px; height:380px; display:flex; align-items:center; justify-content:center; color:#222; font-size:2em; margin:15px auto; border-radius:8px;">
                <img src="{{ $post->image }}" alt="Image" class="post-image" style="width:100%; height:100%; object-fit:cover; cursor:pointer;">
              </div>
            @endif
            @if(isset($post->link) && $post->link)
                    <div id="youtube-edit-{{ $post->id }}" style="display: block; margin-top: 10px;"></div>
                    <script>
                      document.addEventListener('DOMContentLoaded', function() {
                        var link = @json($post->link);
                
                        var previewId = 'youtube-edit-{{ $post->id }}';
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
            <span class="comment-btn-about" data-id="{{ $post->id }}" style="display:flex; align-items:center; gap:5px; cursor:pointer;">💬 <b>{{ $post->comment }}</b></span>
          </div>
          <div class="comments" style="margin-top:15px;">
       
            @if ($post->commentUser->count() > 0)
              @foreach ($post->commentUser as $comment)
                <div class="comment" style="display:flex; gap:10px; margin-bottom:10px;">
                  <img src="{{  asset('storage/' . optional($comment->user)->photo) }}" alt="" class="avatar-small">
                  <div>
                    <b>{{ optional($comment->user)->full_name }}</b> <span style="color:#888; font-size:0.9em;">{{ $comment->created_at->diffForHumans() }}</span>
                    <div style="padding: 10px;">{{ $comment->content }}</div>
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
                      <button class="comment-reply-btn" data-id="{{ $comment->id }}" style="background:none;border:none;color:#888;cursor:pointer;font-size:1em;display:flex;align-items:center;gap:4px;">0 Lượt Trả lời</button>
                    </div>
                  </div>
                </div>
                 
                <!-- Khung trả lời chỉ cho comment này -->
                <div class="reply-input-box" id="reply-input-{{ $comment->id }}" style="display:none; margin-top:10px; margin-bottom:10px; margin-left:50px;">
                  <b style="margin:5px 0px;">{{ optional($comment->user)->full_name }}</b>
                  <textarea rows="2" style="width:95%;border-radius:8px;padding:8px 12px;border:1px solid #ddd;resize:none; margin-left:10px;" placeholder="Nhập trả lời..."></textarea>
                  <button class="send-reply-btn" data-id="{{ $comment->id }}" style="margin-top:6px;background:#1877f2;color:#fff;border:none;padding:7px 18px;border-radius:8px;cursor:pointer;float:right; margin-right:10px;">Gửi</button>
                </div>
                <!-- Hiển thị các reply con (nếu có) -->
                @if($comment->replyComment && $comment->replyComment->count())
                  @foreach($comment->replyComment as $reply)
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
            <div class="comment-input-box" id="comment-input-about-{{ $post->id }}" style="display:none; margin-top:10px; margin-bottom:30px;">
              <textarea rows="2" style="width:97%;border-radius:8px;padding:8px 12px;border:1px solid #ddd;resize:none;" placeholder="Nhập bình luận..."></textarea>
              <button class="send-comment-btn-about" data-id="{{ $post->id }}" style="margin-top:6px;
              background:#1877f2;color:#fff;border:none;padding:7px 18px;
              border-radius:8px;cursor:pointer;float:right; margin-right:10px;">Gửi</button>
            </div>
          
          </div>
          <button class="show-more-comments">Xem thêm bình luận</button>
        </div>
      @endforeach
      </div>
    </div>
  </div>