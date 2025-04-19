<section id="user" class="pb-0 pt-4">
    <div class="container mt-5 profile-container">
        <div class="row align-items-center profile-header">
            <div class="user-img col-md-3 text-center">
                <img src="{{ $photo }}" alt="Avatar Người Dùng" class="rounded-circle img-fluid" width="300" height="300">
            </div>
            <div class="col-md-8">
                <h3 class="fw-bold">{{ $full_name }}</h3>
                <div class="d-flex flex-wrap gap-2 mt-2 justify-content-center">
                    <button class="btn btn-light">Chỉnh sửa trang cá nhân</button>
                    <button class="btn btn-light">Xem kho lưu trữ</button>
                    <button class="btn btn-light">⚙️</button>
                </div>
                <div class="d-flex gap-4 mt-3 profile-stats">
                    <p><strong>0</strong> bài viết</p>
                    <p><strong>0</strong> người theo dõi</p>
                    <p>Đang theo dõi <strong>8</strong> người dùng</p>
                </div>
                <p class="fw-bold">{{ $description }}</p>
            </div>
        </div>
        <div class="user-playlist">
            <div class="box-playlist">
                <div class="background-playlist">
                    <img src="{{asset('frontend/images/mtp.jpg')}}" alt="Avatar Người Dùng" class="rounded-circle img-fluid" width="300" height="300">
                </div>
                <p class="playlist-text">Âm nhạc MTP</p>
            </div>

            <div class="box-playlist">
                <div class="background-playlist">
                    <img src="{{asset('frontend/images/cate1.jpg')}}" alt="Avatar Người Dùng" class="rounded-circle img-fluid" width="300" height="300">
                </div>
                <p class="playlist-text">Sóng</p>
            </div>

            <div class="box-playlist">
                <div class="background-playlist">
                    <img src="{{asset('frontend/images/cate1.jpg')}}" alt="Avatar Người Dùng" class="rounded-circle img-fluid" width="300" height="300">
                </div>
                <p class="playlist-text">Ryoma</p>
            </div>
        
            <div class="box-playlist">
                <p class="playlist-item">+</p>
                <p class="playlist-text">Mới</p>
            </div>
        </div>

        <div class="background-create"></div>
        <div class="modal-custom">
            <div class="text-end">
                <button class="btn-close"></button>
            </div>
            <h5 class="text-center">Tên playlist</h5>
            <input type="text" class="form-control mt-3" placeholder="Tên playlist">
            <button class="btn btn-primary w-100 mt-3">Tiếp</button>
        </div>

    
        <div class="border-top mt-4 pt-2 text-center">
            <ul class="nav justify-content-center">
                <li class="nav-item">
                    <a class="nav-link active fw-bold" href="#">🗂 BÀI VIẾT</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="#">🔖 ÂM NHẠC</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="#">📸 ĐÃ LƯU</a>
                </li>
            </ul>

            <div class="blog-image mt-4">
                <div class="row">
                    <?php foreach($blogs as $item):?>
                        <div class="blog-image-item col-md-3 "><img src="{{$item->photo}}" alt="Image 1"></div>
                    <?php endforeach;?>

                    <div class="blog-image-item col-md-3 "><img src="{{asset('frontend/images/cate1.jpg')}}" alt="Image 2"></div>
                    <div class="blog-image-item col-md-3 "><img src="{{asset('frontend/images/mtp.jpg')}}" alt="Image 3"></div>
                </div>
            </div>


        </div>
    </div>
</section>