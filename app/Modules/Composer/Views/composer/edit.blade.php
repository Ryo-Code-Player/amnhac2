@extends('backend.layouts.master')

@section('scriptop')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('css/tom-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/dropzone.css') }}">
<script src="{{ asset('js/tom-select.complete.min.js') }}"></script>
<script src="{{ asset('js/dropzone.js') }}"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
@endsection

@section('content')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Chỉnh Sửa Ca Sĩ</h2>
</div>
<div class="grid grid-cols-12 gap-12 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <form method="post" action="{{ route('admin.composer.update', $composer->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div class="intro-y box p-5">
                <div class="mt-3">
                    <label for="fullname" class="form-label">Tên Ca Sĩ</label>
                    <input id="fullname" name="fullname" type="text" class="form-control" placeholder="Tên Ca Sĩ" value="{{ old('fullname', $composer->fullname) }}" required>
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
                        <input type="hidden" id="photo" name="photo" value="{{ old('photo', $composer->photo) }}"/>
                    </div>
                </div>

                <div class="mt-3">
                    <label for="summary" class="form-label">Mô tả ngắn</label>
                    <textarea class="form-control" id="editor1" name="summary">{{ old('summary', $composer->summary) }}</textarea>
                </div>

                <div class="mt-3">
                    <label for="content" class="form-label">Nội dung</label>
                    <textarea class="editor" name="content" id="editor2">{{ old('content', $composer->content) }}</textarea>
                </div>

                <div class="mt-3">
                    <label for="status" class="form-label">Tình trạng</label>
                    <select name="status" class="form-select mt-2" required>
                        <option value="active" {{ $composer->status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $composer->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
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
                    <button type="submit" class="btn btn-primary w-24">Cập nhật</button>
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

<script src="{{ asset('js/ckeditor.js') }}"></script>
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
