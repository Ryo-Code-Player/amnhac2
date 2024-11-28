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

                    @case('image')
                        <!-- Nếu type_code là image, hiển thị hình ảnh -->
                        <img src="{{ $resource->url }}" alt="{{ $resource->title }}" class="w-full h-96 object-cover" />
                    @break

                    @case('document')
                        <!-- Nếu type_code là document, hiển thị tài liệu -->
                        <a href="{{ $resource->url }}" class="text-blue-500 underline">Tải tài liệu</a>
                    @break

                    @default
                        <!-- Nếu không phải các loại trên, xử lý linh hoạt theo loại file -->
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
                <!-- Xử lý tài nguyên nếu không có link_code (ví dụ: file đã được tải lên hoặc có URL không phải YouTube) -->
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

        <div class="mb-4">
            <p class="font-medium">File type: <span class="font-normal">{{ $resource->file_type }}</span></p>
            <p class="font-medium">File size: <span class="font-normal">{{ $resource->file_size }} bytes</span></p>

            <p class="font-medium">Tags:</p>
            <p class="font-normal">
                @php
                    $tags = \App\Models\Tag::whereIn('id', $tag_ids)->pluck('title');
                @endphp
                {{ $tags->implode(', ') }}
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

            <a href="{{ route('admin.resources.index') }}" class="flex items-center text-secondary">
                <i data-lucide="arrow-left-circle" class="w-4 h-4 mr-1"></i>
                Quay lại danh sách
            </a>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $('.dltBtn').click(function(e) {
            var resourceId = $(this).data('id');
            var form = $('#delete-form-' + resourceId);
            e.preventDefault();
            Swal.fire({
                title: 'Bạn có chắc muốn xóa không?',
                text: "Bạn không thể lấy lại dữ liệu sau khi xóa",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Vâng, tôi muốn xóa!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                    Swal.fire(
                        'Đã xóa!',
                        'Tài nguyên của bạn đã được xóa.',
                        'success'
                    );
                }
            });
        });
    </script>
@endsection
