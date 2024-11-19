@extends('backend.layouts.master')
@section ('scriptop')

<meta name="csrf-token" content="{{ csrf_token() }}">
 
@endsection
@section('content')

<div class = 'content'>
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Điều chỉnh câu lạc bộ
        </h2>
    </div>
    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Form Layout -->
            <form method="post" action="{{route('admin.Fanclub.update',$fanclub->id)}}" enctype="multipart/form-data">
                @csrf
                @method('patch')
                <div class="intro-y box p-5">
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Tên câu lạc bộ</label>
                        <input id="title" name="title" type="text" class="form-control" placeholder="Tên câu lạc bộ" value="{{ old('title', $fanclub->title) }}">
                 
                    </div>

                    <div class="mt-3">
                        <label for="" class="form-label">Ảnh đại diện</label>
                        <div class="px-4 pb-4 mt-5 flex items-center cursor-pointer relative">
                            <div data-single="true" id="mydropzone" class="dropzone" url="{{ route('admin.upload.avatar') }}">
                                <div class="fallback"><input name="photos" type="file" multiple /></div>
                                <div class="dz-message" data-dz-message>
                                    <div class="font-medium">Kéo thả hoặc chọn ảnh.</div>
                                </div>
                            </div>
                            <input type="hidden" id="photo" name="photo" value="{{ $fanclub->photo }}"/>
                        </div>
                    </div>

                    <label for="regular-form-1" class="form-label">Mô tả</label>
                    <input id="summary" name="summary" type="text" class="form-control" placeholder="Mô tả" value="{{ old('summary', $fanclub->summary)}}">
                        
                        <label for="" class="form-label">Nội dung</label>
                       
                        <textarea class="editor"  id="editor1" name="content" >
                            {{old('content', $fanclub->content)}}
                        </textarea>
                    </div>

                    <div class="mt-3">
                        <div class="flex flex-col sm:flex-row items-center">
                            <label style="min-width:70px" class="form-select-label" for="status">Trạng thái</label>
                            <select name="status" class="form-select mt-2 sm:mr-2">
                                <option value="inactive" {{ old('status', $fanclub->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="active" {{ old('status', $fanclub->status) == 'active' ? 'selected' : '' }}>Active</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>    {{$error}} </li>
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
</div>
@endsection

@section ('scripts')
<script>
    Dropzone.instances[0].options.multiple = true;  // Cho phép chọn nhiều file
Dropzone.instances[0].options.autoQueue = true; // Tự động gửi ảnh khi tải xong
Dropzone.instances[0].options.maxFilesize = 2; // MB
Dropzone.instances[0].options.maxFiles = 5; // Tối đa 5 file
Dropzone.instances[0].options.dictDefaultMessage = 'Drop images anywhere to upload (Multiple images allowed)';
Dropzone.instances[0].options.acceptedFiles = "image/jpeg,image/png,image/gif";
Dropzone.instances[0].options.addRemoveLinks = true;
Dropzone.instances[0].options.headers = {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')};

// Cập nhật mảng ảnh khi ảnh được tải lên thành công
Dropzone.instances[0].on("success", function (file, response) {
    if (response.status == "true") {
        let currentPhotos = $('#photo').val() ? JSON.parse($('#photo').val()) : [];  // Nếu có ảnh thì lấy, không thì tạo mới mảng
        currentPhotos.push(response.link);  // Thêm đường dẫn ảnh vào mảng
        $('#photo').val(JSON.stringify(currentPhotos));  // Cập nhật lại giá trị của trường ẩn 'photo'
    }
});

// Khi ảnh bị xóa khỏi Dropzone, cập nhật lại giá trị của trường 'photo'
Dropzone.instances[0].on("removedfile", function (file) {
    let currentPhotos = $('#photo').val() ? JSON.parse($('#photo').val()) : [];
    currentPhotos = currentPhotos.filter(photo => photo !== file.serverFileName);  // Loại bỏ ảnh đã xóa khỏi mảng
    $('#photo').val(JSON.stringify(currentPhotos));  // Cập nhật lại giá trị của trường ẩn 'photo'
});

// Trước khi form được submit, đảm bảo trường 'photo' có dữ liệu
$('form').on('submit', function (e) {
    // Kiểm tra xem Dropzone còn file nào chưa xử lý
    if (Dropzone.instances[0].getQueuedFiles().length > 0) {
        // Nếu có file đang đợi, thì chờ Dropzone xử lý xong
        e.preventDefault();  // Ngừng gửi form ngay lập tức
        Dropzone.instances[0].processQueue();  // Xử lý các file chưa được tải lên
        // Sau khi tất cả file đã tải lên, gửi form lại
        Dropzone.instances[0].on("queuecomplete", function () {
            $('form')[0].submit();  // Gửi form khi đã hoàn thành
        });
    }
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


<script src="{{asset('js/js/ckeditor.js')}}"></script>

@endsection