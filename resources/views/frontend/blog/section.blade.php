@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
    <style>
        .blog-slider {
            margin-bottom: 20px;
            width: 100%;
        }
        .blog-slider img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 8px;
        }
        .slick-prev, .slick-next {
            z-index: 1;
            width: 40px;
            height: 40px;
            background: rgba(0, 0, 0, 0.5);
            border-radius: 50%;
        }
        .slick-prev:before, .slick-next:before {
            font-size: 24px;
            color: white;
        }
        .slick-dots {
            bottom: -30px;
        }
        .slick-dots li button:before {
            font-size: 12px;
            color: #999;
        }
        .slick-dots li.slick-active button:before {
            color: #333;
        }
        @media (max-width: 768px) {
            .blog-slider img {
                height: 200px;
            }
        }
    </style>
@endsection

<section id="blog" class="pb-0 pt-4 mt-10 blog-section">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-10 filter-content p-md-0 blog">
                <div class="blog-wrapper">
                    @foreach ($blogs as $blog)
                        <div class="blog-item mb-50px">
                            <div class="blog-content">
                                <div class="header">
                                    <img src="{{ optional($blog->user)->photo ?? asset('backend/images/profile-6.jpg') }}"
                                         alt="Ảnh đại diện" class="avatar">
                                    <div class="user-info">
                                        <div class="username">{{ optional($blog->user)->full_name ?? 'Người dùng' }}</div>
                                        <div class="datetime">{{ $blog->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>

                                <div class="blog-slider blog-carousel owl-carousel owl-theme">
                                    @php
                                        $photos = explode(',', $blog->photo);
                                    @endphp
                                    @foreach ($photos as $photo)
                                        <div class="item">
                                            <img src="{{ asset(trim($photo)) }}" alt="{{ $blog->title }}"
                                                 class="blog-image" loading="lazy">
                                        </div>
                                    @endforeach
                                </div>

                                <div class="blog-actions">
                                    <button class="like-btn {{ Auth::check() && $blog->Tmotion && in_array(Auth::id(), json_decode($blog->Tmotion->user_motions, true) ?? []) ? 'liked' : '' }}"
                                            data-id="{{ $blog->id }}" data-code="blog">
                                        <i class="fa fa-heart"></i>
                                        <span id="like-count-{{ $blog->id }}">
                                            {{ $blog->Tmotion ? json_decode($blog->Tmotion->motions, true)['likes'] : 0 }}
                                        </span>
                                    </button>
                                    <button class="toggle-comments" data-blog="{{ $blog->id }}">
                                        <i class="fa fa-comment"></i>
                                        <span>{{ $blog->Tcomments->count() }}</span>
                                    </button>
                                </div>
                                <h4 class="blog-title">{{ $blog->title }}</h4>
                                <div class="blog-summary">{{ $blog->summary }}</div>
                            </div>

                            <!-- Panel bình luận -->
                            <div class="comment-panel" id="comment-panel-{{ $blog->id }}">
                                <div class="comment-header">
                                    <h5>Bình luận ({{ $blog->Tcomments->count() }})</h5>
                                    <button class="close-comment-panel" data-blog="{{ $blog->id }}">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                                <div class="comment-body">
                                    <div class="existing-comments" id="comments-{{ $blog->id }}">
                                        @foreach ($blog->Tcomments->take(3) as $comment)
                                            @include('frontend.blog.comment', ['comment' => $comment, 'blog' => $blog])
                                        @endforeach
                                    </div>
                                    @if($blog->Tcomments->count() > 3)
                                        <a href="#" class="load-more-comments" data-blog="{{ $blog->id }}"
                                           data-offset="3">Xem thêm bình luận</a>
                                    @endif
                                    <div class="comment-input">
                                        @if(Auth::check())
                                            <form action="{{ route('comments.store') }}" method="POST"
                                                  class="ajax-comment-form">
                                                @csrf
                                                <input type="hidden" name="item_id" value="{{ $blog->id }}">
                                                <input type="text" name="content" placeholder="Viết bình luận..."
                                                       required>
                                                <button type="submit">Gửi</button>
                                            </form>
                                        @else
                                            <p><a href="">Đăng nhập</a> để bình luận.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="background-comment"></div>
</section>