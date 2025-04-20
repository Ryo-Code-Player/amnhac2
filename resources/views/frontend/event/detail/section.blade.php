<section id="event">
    <div class="container py-4">
        @foreach($event as $item)
            <header class="mb-4">
                <h1 class="text-center">{{$item->title}}</h1>
            </header>

            <div class="row g-4">
                {!! $item->description !!}
            </div>
        @endforeach
        
    </div>
</section>