let music = {
    init: function () {
        this.user_playlist();
        this.blog();
        this.blog_carousel();
    },

    user_playlist: function () {
        jQuery(document).ready(function ($) {
            $(".box-playlist .playlist-item").click(function () {
                $(".background-create, .modal-custom").addClass("active");
            });

            $(".btn-close, .background-create").click(function () {
                $(".background-create, .modal-custom").removeClass("active");
            });
        });
    },

    blog_carousel: function(){
        jQuery(document).ready(function(){
            var owl = $('.blog-carousel');
            owl.owlCarousel({
                items:1,
                loop:true,
                margin:10,
                autoplay:true,
                autoplayTimeout:3000,
                autoplayHoverPause:true,
                dots: true,
            });
        })
    },

    blog: function () {
        jQuery(document).ready(function ($) {
            // Khởi tạo toastr
            toastr.options = {
                closeButton: true,
                progressBar: true,
                positionClass: 'toast-top-right'
            };


            // Xử lý nút Theo dõi
            $(document).on('click', '.follow-btn', function () {
                const btn = $(this);
                const userId = btn.data('user-id');
                $.ajax({
                    url: '/follow/toggle',
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        user_id: userId
                    },
                    success: function (response) {
                        if (response.success) {
                            btn.toggleClass('following', response.isFollowing);
                            btn.text(response.isFollowing ? 'Đang theo dõi' : 'Theo dõi');
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.error || 'Có lỗi xảy ra.');
                        }
                    },
                    error: function (xhr) {
                        toastr.error(xhr.responseJSON.error || 'Có lỗi xảy ra.');
                    }
                });
            });

            // Hiển thị/ẩn panel bình luận
            $(document).on('click', '.toggle-comments', function (e) {
                e.preventDefault();
                const blogId = $(this).data('blog');
                const panel = $(`#comment-panel-${blogId}`);
                const bg = $('.background-comment');

                $('.comment-panel.active').removeClass('active');
                $('.background-comment.active').removeClass('active');

                panel.addClass('active');
                bg.addClass('active');

                const commentCount = $(`#comments-${blogId} .comment-item`).length;
                $(this).find('span').text(commentCount);
            });

            // Đóng panel bình luận
            $(document).on('click', '.close-comment-panel', function () {
                const blogId = $(this).data('blog');
                $(`#comment-panel-${blogId}`).removeClass('active');
                $('.background-comment').removeClass('active');
            });

            // Đóng panel khi nhấp background
            $(document).on('click', '.background-comment.active', function () {
                $('.comment-panel.active').removeClass('active');
                $(this).removeClass('active');
            });

            // Toggle form trả lời
            $(document).on('click', '.reply-btn', function (e) {
                e.preventDefault();
                const commentId = $(this).data('comment');
                $(`#reply-form-${commentId}`).slideToggle();
            });

            // Gửi bình luận qua AJAX
            $(document).on('submit', '.ajax-comment-form', function (e) {
                e.preventDefault();
                const form = $(this);
                const blogId = form.find('input[name="item_id"]').val();
                const parentId = form.find('input[name="parent_id"]').val();

                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    success: function (response) {
                        if (response.success) {
                            const comment = response.comment;
                            const commentHtml = `
                                <div class="comment-item" data-comment-id="${comment.id}">
                                    <strong>${comment.user.full_name || 'Người dùng'}</strong>
                                    <span class="datetime">Vừa xong</span>
                                    <p>${comment.content}</p>
                                    <button class="like-btn" data-id="${comment.id}" data-code="comment">
                                        <i class="fa fa-thumbs-up"></i>
                                        <span id="like-count-${comment.id}">0</span>
                                    </button>
                                    <button class="reply-btn" data-comment="${comment.id}">
                                        <i class="fa fa-reply"></i> Trả lời
                                    </button>
                                    <div class="reply-form" id="reply-form-${comment.id}" style="display: none;">
                                        <form action="/comments/store" method="POST" class="ajax-comment-form">
                                            <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                                            <input type="hidden" name="item_id" value="${comment.item_id}">
                                            <input type="hidden" name="parent_id" value="${comment.id}">
                                            <input type="text" name="content" placeholder="Viết trả lời..." required>
                                            <button type="submit">Gửi</button>
                                        </form>
                                    </div>
                                </div>
                            `;

                            if (parentId) {
                                $(`#reply-form-${parentId}`).before(commentHtml);
                                $(`#reply-form-${parentId}`).slideUp();
                            } else {
                                $(`#comments-${blogId}`).prepend(commentHtml);
                            }

                            form.find('input[name="content"]').val('');
                            toastr.success('Bình luận đã được thêm!');

                            const commentCount = $(`#comments-${blogId} .comment-item`).length;
                            $(`.toggle-comments[data-blog="${blogId}"] span`).text(commentCount);
                        }
                    },
                    error: function (xhr) {
                        toastr.error(xhr.responseJSON.error || 'Có lỗi xảy ra.');
                    }
                });
            });

            // Tải thêm bình luận
            $(document).on('click', '.load-more-comments', function (e) {
                e.preventDefault();
                const link = $(this);
                const blogId = link.data('blog');
                const offset = link.data('offset');

                $.ajax({
                    url: `/blogs/${blogId}/comments`,
                    method: 'GET',
                    data: { offset: offset },
                    success: function (response) {
                        response.comments.forEach(comment => {
                            const commentHtml = `
                                <div class="comment-item" data-comment-id="${comment.id}">
                                    <strong>${comment.user.full_name || 'Người dùng'}</strong>
                                    <span class="datetime">${new Date(comment.created_at).toLocaleString()}</span>
                                    <p>${comment.content}</p>
                                    <button class="like-btn" data-id="${comment.id}" data-code="comment">
                                        <i class="fa fa-thumbs-up"></i>
                                        <span id="like-count-${comment.id}">
                                            ${comment.Tmotion ? JSON.parse(comment.Tmotion.motions).likes : 0}
                                        </span>
                                    </button>
                                    <button class="reply-btn" data-comment="${comment.id}">
                                        <i class="fa fa-reply"></i> Trả lời
                                    </button>
                                    <div class="reply-form" id="reply-form-${comment.id}" style="display: none;">
                                        <form action="/comments/store" method="POST" class="ajax-comment-form">
                                            <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                                            <input type="hidden" name="item_id" value="${comment.item_id}">
                                            <input type="hidden" name="parent_id" value="${comment.id}">
                                            <input type="text" name="content" placeholder="Viết trả lời..." required>
                                            <button type="submit">Gửi</button>
                                        </form>
                                    </div>
                                </div>
                            `;
                            $(`#comments-${blogId}`).append(commentHtml);
                        });

                        if (!response.hasMore) {
                            link.remove();
                        } else {
                            link.data('offset', offset + 3);
                        }
                    },
                    error: function () {
                        toastr.error('Không thể tải thêm bình luận.');
                    }
                });
            });

            // Xử lý like/unlike
            $(document).on('click', '.like-btn', function (e) {
                e.preventDefault();
                const btn = $(this);
                const id = btn.data('id');
                const code = btn.data('code');

                $.ajax({
                    url: '/motions/toggle',
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        item_id: id,
                        item_code: code
                    },
                    success: function (response) {
                        $(`#like-count-${id}`).text(response.likes);
                        btn.toggleClass('liked', response.liked);
                        toastr.success(response.liked ? 'Đã thích!' : 'Đã bỏ thích.');
                    },
                    error: function (xhr) {
                        toastr.error(xhr.responseJSON.error || 'Có lỗi xảy ra.');
                    }
                });
            });
        });
    }
};

music.init();