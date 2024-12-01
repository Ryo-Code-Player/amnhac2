@extends('backend.layouts.master')

@section('scriptop')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/js/tom-select.complete.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('/js/css/tom-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
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
                    <!-- Các trường thông tin công ty -->
                    <div>
                        <label for="title" class="form-label">Tên công ty</label>
                        <input id="title" name="title" type="text" value="{{ old('title', $musicCompany->title) }}" class="form-control" placeholder="Tên công ty" required>
                    </div>

  <!-- Input Address Field -->
  <div class="mt-3">
        <label for="address" class="form-label">Địa chỉ</label>
        <input id="address" name="address" type="text" class="form-control" placeholder="Nhập địa chỉ" 
               value="{{ old('address', $musicCompany->address ?? '') }}" required>
    </div>
    <div id="address-suggestions" class="mt-2" style="border: 1px solid #ccc; display: none;"></div>

                    <!-- Phần quản lý ảnh -->
                    <div class="mt-3">
                        <label for="photo" class="form-label">Ảnh</label>
                        <div class="px-4 pb-4 mt-5 flex items-center cursor-pointer relative">
                            <div data-single="true" id="mydropzone" class="dropzone" url="{{ route('admin.upload.avatar') }}">
                                <div class="fallback"> <input name="file" type="file" /> </div>
                                <div class="dz-message" data-dz-message>
                                    <div class="font-medium">Kéo thả hoặc chọn ảnh.</div>
                                </div>
                            </div>
                            <input type="hidden" id="photo" name="photo"/>
                        </div>
                    </div>

                    <!-- Phần hiển thị ảnh đã gắn -->
                    <div class="grid grid-cols-10 gap-5 pl-4 pr-5 py-5">
                        @foreach (explode(',', $musicCompany->photo) as $photo)
                            <div data-photo="{{ $photo }}" class="product_photo col-span-5 md:col-span-2 h-28 relative image-fit cursor-pointer zoom-in">
                                <img class="rounded-md" src="{{ $photo }}">
                                <div title="Xóa hình này?" class="tooltip w-5 h-5 flex items-center justify-center absolute rounded-full text-white bg-danger right-0 top-0 -mr-2 -mt-2 btn_remove">
                                    <i data-lucide="x" class="w-4 h-4"></i>
                                </div>
                            </div>
                        @endforeach
                        <input type="hidden" id="photo_old" name="photo" value="{{ old('photo', $musicCompany->photo) }}"/>
                    </div>

                    <!-- Phần mô tả và nội dung -->
                 
                    <div class="mt-3">
    <label for="summary" class="form-label">Mô tả ngắn</label>
    <textarea class="form-control" id="editor1" name="summary">{{ old('summary', $musicCompany->summary) }}</textarea>
</div>

<div class="mt-3">
    <label for="content" class="form-label">Nội dung chi tiết</label>
    <textarea class="editor" name="content" id="editor2" required>{{ old('content', $musicCompany->content) }}</textarea>
</div>


          <!-- Phần tài nguyên (file nhạc, MV, v.v.) -->
<div class="form-group mt-3">
    <label for="resources">Tài nguyên hiện có:</label>
    <ul>
        @foreach($resources as $resource)
            <li id="resource-{{ $resource->resource_id }}">
                <!-- Hiển thị tên tài nguyên -->
                <a href="{{ $resource->url }}" target="_blank">{{ $resource->file_name }}</a>

                <!-- Nút xóa tài nguyên -->
                <button class="delete-resource btn btn-danger btn-sm" data-id="{{ $resource->id }}" data-company-id="{{ $musicCompany->id }}">Xóa</button>
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
                            <option value="active" {{ $musicCompany->status == 'active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="inactive" {{ $musicCompany->status == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
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

@section('scripts');
<script>
    // Xử lý nút xóa hình ảnh cũ
    $(".btn_remove").click(function(){
        $(this).parent().parent().remove();   
        var link_photo = "";
        $('.product_photo').each(function() {
            if (link_photo != '') {
                link_photo += ',';
            }   
            link_photo += $(this).data("photo");
        });
        $('#photo_old').val(link_photo);
    });

    // Xử lý Dropzone
    Dropzone.instances[0].options.multiple = true;
    Dropzone.instances[0].options.autoQueue = true;
    Dropzone.instances[0].options.maxFilesize = 1; // MB
    Dropzone.instances[0].options.maxFiles = 5;
    Dropzone.instances[0].options.acceptedFiles = "image/jpeg,image/png,image/gif";
    Dropzone.instances[0].options.previewTemplate = '<div class="col-span-5 md:col-span-2 h-28 relative image-fit cursor-pointer zoom-in">'
        + '<img data-dz-thumbnail >'
        + '<div title="Xóa hình này?" class="tooltip w-5 h-5 flex items-center justify-center absolute rounded-full text-white bg-danger right-0 top-0 -mr-2 -mt-2"> <i data-lucide="octagon" data-dz-remove> x </i> </div>'
        + '</div>';
    Dropzone.instances[0].options.addRemoveLinks = true;
    Dropzone.instances[0].options.headers = {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')};

    Dropzone.instances[0].on("addedfile", function(file) {
        console.log('File added successfully!');
    });

    Dropzone.instances[0].on("success", function(file, response) {
        if(response.status == "true") {
            var value_link = $('#photo').val();
            if(value_link != "") {
                value_link += ",";
            }
            value_link += response.link;
            $('#photo').val(value_link);
        }
    });

    Dropzone.instances[0].on("removedfile", function(file) {
        $('#photo').val('');
        console.log('File removed successfully!');
    });

    Dropzone.instances[0].on("error", function(file, message) {
        console.log('error: ' + message);
    });
</script>

<script src="{{asset('js/js/ckeditor.js')}}"></script>

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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Bắt sự kiện khi nhấn nút xóa tài nguyên
        $('.delete-resource').click(function(e) {
            e.preventDefault();

            var resourceId = $(this).data('id');
            var companyId = $(this).data('company-id');
            
            // Xác nhận xóa tài nguyên
            if (confirm('Bạn có chắc chắn muốn xóa tài nguyên này?')) {
                // Gửi yêu cầu Ajax để xóa tài nguyên
                $.ajax({
                    url: '{{ route('admin.musiccompany.resource.delete') }}',  // Đảm bảo route đúng
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}', // Thêm csrf token
                        resource_id: resourceId,
                        company_id: companyId
                    },
                    success: function(response) {
                        // Kiểm tra phản hồi từ server
                        if (response.success) {
                            // Ẩn hoặc xóa tài nguyên khỏi giao diện
                            $('#resource-' + resourceId).remove(); // Xóa phần tử khỏi giao diện

                            alert(response.message); // Hiển thị thông báo thành công
                            location.reload(); // Tải lại trang (hoặc hiển thị lại tài nguyên nếu cần)
                        } else {
                            alert('Lỗi: ' + response.message); // Hiển thị thông báo lỗi
                        }
                    },
                    error: function(xhr, status, error) {
                        // Xử lý lỗi khi gửi yêu cầu Ajax
                        console.log('Error:', error);
                        alert('Đã có lỗi xảy ra. Vui lòng thử lại.');
                    }
                });
            }
        });
    });
</script>


@endsection
