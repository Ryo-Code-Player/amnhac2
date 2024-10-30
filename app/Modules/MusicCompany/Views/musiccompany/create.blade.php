@extends('backend.layouts.master')

@section('scriptop')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="{{ asset('js/js/tom-select.complete.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('js/css/tom-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('path/to/dropzone.css') }}">
<script src="{{ asset('path/to/dropzone.js') }}"></script>
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
                <div>
                    <label for="title" class="form-label">Tên Công Ty</label>
                    <input id="title" name="title" type="text" class="form-control" placeholder="Tên Công Ty" value="{{ old('title') }}" required>
                </div>
                <div class="mt-3">
                    <label for="address" class="form-label">Địa chỉ</label>
                    <input id="address" name="address" type="text" class="form-control" placeholder="Địa chỉ" value="{{ old('address') }}">
                </div>
                <div class="mt-3">
                    <label for="photo" class="form-label">Ảnh đại diện</label>
                    <div class="px-4 pb-4 mt-5 flex items-center cursor-pointer relative">
                        <div data-single="true" id="mydropzone" class="dropzone" url="{{route('admin.upload.avatar')}}">
                            <div class="fallback">
                                <input name="file" type="file" />
                            </div>
                            <div class="dz-message" data-dz-message>
                                <div class="font-medium">Kéo thả hoặc chọn ảnh.</div>
                            </div>
                        </div>
                        <input type="hidden" id="photo" name="photo"/>
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

                <div class="form-group mt-3">
                    <label for="resources">Chọn Tài Nguyên:</label>
                    <select name="resources[]" id="resources" multiple>
                        @foreach($allResources as $resource)
                            <option value="{{ $resource->id }}">{{ $resource->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-3">
                    <label for="tags" class="form-label">Tags</label>
                    <input id="tags" name="tags" type="text" class="form-control" placeholder="Tags" value="{{ old('tags') }}">
                </div>
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
    var resourceSelect = new TomSelect('#resources', {
        create: true,
        maxItems: null,
        placeholder: 'Nhập tài nguyên và nhấn Enter',
    });
</script>

<script>
    Dropzone.autoDiscover = false;

    // Khởi tạo Dropzone với cấu hình cụ thể
    var myDropzone = new Dropzone("#mydropzone", {
        url: "{{ route('admin.upload.avatar') }}",
        method: "post",
        maxFilesize: 1,
        maxFiles: 1,
        dictDefaultMessage: 'Kéo và thả hình ảnh vào đây hoặc nhấp để chọn tệp',
        acceptedFiles: "image/jpeg,image/png,image/gif",
        previewTemplate: `
            <div class="d-flex flex-column position-relative">
                <img data-dz-thumbnail>
                <div class="tooltip w-5 h-5 flex items-center justify-center absolute rounded-full text-white bg-danger right-0 top-0 -mr-2 -mt-2">
                    <i data-dz-remove class="w-4 h-4">✖</i>
                </div>
            </div>
        `,
        addRemoveLinks: true,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    });

    myDropzone.on("addedfile", function(file) {
        console.log('Tệp đã được thêm thành công!');
    });

    myDropzone.on("success", function(file, response) {
        if (response.status == "true") {
            $('#photo').val(response.link); // Cập nhật đường dẫn tệp vào ô ẩn
            console.log('Tệp tải lên thành công: ' + response.link);
        } else {
            console.log('Tải lên không thành công!');
        }
    });

    myDropzone.on("removedfile", function(file) {
        $('#photo').val(''); // Xóa giá trị khi tệp bị xóa
        console.log('Tệp đã được xóa thành công!');
    });

    myDropzone.on("error", function(file, message) {
        console.error('Lỗi: ' + message); // Hiển thị lỗi
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
@endsection
