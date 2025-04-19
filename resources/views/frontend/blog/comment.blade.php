<!-- resources/views/frontend/blog/comment.blade.php -->
<div class="comment-item" data-comment-id="{{ $comment->id }}">
    <strong>{{ optional($comment->user)->full_name ?? 'Người dùng' }}</strong>
    <span class="datetime">{{ $comment->created_at->diffForHumans() }}</span>
    <p>{{ $comment->content }}</p>
    <button class="like-btn {{ Auth::check() && $comment->Tmotion && in_array(Auth::id(), json_decode($comment->Tmotion->user_motions, true) ?? []) ? 'liked' : '' }}"
            data-id="{{ $comment->id }}" data-code="comment">
        <i class="fa fa-thumbs-up"></i>
        <span id="like-count-{{ $comment->id }}">
            {{ $comment->Tmotion ? json_decode($comment->Tmotion->motions, true)['likes'] : 0 }}
        </span>
    </button>
    <button class="reply-btn" data-comment="{{ $comment->id }}">
        <i class="fa fa-reply"></i> Trả lời
    </button>

    <!-- Replies -->
    @if($comment->replies && $comment->replies->count())
        <div class="reply-list">
            @foreach($comment->replies as $reply)
                @include('frontend.blog.comment', ['comment' => $reply, 'blog' => $blog])
            @endforeach
        </div>
    @endif

    <!-- Form trả lời -->
    <div class="reply-form" id="reply-form-{{ $comment->id }}" style="display: none;">
        @if(Auth::check())
            <form action="{{ route('comments.store') }}" method="POST" class="ajax-comment-form">
                @csrf
                <input type="hidden" name="item_id" value="{{ $blog->id }}">
                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                <input type="text" name="content" placeholder="Viết trả lời..." required>
                <button type="submit">Gửi</button>
            </form>
        @else
            <p><a href="">Đăng nhập</a> để trả lời.</p>
        @endif
    </div>
</div>