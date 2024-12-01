@extends('backend.layouts.master')

@section('scriptop')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="{{ asset('js/js/tom-select.complete.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('js/css/tom-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('path/to/dropzone.css') }}">
<script src="{{ asset('path/to/dropzone.js') }}"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

@endsection

@section('content')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Thêm Ca Sĩ</h2>
</div>
<div class="grid grid-cols-12 gap-12 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <form method="post" action="{{ route('admin.singer.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="intro-y box p-5">
                <div class="mt-3">
                    <label for="alias" class="form-label">Tên Ca Sĩ</label>
                    <input id="alias" name="alias" type="text" class="form-control" placeholder="Tên Ca Sĩ" value="{{ old('alias') }}" required>
                </div>

                <div class="mt-3">
                    <label for="photo" class="form-label">Ảnh</label>
                    <div class="px-4 pb-4 mt-5 flex items-center cursor-pointer relative">
                        <div data-single="true" id="mydropzone" class="dropzone" url="{{ route('admin.upload.avatar') }}">
                            <div class="fallback"><input name="file" type="file" /></div>
                            <div class="dz-message" data-dz-message>
                                <div class="font-medium">Kéo thả hoặc chọn ảnh.</div>
                            </div>
                        </div>
                        <input type="hidden" id="photo" name="photo"/>
                    </div>
                </div>
                <div class="form-group">
                <label for="start_year">Chọn Năm Bắt Đầu</label>
<input type="text" name="start_year" id="start_year" class="form-control" placeholder="Nhập năm bắt đầu" value="{{ old('start_year', date('Y')) }}">



                <div class="form-group">
    <label for="music_type">Chọn Thể Loại Âm Nhạc</label>
    <select name="type_id" id="music_type" class="form-control" required>
        <option value="">Chọn Thể Loại</option> <!-- Thêm tùy chọn mặc định -->
        @foreach($music_types as $musicType)
            <option value="{{ $musicType->id }}">{{ $musicType->title }}</option>
        @endforeach
    </select>
</div>


              

                <div class="form-group">
        <label for="company_id">Công ty Âm nhạc</label>
        <select name="company_id" id="company_id" class="form-control" required>
            <option value="">Chọn công ty âm nhạc</option>
            @foreach($musicCompanies as $company)
                <option value="{{ $company->id }}">{{ $company->title }}</option>
            @endforeach
        </select>
    </div>

    <div class="mt-3">
    <label for="summary" class="form-label">Mô tả ngắn</label>
    <textarea class="form-control" id="editor1" name="summary">{{ old('summary', $singer->summary ?? '') }}</textarea>

</div>

<div class="mt-3">
    <label for="content" class="form-label">Nội dung</label>
    <textarea class="form-control" id="editor2" name="content">{{ old('content', $singer->content ?? '') }}</textarea>
</div>
<!-- Trường Tags -->
<div class="form-group">
    <label for="tags">Tags</label>
    <select id="tags" name="tags[]" class="form-control" multiple>
        @foreach ($tags as $tag)
            <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', [])) ? 'selected' : '' }}>{{ $tag->title }}</option>
        @endforeach
    </select>
</div>

<!-- Input field for new tags -->
<div class="form-group">
    <label for="new_tags">Nhập tags mới (cách nhau bằng dấu phẩy)</label>
    <input type="text" name="new_tags" class="form-control" id="new_tags" placeholder="Nhập tag mới">
</div>

<!-- Chỗ để hiển thị thông tin khi nhập tag mới -->
<input type="hidden" name="new_tags_input" id="new_tags_input">



                <div class="mt-3">
                    <label for="status" class="form-label">Tình trạng</label>
                    <select name="status" class="form-select mt-2" required>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <input type="hidden" name="user_id" value="{{ Auth::id() }}">

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

                <div class="text-right mt-5">
                    <button type="submit" class="btn btn-primary w-24">Lưu</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    Dropzone.instances[0].options.multiple = false;
    Dropzone.instances[0].options.autoQueue = true;
    Dropzone.instances[0].options.maxFilesize = 2; // MB
    Dropzone.instances[0].options.maxFiles = 1;
    Dropzone.instances[0].options.dictDefaultMessage = 'Drop images anywhere to upload (1 image Max)';
    Dropzone.instances[0].options.acceptedFiles = "image/jpeg,image/png,image/gif";
    Dropzone.instances[0].options.addRemoveLinks = true;
    Dropzone.instances[0].options.headers = {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')};

    Dropzone.instances[0].on("success", function (file, response) {
        if(response.status == "true")
            $('#photo').val(response.link);
    });
    Dropzone.instances[0].on("removedfile", function (file) {
        $('#photo').val('');
    });
</script>

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
