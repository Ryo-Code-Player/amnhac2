<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

@if (isset($resources) && count($resources) > 0)
    @foreach ($resources as $resource)
        <div class="intro-y col-span-6 sm:col-span-4 lg:col-span-3">
            <div class="card p-4 border rounded shadow-md relative">
                <a href="{{ route('admin.resources.show', $resource->id) }}">
                    <div class="relative">
                        {{-- Kiểm tra loại file để hiển thị nội dung phù hợp --}}
                        @if ($resource->link_code)
                            @switch($resource->link_code)
                                @case('youtube')
                                    @if ($resource->type_code == 'youtube')
                                        <iframe style="width: 100%; height: 10rem;"
                                            src="{{ str_replace('watch?v=', 'embed/', $resource->url) }}" frameborder="0"
                                            allowfullscreen></iframe>
                                    @endif
                                    @break
                                @case('vimeo')
                                    @if ($resource->type_code == 'vimeo')
                                        <iframe style="width: 100%; height: 10rem;"
                                            src="https://player.vimeo.com/video/{{ substr($resource->url, strrpos($resource->url, '/') + 1) }}"
                                            frameborder="0" allowfullscreen></iframe>
                                    @endif
                                    @break
                                @case('spotify')
                                    @if ($resource->type_code == 'spotify')
                                        <iframe style="width: 100%; height: 10rem;"
                                            src="https://open.spotify.com/embed/track/{{ substr($resource->url, strrpos($resource->url, '/') + 1) }}"
                                            frameborder="0" allowfullscreen></iframe>
                                    @endif
                                    @break
                                @case('dailymotion')
                                    @if ($resource->type_code == 'dailymotion')
                                        <iframe style="width: 100%; height: 10rem;"
                                            src="https://www.dailymotion.com/embed/video/{{ substr($resource->url, strrpos($resource->url, '/') + 1) }}"
                                            frameborder="0" allowfullscreen></iframe>
                                    @endif
                                    @break
                                @case('facebook')
                                    @if ($resource->type_code == 'facebook')
                                        <iframe style="width: 100%; height: 10rem;"
                                            src="https://www.facebook.com/plugins/video.php?href={{ urlencode($resource->url) }}"
                                            frameborder="0" allowfullscreen></iframe>
                                    @endif
                                    @break
                                @case('twitter')
                                    @if ($resource->type_code == 'twitter')
                                        <iframe style="width: 100%; height: 10rem;"
                                            src="https://platform.twitter.com/embed/tweet?url={{ urlencode($resource->url) }}"
                                            frameborder="0" allowfullscreen></iframe>
                                    @endif
                                    @break
                                @case('googleMaps')
                                    @if ($resource->type_code == 'googleMaps')
                                        <iframe style="width: 100%; height: 10rem;"
                                            src="https://www.google.com/maps/embed?pb={{ urlencode($resource->url) }}"
                                            frameborder="0" allowfullscreen></iframe>
                                    @endif
                                    @break
                                @case('flickr')
                                    @if ($resource->type_code == 'flickr')
                                        <iframe style="width: 100%; height: 10rem;"
                                            src="https://www.flickr.com/embed/{{ substr($resource->url, strrpos($resource->url, '/') + 1) }}"
                                            frameborder="0" allowfullscreen></iframe>
                                    @endif
                                    @break
                                @case('audio')
                                    @if ($resource->type_code == 'audio')
                                        <audio controls style="width: 100%;">
                                            <source src="{{ $resource->url }}" type="audio/mpeg">
                                            Trình duyệt của bạn không hỗ trợ thẻ audio.
                                        </audio>
                                    @endif
                                    @break
                                @case('video')
                                    @if ($resource->type_code == 'video')
                                        <video controls style="width: 100%; height: 10rem;">
                                            <source src="{{ $resource->url }}" type="video/mp4">
                                            Trình duyệt của bạn không hỗ trợ thẻ video.
                                        </video>
                                    @endif
                                    @break
                                @case('image')
                                    @if ($resource->type_code == 'image')
                                        <img src="{{ $resource->url }}" alt="{{ $resource->title }}"
                                            style="width: 100%; height: 10rem; object-fit: cover;" />
                                    @endif
                                    @break
                                @case('document')
                                    @if ($resource->type_code == 'document')
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <!-- Biểu tượng tải tài liệu -->
                                            <i class="fas fa-download" style="font-size: 160px; color: #1d4ed8;"></i>
                                            <a href="{{ $resource->url }}" class="text-blue-500 underline">
                                                Tải tài liệu
                                            </a>
                                        </div>
                                    @endif
                                    @break
                                @default
                                    <img src="{{ asset('backend/assets/icons/icon1.png') }}" alt="{{ $resource->title }}"
                                        style="width: 100%; height: 10rem; object-fit: cover;" />
                            @endswitch
                        @else
                            @php
                                $fileType = $resource->file_type;
                            @endphp
                            @switch(true)
                                @case(strpos($fileType, 'image/') === 0)
                                    <img src="{{ $resource->url }}" alt="{{ $resource->title }}"
                                        style="width: 100%; height: 10rem; object-fit: cover;" />
                                @break

                                @case(strpos($fileType, 'video/') === 0)
                                    <video controls style="width: 100%; height: 10rem;">
                                        <source src="{{ $resource->url }}" type="{{ $fileType }}"/>
                                        Trình duyệt của bạn không hỗ trợ thẻ video.
                                    </video>
                                @break

                                @case(strpos($fileType, 'audio/') === 0)
                                    <audio controls style="width: 100%;">
                                        <source src="{{ $resource->url }}" type="{{ $fileType }}"/>
                                        Trình duyệt của bạn không hỗ trợ thẻ audio.
                                    </audio>
                                @break

                                @case($fileType === 'application/pdf,application/docx,application/ppt')
                                    <embed src="{{ $resource->url }}" type="application/pdf"
                                        style="width: 100%; height: 10rem;" />
                                @break

                                @default
                                    <img src="{{ asset('backend/assets/icons/icon1.png') }}" alt="{{ $resource->title }}"
                                        style="width: 100%; height: 10rem; object-fit: cover;" />
                            @endswitch
                        @endif

                        <div class="image-title"
                            style="display: flex; align-items: center; justify-content: space-between; position: absolute; inset-x: 0; bottom: -1.5rem; background-color: rgba(255, 255, 255, 0.8); padding: 8px; width: 100%;">
                            <span
                                style="display: block;flex-grow: 1; text-align: left; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 50px;">
                                {{ $resource->title }}
                            </span>
                            <div style="display: flex; align-items: center; gap: 20px;">
                                <a href="{{ route('admin.resources.edit', $resource->id) }}" style="color: #3b82f6;"
                                    title="Chỉnh sửa">
                                    <i data-lucide="edit" style="width: 16px; height: 16px;"></i>
                                </a>
                                <form id="delete-form-{{ $resource->id }}"
                                    action="{{ route('admin.resources.destroy', $resource->id) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <a class="dltBtn" data-id="{{ $resource->id }}" href="javascript:;" title="Xóa"
                                        style="color: #ef4444;">
                                        <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
                                    </a>
                                </form>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    @endforeach
    <div class="pagination" style="margin-top: 200px; margin-bottom: 20px;">
        {{ $resources->appends(request()->except('page'))->links() }} <!-- Hiển thị các liên kết phân trang -->
    </div>
@else
    <div class="intro-y col-span-12">
        <div class="text-center p-4">
            <p class="text-lg text-red-600">Không tìm thấy tài nguyên nào!</p>
        </div>
    </div>
@endif
