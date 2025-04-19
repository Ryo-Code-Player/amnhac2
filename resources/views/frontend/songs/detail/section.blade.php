<section class="pb-0 pt-4">
    <div class="container mt-8">
        <div class="row">
            @if($resources->count() > 0)
            @foreach ($resources as $index => $resource)
            <div id="resources" data-resources="{{ json_encode($resources) }}" style="display: none;"></div>

                @if (str_contains($resource->url, '.mp4'))
                    <p class="font-medium">Video</p>
                    <video controls class="w-full h-48 object-cover mx-auto rounded-md" id="video-{{$index}}">
                        <source src="{{ asset($resource->url) }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                    <button class="mt-2 text-blue-500" onclick="togglePiP('video-{{$index}}')">PiP</button>
                @elseif (in_array(pathinfo($resource->url, PATHINFO_EXTENSION), ['jpeg', 'jpg', 'png', 'gif']))
                    <p class="font-medium">Ảnh</p>
                    <img src="{{ asset($resource->url) }}" alt="Resource Image" class="w-full h-48 object-cover rounded-md mx-auto">
                @elseif (pathinfo($resource->url, PATHINFO_EXTENSION) == 'pdf')
                    <p class="font-medium">Tài liệu PDF</p>
                    <iframe src="{{ asset($resource->url) }}" width="100%" height="300px" class="mx-auto"></iframe>
                    <div class="mt-2">
                        <a href="{{ asset($resource->url) }}" target="_blank" class="text-blue-500 hover:underline">
                            Mở file PDF
                        </a>
                    </div>
                @elseif (pathinfo($resource->url, PATHINFO_EXTENSION) == 'mp3')
                    <p class="font-medium">Âm thanh</p>
                    <audio controls class="w-full h-auto mx-auto rounded-md">
                        <source src="{{ asset($resource->url) }}" type="audio/mpeg">
                        Your browser does not support the audio tag.
                    </audio>
                    <div class="mt-2">
                        <a href="{{ asset($resource->url) }}" target="_blank" class="text-blue-500 hover:underline">
                            Mở hoặc tải file MP3
                        </a>
                    </div>
                    @elseif (pathinfo($resource->url, PATHINFO_EXTENSION) == 'ppt' || pathinfo($resource->url, PATHINFO_EXTENSION) == 'pptx')
                        <p class="font-medium">Tài liệu PowerPoint</p>
                        <iframe src="https://docs.google.com/gview?url={{ urlencode(asset($resource->url)) }}&embedded=true" width="100%" height="300px" class="mx-auto"></iframe>
                            @elseif (pathinfo($resource->url, PATHINFO_EXTENSION) == 'docx')
                        <p class="font-medium">Tài liệu Word</p>
                        <iframe src="https://docs.google.com/gview?url={{ urlencode($resource->url) }}&embedded=true" style="width: 100%; height: 500px;"></iframe>


                        <p class="font-medium">Tệp nén</p>
                        <div class="mt-2">
                            <a href="{{ asset($resource->url) }}" target="_blank" class="text-blue-500 hover:underline">
                                Tải về file nén
                            </a>
                        </div>
                @elseif (str_contains($resource->url, 'youtube.com/watch?v=')) 
                    @php
                        preg_match('/v=([^&]+)/', $resource->url, $matches);
                        $videoId = $matches[1] ?? '';
                    @endphp
                    @if($videoId)
                        <!-- <p class="font-medium">Video YouTube</p> -->
                        <iframe width="100%" height="600px" src="https://www.youtube.com/embed/{{ $videoId }}" frameborder="0" allowfullscreen class="mx-auto"></iframe>
                    @else
                        <p>Không có video YouTube hợp lệ.</p>
                    @endif
                @elseif (str_contains($resource->url, 'instagram.com'))
                    <p class="font-medium">Instagram</p>
                    <iframe width="100%" height="400px" src="https://www.instagram.com/p/{{ basename($resource->url) }}/embed" frameborder="0" allowfullscreen class="mx-auto"></iframe>
                @elseif (str_contains($resource->url, 'vimeo.com'))
                    <p class="font-medium">Vimeo</p>
                    @php
                        preg_match('/vimeo\.com\/(\d+)/', $resource->url, $matches);
                        $videoId = $matches[1] ?? '';
                    @endphp
                    @if($videoId)
                        <iframe width="100%" height="400px" src="https://player.vimeo.com/video/{{ $videoId }}" frameborder="0" allowfullscreen class="mx-auto"></iframe>
                    @else
                        <p>Không có video Vimeo hợp lệ.</p>
                    @endif
                @elseif (str_contains($resource->url, 'spotify.com'))
                    <p class="font-medium">Spotify</p>
                    <iframe width="100%" height="380px" src="https://open.spotify.com/embed/track/{{ basename($resource->url) }}" frameborder="0" allow="encrypted-media" allowfullscreen class="mx-auto"></iframe>
                @elseif (str_contains($resource->url, 'dailymotion.com'))
                    <p class="font-medium">Dailymotion</p>
                    @php
                        preg_match('/dailymotion\.com\/video\/([^_]+)/', $resource->url, $matches);
                        $videoId = $matches[1] ?? '';
                    @endphp
                    @if($videoId)
                        <iframe width="100%" height="400px" src="https://www.dailymotion.com/embed/video/{{ $videoId }}" frameborder="0" allowfullscreen class="mx-auto"></iframe>
                    @else
                        <p>Không có video Dailymotion hợp lệ.</p>
                    @endif
                @elseif (str_contains($resource->url, 'facebook.com'))
                    <p class="font-medium">Facebook</p>
                    <iframe width="100%" height="400px" src="https://www.facebook.com/plugins/post.php?href={{ urlencode($resource->url) }}" frameborder="0" allow="encrypted-media" allowfullscreen class="mx-auto"></iframe>
                @elseif (str_contains($resource->url, 'twitter.com'))
                    <p class="font-medium">Twitter</p>
                    <iframe width="100%" height="400px" src="https://platform.twitter.com/widgets/tweetembed.js?url={{ urlencode($resource->url) }}" frameborder="0" class="mx-auto"></iframe>
                @elseif (str_contains($resource->url, 'google.com/maps'))
                    <p class="font-medium">Google Maps</p>
                    <iframe width="100%" height="400px" src="{{ $resource->url }}" frameborder="0" allowfullscreen class="mx-auto"></iframe>
                @elseif (str_contains($resource->url, 'flickr.com'))
                    <p class="font-medium">Flickr</p>
                    <iframe width="100%" height="400px" src="https://www.flickr.com/embed/{{ basename($resource->url) }}" frameborder="0" allowfullscreen class="mx-auto"></iframe>
                @else
                    <p>Không hỗ trợ định dạng tệp này.</p>
                @endif
                @endforeach
                @endif
        </div>

        <div class="row mt-10">
            <div class="col-md-4">
                <img src="{{ $song->singer->photo }}" alt="{{ $song->title }}" class="img-fluid">
            </div>
            <div class="col-md-8">
                <h2>{{ $song->title }}</h2>
                <p><strong>Ca sĩ:</strong> {{ $song->singer->alias ?? 'Unknown' }}</p>
                <p><strong>Thể loại:</strong> {{ $song->musicType->title ?? 'Unknown' }}</p>
                <p><strong>Tóm tắt:</strong> {{ $song->summary }}</p>
                
                <a href="{{ route('front.song') }}" class="btn btn-primary">Quay lại danh sách</a>
            </div>
        </div>
        
        <div class="row">
            <p><strong>Nội dung:</strong> {!! $song->content !!}</p>
        </div>
    </div>
</section>