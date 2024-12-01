@extends('backend.layouts.master')

@section('scriptop')

@section('content')

<div class="content">
    @include('backend.layouts.notification')

    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Thông tin bài hát
        </h2>
    </div>

    <div class="grid grid-cols-12 gap-6">
      <!-- BEGIN: Profile Menu -->
<div class="col-span-12 lg:col-span-3 2xl:col-span-2 flex lg:block flex-col-reverse">
    <div class="intro-y box mt-5">
        <div class="relative flex items-center p-5">
            <div class="p-6">
                @if($resources->count() > 0)
                    @foreach ($resources as $index => $resource)
                        <div class="my-2 resource-item" id="resource-{{$index}}" style="display: none;">
                            <label class="font-medium">Tài nguyên:</label>
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
                                    <p class="font-medium">Video YouTube</p>
                                    <iframe width="100%" height="100%" src="https://www.youtube.com/embed/{{ $videoId }}" frameborder="0" allowfullscreen class="mx-auto"></iframe>
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

                            <div class="relative mt-4">
                                <button id="prevButton" class="btn btn-primary position-absolute left-0 ml-4" onclick="navigateResources(-1)">← Trước</button>
                                <button id="nextButton" class="btn btn-primary position-absolute right-0 mr-4" onclick="navigateResources(1)">Sau →</button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p>Không có tài nguyên nào.</p>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- END: Resources Section -->

     
     <!-- BEGIN: Display Information -->
     <div class="intro-y box lg:mt-5 col-span-12 lg:col-span-9 2xl:col-span-10">
            <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                <h2 class="font-medium text-base mr-auto">
                    {{$song->title}} 
                </h2>
            </div>

            <div class="p-5">
                <div class="flex flex-col xl:flex-row gap-6">
                    <div class="flex-1">
                        <div class="grid grid-cols-12 gap-x-5">
                            <div class="col-span-12 sm:col-span-6">
                                <label for="update-profile-form-1" class="font-medium form-label">Ca sĩ:</label>
                                <p>{{ $song->singer->alias ?? 'Không có ca sĩ' }}</p>
                            </div>

                            <div class="col-span-12 sm:col-span-6">
                                <label for="update-profile-form-1" class="font-medium form-label">Nhạc sĩ:</label>
                                <p>{{ $song->composer->fullname ?? 'Không có nhạc sĩ' }}</p>
                            </div>

                            <div class="col-span-12 sm:col-span-6">
                                <label for="update-profile-form-1" class="font-medium form-label">Trạng thái:</label>
                                <p>{{$song->status}}</p>
                            </div>

                            <div class="col-span-12">
                                <label for="update-profile-form-1" class="font-medium form-label">Tóm tắt:</label>
                                <p>{!! $song->summary !!}</p>
                            </div>

                            <div class="col-span-12">
                                <label for="update-profile-form-1" class="font-medium form-label">Nội dung:</label>
                                <p>{!! $song->content !!}</p>
                            </div>

                            <div class="col-span-12">
                                <label for="update-profile-form-1" class="font-medium form-label">Tags:</label>
                                <p>
                                    @foreach (json_decode($song->tags) as $tagId)
                                        @php
                                            $tag = \App\Models\Tag::find($tagId);
                                        @endphp
                                        {{ $tag ? $tag->title : 'Tag not found' }}@if(!$loop->last), @endif
                                    @endforeach
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: Display Information -->
    </div>
</div>

<script>
    function navigateResources(direction) {
        let currentIndex = parseInt(document.querySelector('.resource-item[style="display: block;"]').id.replace('resource-', ''));
        let resources = document.querySelectorAll('.resource-item');
        let nextIndex = (currentIndex + direction + resources.length) % resources.length;
        
        resources[currentIndex].style.display = 'none';
        resources[nextIndex].style.display = 'block';
    }

    document.addEventListener('DOMContentLoaded', () => {
        let resources = document.querySelectorAll('.resource-item');
        if (resources.length > 0) {
            resources[0].style.display = 'block';
        }
    });
</script>

@endsection
