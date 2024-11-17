@extends('backend.layouts.master')

@section('scriptop')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/js/tom-select.complete.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('/js/css/tom-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
@endsection

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">Điều chỉnh bài hát</h2>
    </div>
    
    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <form method="post" action="{{ route('admin.song.update', $song->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <div class="intro-y box p-5">
                    <!-- Các trường thông tin bài hát -->
                    <div>
                        <label for="title" class="form-label">Tên bài hát</label>
                        <input id="title" name="title" type="text" value="{{ old('title', $song->title) }}" class="form-control" placeholder="Tên bài hát" required>
                    </div>

                    <div class="mt-3">
                        <label for="composer_id" class="form-label">Nhạc Sĩ</label>
                        <select name="composer_id" class="form-select mt-2 sm:mr-2" required>
                            <option value="">Chọn nhạc sĩ</option>
                            @foreach ($composers as $composer)
                                <option value="{{ $composer->id }}" {{ old('composer_id', $song->composer_id) == $composer->id ? 'selected' : '' }}>{{ $composer->fullname }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mt-3">
                        <label for="singer_id" class="form-label">Ca sĩ</label>
                        <select name="singer_id" class="form-select mt-2 sm:mr-2" required>
                            <option value="">Chọn ca sĩ</option>
                            @foreach ($singers as $singer)
                                <option value="{{ $singer->id }}" {{ old('singer_id', $song->singer_id) == $singer->id ? 'selected' : '' }}>{{ $singer->alias }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Mô tả ngắn -->
                    <div class="mt-3">
                        <label for="summary" class="form-label">Mô tả ngắn</label>
                        <textarea class="form-control" id="editor1" name="summary">{{ old('summary', $song->summary) }}</textarea>
                    </div>

                    <!-- Nội dung -->
                    <div class="mt-3">
                        <label for="content" class="form-label">Nội dung</label>
                        <textarea class="form-control" name="content" id="editor2">{{ old('content', $song->content) }}</textarea>
                    </div>

                    <!-- Phần tài nguyên (file nhạc, MV, v.v.) -->
                    <div class="form-group mt-3">
                        <label for="resources">Tài nguyên hiện có:</label>
                        <ul>
                            @foreach($resources as $resource)
                                <li>
                                    <a href="{{ $resource['url'] }}" target="_blank">{{ $resource['file_name'] }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Thêm file mới -->
                    <div class="form-group">
                        <label for="resources">Tải tài nguyên mới:</label>
                        <input type="file" name="resources[]" multiple>
                    </div>

                      <!-- Trường Tags (chọn các tags đã có từ trước) -->
<div class="form-group">
    <label for="tags">Tags</label>
    <select id="tags" name="tags[]" class="form-control" multiple>
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

                    <!-- Phần tình trạng -->
                    <div class="mt-3">
                        <label class="form-label" for="status">Tình trạng</label>
                        <select name="status" class="form-select mt-2 sm:mr-2">
                            <option value="active" {{ $song->status == 'active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="inactive" {{ $song->status == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                        </select>
                    </div>

                    <!-- Thông báo lỗi -->
                    <div class="mt-3">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    <!-- Nút cập nhật -->
                    <div class="text-right mt-5">
                        <button type="submit" class="btn btn-primary w-24">Cập nhật</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/js/ckeditor.js') }}"></script>
    <script>
        // Khởi tạo CKEditor cho trường summary
        ClassicEditor.create(document.querySelector('#editor1')).catch(error => {
            console.error(error);
        });

        // Khởi tạo CKEditor cho trường content
        ClassicEditor.create(document.querySelector('#editor2'), {
            ckfinder: {
                uploadUrl: "{{ route('admin.upload.ckeditor') }}",
            }
        }).catch(error => {
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
