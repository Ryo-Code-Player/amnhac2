@extends('backend.layouts.master')

@section('scriptop')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/js/tom-select.complete.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('js/css/tom-select.min.css') }}">
@endsection

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Chỉnh sửa tài nguyên
        </h2>
    </div>
    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">

            <form method="post" action="{{ route('admin.resources.update', $resource->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="intro-y box p-5">
                    <div>
                        <label for="title" class="form-label">Tiêu đề</label>
                        <input id="title" name="title" type="text" class="form-control" placeholder="Nhập tiêu đề"
                            value="{{ $resource->title }}" required>
                    </div>

                    <div class="mt-3">
                        <label for="type_code" class="form-label">Loại tài nguyên</label>
                        <select name="type_code" class="form-select mt-2">
                            @foreach ($resourceTypes as $type)
                                <option value="{{ $type->code }}"
                                    {{ $resource->type_code == $type->code ? 'selected' : '' }}>
                                    {{ $type->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mt-3">
                        <label for="link_code" class="form-label">Loại liên kết tài nguyên</label>
                        <select name="link_code" class="form-select mt-2">
                            <option value="">- Chọn loại liên kết -</option>
                            @foreach ($linkTypes as $type)
                                <option value="{{ $type->code }}"
                                    {{ $resource->link_code == $type->code ? 'selected' : '' }}>
                                    {{ $type->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mt-3">
                        <label for="description" class="form-label">Mô tả chi tiết</label>
                        <textarea id="description" name="description" class="form-control" placeholder="Nhập mô tả chi tiết">{{ old('description', $resource->description) }}</textarea>
                    </div>

                    <div class="mt-3">
                        <label for="file" class="form-label">Tệp phương tiện (tùy chọn)</label>
                        <input type="file" name="file" class="form-control">

                        
                        @if (!empty($resource->file_name) && !empty($resource->url))
                            <p class="mt-2">
                                <strong>Tệp đã tải lên:</strong>
                                <a href="{{ asset($resource->url) }}" target="_blank">{{ $resource->file_name }}</a>
                            </p>
                        @else
                            <p class="mt-2 text-gray-600">Chưa có tệp nào được chọn.</p>
                        @endif
                    </div>



                    <div class="mt-3">
                        <label for="url" class="form-label">Liên kết  (nếu có)</label>
                        <input id="url" name="url" type="url" class="form-control"
                            placeholder="Nhập liên kết hình ảnh" value="{{$resource->link_code? old('url', $resource->url):'' }}">
                    </div>

                    <div class="mt-3">
                        <label for="post-form-4" class="form-label">Tags</label>
                        <select id="select-junk" name="tags[]" multiple placeholder="..." autocomplete="off">
                        @foreach ($tags as $tag)
            <option value="{{ $tag->id }}" 
                {{ in_array($tag->id, old('tags', $attachedTags)) ? 'selected' : '' }}>
                {{ $tag->title }}
            </option>
        @endforeach
                        </select>                        
                    </div>     


<!-- Input field for new tags (nhập tags mới) -->
<div class="form-group">
    <label for="new_tags">Nhập tags mới (cách nhau bằng dấu phẩy)</label>
    <input type="text" name="new_tags" class="form-control" id="new_tags" placeholder="Nhập tag mới" value="{{ old('new_tags') }}">
</div>

<!-- Chỗ để hiển thị thông tin khi nhập tag mới -->
<input type="hidden" name="new_tags_input" id="new_tags_input" value="{{ old('new_tags_input') }}">



                    <div class="text-right mt-5">
                        <button type="submit" class="btn btn-primary w-24">Cập nhật</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    var select = new TomSelect('#select-junk',{
        maxItems: null,
        allowEmptyOption: true,
        plugins: ['remove_button'],
        sortField: {
            field: "text", 
            direction: "asc"
        },
        onItemAdd:function(){
                this.setTextboxValue('');
                this.refreshOptions();
            },
        create: true
        
    });
   
</script>
    <script src="{{ asset('js/js/ckeditor.js') }}"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#description'), {
                ckfinder: {
                    uploadUrl: '{{ route('admin.upload.ckeditor') . '?_token=' . csrf_token() }}'
                },
                mediaEmbed: {
                    previewsInData: true
                }
            })
            .then(editor => {
                console.log(editor);
            })
            .catch(error => {
                console.error(error);
            });
    </script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Khởi tạo TomSelect cho trường select
        var tagSelect = new TomSelect('#tags', {
            create: false,  
            maxItems: null,  // Không giới hạn số lượng tags
            placeholder: 'Chọn tags',  // 
            persist: false,  // Tránh tag trùng lặp trong danh sách chọn
            tokenSeparators: [',', ' '],  // Phân tách tags bằng dấu phẩy hoặc dấu cách
        });

        // Lắng nghe sự kiện thay đổi trong ô nhập liệu tag mới
        document.getElementById('new_tags').addEventListener('input', function() {
            var newTags = this.value.split(',').map(tag => tag.trim()).filter(tag => tag);  // Lấy các tag mới nhập vào
            // Cập nhật giá trị mới vào trường ẩn để gửi lên server
            document.getElementById('new_tags_input').value = newTags.join(',');  // Nối các tag mới bằng dấu phẩy
        });
    });
</script>
@endsection
