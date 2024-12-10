@extends('backend.layouts.master')

@section('content')
    <div class="container mx-auto p-6 bg-white rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">Chi tiết tài nguyên</h1>

        <h3 class="text-xl font-semibold mb-2">{{ $resource->title }}</h3>

        <div class="mb-4">
            @if($resource->link_code)
                <!-- Kiểm tra loại tài nguyên dựa trên type_code và link_code -->
                @switch($resource->type_code)
                    @case('youtube')
                        <!-- Nếu type_code là youtube, hiển thị video YouTube -->
                        @php
                            // Lấy ID video từ URL YouTube
                            preg_match('/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $resource->url, $matches);
                            $videoId = $matches[1] ?? '';
                        @endphp
                        @if($videoId)
                            <iframe width="100%" height="500" src="https://www.youtube.com/embed/{{ $videoId }}"
                                    frameborder="0" allowfullscreen></iframe>
                        @else
                            <p class="text-red-500">URL YouTube không hợp lệ.</p>
                        @endif
                    @break

                    @case('instagram')
                        <!-- Nếu type_code là instagram, hiển thị ảnh/ video Instagram -->
                        <iframe width="100%" height="500" src="https://www.instagram.com/p/{{ extractInstagramId($resource->url) }}/embed"
                                frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    @break

                    @case('vimeo')
                        <!-- Nếu type_code là vimeo, hiển thị video Vimeo -->
                        @php
                            $videoId = basename(parse_url($resource->url, PHP_URL_PATH));
                        @endphp
                        <iframe width="100%" height="500" src="https://player.vimeo.com/video/{{ $videoId }}" frameborder="0" allowfullscreen></iframe>
                    @break
                    
                    @case('spotify')
                        <!-- Nếu type_code là spotify, hiển thị bài hát/ podcast Spotify -->
                        <iframe src="https://open.spotify.com/embed/track/{{ substr($resource->url, strrpos($resource->url, '/') + 1) }}"
                                width="100%" height="500" frameborder="0" allow="encrypted-media" allowfullscreen></iframe>
                    @break

                    @case('dailymotion')
                        <!-- Nếu type_code là dailymotion, hiển thị video Dailymotion -->
                        @php
                            $videoId = basename(parse_url($resource->url, PHP_URL_PATH));
                        @endphp
                        <iframe width="100%" height="500" src="https://www.dailymotion.com/embed/video/{{ $videoId }}"
                                frameborder="0" allowfullscreen></iframe>
                    @break

                    @case('facebook')
                        <!-- Nếu type_code là facebook, hiển thị video Facebook -->
                        <iframe style="width: 100%; height: 50rem;"
                                            src="https://www.facebook.com/plugins/video.php?href={{ urlencode($resource->url) }}"
                                            frameborder="0" allowfullscreen></iframe>
                    @break

                    @case('twitter')
                        <!-- Nếu type_code là twitter, hiển thị tweet -->
                        <blockquote class="twitter-tweet">
                            <a href="{{ $resource->url }}"></a>
                        </blockquote>
                        <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
                    @break

                    @case('googleMaps')
                        <!-- Nếu type_code là Google Maps, hiển thị bản đồ -->
                        <iframe src="{{ $resource->url }}" width="100%" height="500" frameborder="0" allowfullscreen></iframe>
                    @break

                    @case('flickr')
                        <!-- Nếu type_code là Flickr, hiển thị ảnh từ Flickr -->
                        <iframe src="https://www.flickr.com/photos/{{ extractFlickrUser($resource->url) }}/{{ extractFlickrPhotoId($resource->url) }}/embed"
                                width="100%" height="500" frameborder="0" allowfullscreen></iframe>
                    @break

                    @default
                        <!-- Xử lý các loại khác như ảnh, video, tài liệu -->
                        @switch(true)
                            @case(strpos($resource->file_type, 'image/') === 0)
                                <img src="{{ $resource->url }}" alt="{{ $resource->title }}" class="w-full h-96 object-cover" />
                            @break
                            @case(strpos($resource->file_type, 'video/') === 0)
                                <video controls class="w-full h-96">
                                    <source src="{{ $resource->url }}" type="{{ $resource->file_type }}">
                                    Trình duyệt của bạn không hỗ trợ thẻ video.
                                </video>
                            @break
                            @case(strpos($resource->file_type, 'audio/') === 0)
                                <audio controls class="w-full">
                                    <source src="{{ $resource->url }}" type="{{ $resource->file_type }}"/>
                                    Trình duyệt của bạn không hỗ trợ thẻ audio.
                                </audio>
                            @break
                            @case($resource->file_type === 'application/pdf')
                                <embed src="{{ $resource->url }}" type="application/pdf" class="w-full h-96" />
                            @break
                            
                            @default
                                <img src="{{ asset('backend/assets/icons/icon1.png') }}" alt="{{ $resource->title }}" class="w-full h-96 object-cover" />
                        @endswitch
                @endswitch
            @else
                <!-- Xử lý tài nguyên nếu không có link_code -->
                @switch(true)
                    @case(strpos($resource->file_type, 'image/') === 0)
                        <img src="{{ $resource->url }}" alt="{{ $resource->title }}" class="w-full h-96 object-cover" />
                    @break
                    @case(strpos($resource->file_type, 'video/') === 0)
                        <video controls class="w-full h-96">
                            <source src="{{ $resource->url }}" type="{{ $resource->file_type }}">
                            Trình duyệt của bạn không hỗ trợ thẻ video.
                        </video>
                    @break
                    @case(strpos($resource->file_type, 'audio/') === 0)
                        <audio controls class="w-full">
                            <source src="{{ $resource->url }}" type="{{ $resource->file_type }}"/>
                            Trình duyệt của bạn không hỗ trợ thẻ audio.
                        </audio>
                    @break
                    @case($resource->file_type === 'application/pdf')
                        <embed src="{{ $resource->url }}" type="application/pdf" class="w-full h-96" />
                    @break
                    @default
                        <img src="{{ asset('backend/assets/icons/icon1.png') }}" alt="{{ $resource->title }}" class="w-full h-96 object-cover" />
                @endswitch
            @endif
        </div>

        <!-- Các thông tin khác về tài nguyên -->
        <div class="mb-4">
            <p class="font-medium">File type: <span class="font-normal">{{ $resource->file_type }}</span></p>
            <p class="font-medium">File size: <span class="font-normal">{{ $resource->file_size }} bytes</span></p>

            <p class="font-medium">Tags:</p>
<p class="font-normal">
    @php
        $tags = json_decode($resource->tags);
    @endphp

    @if(is_array($tags) || is_object($tags))
        @foreach ($tags as $tagId)
            @php
                $tag = \App\Models\Tag::find($tagId);
            @endphp
            {{ $tag ? $tag->title : 'Tag not found' }}@if(!$loop->last), @endif
        @endforeach
    @else
        Tag not found
    @endif
</p>



           


            <p class="font-medium">Description:</p>
            <div class="font-normal">
                {!! nl2br(strip_tags($resource->description)) !!}
            </div>

            <p class="font-medium">URL:</p>
            <p class="font-normal">
                <a href="{{ $resource->url }}" class="text-blue-500 underline" target="_blank">{{ $resource->url }}</a>
            </p>
        </div>

        <!-- Các nút chức năng -->
        <div class="flex space-x-2">
            <a href="{{ route('admin.resources.edit', $resource->id) }}" class="flex items-center text-blue-600">
                <i data-lucide="check-square" class="w-4 h-4 mr-1"></i>
                Chỉnh sửa
            </a>

            <form id="delete-form-{{ $resource->id }}" action="{{ route('admin.resources.destroy', $resource->id) }}"
                method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <a class="flex items-center text-danger dltBtn" data-id="{{ $resource->id }}" href="javascript:;">
                    <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i>
                    Xóa
                </a>
            </form>

            <a href="{{ route('admin.resources.index') }}" class="flex items-center text-green-600">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i>
                Quay lại
            </a>
        </div>
    </div>
@endsection
