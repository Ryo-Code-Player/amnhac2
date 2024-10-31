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
                        <label for="" class="form-label">Photo</label>
                        <div class="px-4 pb-4 mt-5 flex items-center  cursor-pointer relative">
                            <div data-single="true" id="mydropzone" class="dropzone  "    url="{{route('admin.upload.avatar')}}" >
                                <div class="fallback"> <input name="file" type="file" /> </div>
                                <div class="dz-message" data-dz-message>
                                    <div class=" font-medium">Kéo thả hoặc chọn ảnh.</div>
                                        
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
@endsection
