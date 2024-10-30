@extends('backend.layouts.master')

@section('scriptop')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/js/tom-select.complete.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('/js/css/tom-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> <!-- Thêm CSS cho ứng dụng -->
@endsection

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">Điều chỉnh công ty âm nhạc</h2>
    </div>
    
    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <form method="post" action="{{ route('admin.musiccompany.update', $musicCompany->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <div class="intro-y box p-5">
                    <div>
                        <label for="title" class="form-label">Tên công ty</label>
                        <input id="title" name="title" type="text" value="{{ old('title', $musicCompany->title) }}" class="form-control" placeholder="Tên công ty" required>
                    </div>

                    <div class="mt-3">
                        <label for="address" class="form-label">Địa chỉ</label>
                        <input id="address" name="address" type="text" value="{{ old('address', $musicCompany->address) }}" class="form-control" placeholder="Địa chỉ công ty" required>
                    </div>

                    <div class="mt-3">
                        <label for="mydropzone" class="form-label">Hình ảnh</label>
                        <div class="px-4 pb-4 mt-5 flex items-center cursor-pointer relative">
                            <div data-single="true" id="mydropzone" class="dropzone" url="{{ route('admin.upload.avatar') }}">
                                <div class="fallback">
                                    <input name="file" type="file" />
                                </div>
                                <div class="dz-message" data-dz-message>
                                    <div class="font-medium">Kéo thả hoặc chọn hình ảnh.</div>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-10 gap-5 pl-4 pr-5 py-5">
                            @foreach (explode(',', $musicCompany->photo) as $photo)
                                <div data-photo="{{ $photo }}" class="product_photo col-span-5 md:col-span-2 h-28 relative image-fit cursor-pointer zoom-in">
                                    <img class="rounded-md" src="{{ $photo }}">
                                    <div title="Xóa hình này?" class="tooltip w-5 h-5 flex items-center justify-center absolute rounded-full text-white bg-danger right-0 top-0 -mr-2 -mt-2 btn_remove">
                                        <i data-lucide="x" class="w-4 h-4"></i>
                                    </div>
                                </div>
                            @endforeach
                            <input type="hidden" id="photo" name="photo" value="{{ old('photo', $musicCompany->photo) }}"/>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label for="summary" class="form-label">Mô tả ngắn</label>
                        <textarea class="form-control" name="summary" id="editor1" required>{{ old('summary', $musicCompany->summary) }}</textarea>
                    </div>

                    <div class="mt-3">
                        <label for="content" class="form-label">Nội dung chi tiết</label>
                        <textarea class="editor" name="content" id="editor2" required>{{ old('content', $musicCompany->content) }}</textarea>
                    </div>

                    <!-- Mẫu cho việc chọn tài nguyên -->
                    <div class="form-group mt-3">
                        <label for="resources">Chọn Tài Nguyên:</label>
                        <select name="resources[]" id="resources" multiple>
                            @foreach($allResources as $resource)
                                <option value="{{ $resource->id }}" {{ in_array($resource->id, old('resources', explode(',', $musicCompany->resources))) ? 'selected' : '' }}>
                                    {{ $resource->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mt-3">
                        <div class="flex flex-col sm:flex-row items-center">
                            <label class="form-select-label" for="status">Tình trạng</label>
                            <select name="status" class="form-select mt-2 sm:mr-2">
                                <option value="active" {{ $musicCompany->status == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="inactive" {{ $musicCompany->status == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
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
                        <button type="submit" class="btn btn-primary w-24">Cập nhật</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Khởi tạo TomSelect cho dropdown
    var select = new TomSelect('#resources', {
        maxItems: null,
        allowEmptyOption: true,
        plugins: ['remove_button'],
        sortField: {
            field: "text",
            direction: "asc"
        },
        create: true
    });

    // Xử lý sự kiện khi nhấn nút xóa
    $(".btn_remove").click(function() {
        var photo = $(this).parent().data("photo"); // Lấy đường dẫn ảnh
        $(this).closest('.product_photo').remove();
        
        // Cập nhật giá trị của photo
        var current_photos = $('#photo').val().split(','); // Lấy danh sách hiện tại
        current_photos = current_photos.filter(function(item) {
            return item !== photo; // Lọc bỏ ảnh đã xóa
        });
        
        $('#photo').val(current_photos.join(',')); // Cập nhật giá trị của photo
    });

    // Cấu hình Dropzone
    Dropzone.autoDiscover = false; // Tắt autoDiscover để tự quản lý Dropzone
    var myDropzone = new Dropzone("#mydropzone", { // Đảm bảo ID đúng
        url: "{{ route('admin.upload.avatar') }}", // Đường dẫn tải lên
        method: "post",
        maxFilesize: 1, // MB
        maxFiles: 5, // Giới hạn số tệp
        acceptedFiles: "image/jpeg,image/png,image/gif",
        previewTemplate: `
            <div class="col-span-5 md:col-span-2 h-28 relative image-fit cursor-pointer zoom-in">
                <img data-dz-thumbnail>
                <div title="Xóa hình này?" class="tooltip w-5 h-5 flex items-center justify-center absolute rounded-full text-white bg-danger right-0 top-0 -mr-2 -mt-2">
                    <i data-lucide="octagon" data-dz-remove> x </i>
                </div>
            </div>
        `,
        addRemoveLinks: true,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
    });

    // Các sự kiện của Dropzone
    myDropzone.on("addedfile", function(file) {
        console.log('File added successfully!');
    });

    myDropzone.on("success", function(file, response) {
        if(response.status == "true") {
            var value_link = $('#photo').val();
            if(value_link != "") {
                value_link += ",";
            }
            value_link += response.link; // Thêm đường dẫn tệp vào ô ẩn
            $('#photo').val(value_link);
        }
    });

    myDropzone.on("removedfile", function(file) {
        var current_photos = $('#photo').val().split(',');
        current_photos = current_photos.filter(function(item) {
            return item !== file.name; // Chỉ xóa ảnh cụ thể
        });
        $('#photo').val(current_photos.join(',')); // Cập nhật giá trị ô ẩn
        console.log('File removed successfully!');
    });

    myDropzone.on("error", function(file, message) {
        console.log(file);
        console.log('error: ' + message);
    });

    // Khởi tạo CKEditor cho editor2
    ClassicEditor.create(document.querySelector('#editor2'), {
        ckfinder: {
            uploadUrl: '{{ route("admin.upload.ckeditor") . "?_token=" . csrf_token() }}',
        }
    }).catch(error => {
        console.error(error);
    });

    // Khởi tạo CKEditor cho editor1
    ClassicEditor.create(document.querySelector('#editor1'), {
        ckfinder: {
            uploadUrl: '{{ route("admin.upload.ckeditor") . "?_token=" . csrf_token() }}',
        }
    }).catch(error => {
        console.error(error);
    });
</script>

@endsection
