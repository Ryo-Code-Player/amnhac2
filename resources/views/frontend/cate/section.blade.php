<section class="pb-0 pt-4">
    <div class="container">
        <div class="row">
            <div class="col-12 filter-content p-md-0">
                <ul class="portfolio-simple portfolio-wrapper grid-loading alt-font fs-18 grid grid-3col xxl-grid-3col xl-grid-3col lg-grid-3col md-grid-2col sm-grid-1col xs-grid-1col gutter-extra-large text-center">
                    <li class="grid-sizer"></li>
                    @foreach ($cate as $item)
                    <li class="grid-item retails banking transition-inner-all">
                        <div class="portfolio-box">
                            <div class="portfolio-image border-radius-6px bg-dark-gray">
                                <a href="{{ route('front.song.category', ['slug' => $item->slug]) }}">
                                    <img src="{{ $item->photo ?? asset('backend/images/profile-6.jpg')}}" alt="" class="circular-image" style="max-height: 250px;"/>
                                </a>
                            </div>
                            <div class="portfolio-caption pt-30px pb-30px sm-pt-25px sm-pb-25px">
                                <a href="{{ route('front.song.category', ['slug' => $item->slug]) }}" class="text-dark-gray text-dark-gray-hover fw-600">{{$item->title}}</a>
                                <!-- <span class="d-inline-block align-middle w-10px separator-line-1px bg-light-gray ms-10px me-10px"></span> -->
                                <!-- <div class="d-inline-block">{{$item->title}}</div> -->
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>