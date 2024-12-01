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
    <h2 class="text-lg font-medium mr-auto">Thêm Công Ty Âm Nhạc</h2>
</div>
<div class="grid grid-cols-12 gap-12 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <form method="post" action="{{ route('admin.musiccompany.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="intro-y box p-5">
    <!-- Các trường thông tin công ty -->
    <div>
    <label for="title" class="form-label">Tên công ty</label>
    <input id="title" name="title" type="text" value="{{ old('title') }}" class="form-control" placeholder="Tên công ty" required>

</div>


    <!-- Input Address Field -->
    <div class="mt-3">
        <label for="address" class="form-label">Địa chỉ</label>
        <input id="address" name="address" type="text" class="form-control" placeholder="Nhập địa chỉ" 
               value="{{ old('address', $musicCompany->address ?? '') }}" required>
    </div>
    <div id="address-suggestions" class="mt-2" style="border: 1px solid #ccc; display: none;"></div>
</div>
               
                <div class="mt-3">
                    <label for="" class="form-label">Photo</label>
                    <div class="px-4 pb-4 mt-5 flex items-center cursor-pointer relative">
                        <div data-single="true" id="mydropzone" class="dropzone" url="{{route('admin.upload.avatar')}}">
                            <div class="fallback"><input name="file" type="file" /></div>
                            <div class="dz-message" data-dz-message>
                                <div class="font-medium">Kéo thả hoặc chọn ảnh.</div>
                            </div>
                        </div>
                        <input type="hidden" id="photo" name="photo"/>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="flex flex-col sm:flex-row items-center">
                        <label style="min-width:70px" class="form-select-label" for="phone">Điện thoại</label>
                        <input id="phone" name="phone" type="text" class="form-control" placeholder="Điện thoại" value="{{ old('phone') }}" required>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="flex flex-col sm:flex-row items-center">
                        <label style="min-width:70px" class="form-select-label" for="email">Email</label>
                        <input id="email" name="email" type="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required>
                    </div>
                </div>
                <div class="mt-3">
                    <label for="summary" class="form-label">Mô tả ngắn</label>
                    <textarea class="form-control" id="editor1" name="summary">{{ old('summary') }}</textarea>
                </div>
                <div class="mt-3">
                    <label for="content" class="form-label">Nội dung</label>
                    <textarea class="editor" name="content" id="editor2">{{ old('content') }}</textarea>
                </div>
        <!-- Các trường khác -->
     <div class="form-group">
        <label for="resources">Tải lên tài nguyên (File)</label>
        <input type="file" name="resources[]" id="resources" class="form-control" multiple>

        <!-- Nếu có thông báo lỗi -->
        @error('resources')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
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
                    <div class="flex flex-col sm:flex-row items-center">
                        <label style="min-width:70px" class="form-select-label" for="status">Tình trạng</label>
                        <select name="status" class="form-select mt-2 sm:mr-2" required>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
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
    ClassicEditor
        .create(document.querySelector('#editor1'))
        .catch(error => {
            console.error(error);
        });

    ClassicEditor
        .create(document.querySelector('#editor2'))
        .catch(error => {
            console.error(error);
        });
</script>

<script>
  document.getElementById('address').addEventListener('input', function() {
    const query = this.value;
    if (query.length < 3) {
        document.getElementById('address-suggestions').innerHTML = '';
        document.getElementById('address-suggestions').style.display = 'none';
        return;
    }

    fetch(`https://api.locationiq.com/v1/autocomplete.php?key=pk.681bc4409499b8e73a130cf7ee8293aa&q=${encodeURIComponent(query)}&format=json`)
        .then(response => response.json())
        .then(data => {
            const suggestions = data.map(place => 
                `<div class="suggestion" data-address="${place.display_name}" style="padding: 8px; cursor: pointer; border-bottom: 1px solid #eee;">${place.display_name}</div>`
            ).join('');

            const suggestionsDiv = document.getElementById('address-suggestions');
            suggestionsDiv.innerHTML = suggestions;
            suggestionsDiv.style.display = suggestions ? 'block' : 'none';

            // Add click event to suggestions
            document.querySelectorAll('.suggestion').forEach(suggestion => {
                suggestion.addEventListener('click', function() {
                    document.getElementById('address').value = this.getAttribute('data-address');
                    document.getElementById('address-suggestions').innerHTML = '';
                    document.getElementById('address-suggestions').style.display = 'none';
                });
            });
        })
        .catch(error => {
            console.error('Error fetching address:', error);
            document.getElementById('address-suggestions').style.display = 'none';
        });
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
