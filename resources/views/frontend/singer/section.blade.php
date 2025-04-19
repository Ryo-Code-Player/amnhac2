<section class="position-relative pt-4">
            <img src="{{ asset('frontend/images/demo-data-analysis-bg-06.png')}}" class="position-absolute top-20 left-0px" data-bottom-top="transform: translateY(150px)" data-top-bottom="transform: translateY(-150px)" alt=""/>
            <div class="container position-relative">
                 
                <div class="row justify-content-center row-cols-3 row-cols-lg-6 row-cols-md-3 row-cols-sm-3 clients-style-03 mt-8 sm-mt-12" data-anime='{ "el": "childs", "scale": [0, 1], "opacity": [0,1], "duration": 600, "delay": 100, "staggervalue": 100, "easing": "easeOutQuad" }'>
                    <!-- start client item -->
                    @foreach($singer as $item)
                    <div class="col text-center md-mb-35px singer">
                        <div class="client-box">
                            <a href="{{ route('front.song.singer', ['slug' => $item->slug]) }}"><img src="{{ $item->photo}}" alt="" class="box-shadow-extra-large border-radius-100px w-150px"></a>
                            <span>{{ $item->alias }}</span>
                        </div>
                    </div>
                    @endforeach
                    <!-- end client item -->
                                  
                </div>
            </div>
        </section>
        <!-- end section -->
        