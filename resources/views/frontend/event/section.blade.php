<section id="event">
    <div class="container py-4">
        <header class="mb-4">
            <h1 class="text-center">Danh Sách Sự Kiện</h1>
        </header>

        <div class="row g-4">
            @foreach($event as $item)
            <div class="col-md-12">
                <div class="card h-100">
                    <img src="{{$item->photo}}" class="card-img-top" alt="Hình ảnh sự kiện">
                    <div class="card-body">
                        <h5 class="card-title">{{ $item->title}}</h5>
                        <p class="card-text">
                        <small class="text-muted">Người đăng: Admin</small>
                        </p>
                        <p>{{$item->diadiem}}</p>
                        <p class="card-text">{{$item->summary}}</p>
                    </div>
                    <div class="card-footer bg-transparent border-top-0">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('front.event.detail', ['id' => $item->id]) }}" class="btn btn-primary">Xem thêm</a>
                            <!-- <button class="btn btn-success">Thanh toán</button> -->
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
    </div>
</section>