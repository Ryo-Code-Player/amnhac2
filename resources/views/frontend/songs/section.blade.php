<section class="pb-0 pt-4">
    <div class="container">
        <div class="row align-items-center mb-4">
            <div class="col-12 text-center text-md-start">
                <!-- filter navigation -->
                <ul class="portfolio-filter nav nav-tabs justify-content-center border-0 fw-500 alt-font fs-19">
                    <li class="nav active"><a data-filter="*" href="#">All</a></li>
                    <li class="nav"><a data-filter=".hot" href="#">HOT</a></li>
                    <li class="nav"><a data-filter=".new" href="#">MỚI</a></li>
                </ul>
                <!-- end filter navigation -->
            </div>
        </div>
        <div class="row">
            <div class="col-12 filter-content p-md-0 song">
                <ul class="portfolio-simple portfolio-wrapper  alt-font fs-18  grid-3col xxl-grid-3col xl-grid-3col lg-grid-3col md-grid-2col sm-grid-1col xs-grid-1col gutter-extra-large text-center">
                    <li class="grid-sizer"></li>
                    <!-- start portfolio item -->
                    @foreach ($songs as $song)
                    <li class="grid-item hot new transition-inner-all">
                        <div class="portfolio-box">
                            <div class="portfolio-image border-radius-6px bg-dark-gray">
                                <a href="{{ route('front.song.detail', ['slug' => $song->slug]) }}">
                                    <img src="{{ $song->singer->photo ?? 'Unknown' }}" alt="" class="circular-image" />
                                </a>
                            </div>
                            <div class="portfolio-caption pt-30px pb-30px sm-pt-25px sm-pb-25px">
                                <a href="{{ route('front.song.detail', ['slug' => $song->slug]) }}" class="text-dark-gray text-dark-gray-hover fw-600">{{ $song->title }}</a>
                                <span class="d-inline-block align-middle w-10px separator-line-1px bg-light-gray ms-10px me-10px"></span>
                                <div class="d-inline-block">{{ $song->singer->alias ?? 'Unknown' }}</div>
                                <div class="d-inline-block">{{ $song->musictype->title }}</div>
                            </div>
                        </div>
                    </li>
                    @endforeach
                    <!-- end portfolio item -->
                </ul>
            </div>
        </div>
    </div>
</section>