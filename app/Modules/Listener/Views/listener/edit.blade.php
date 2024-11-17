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
    <h2 class="text-lg font-medium mr-auto">Chỉnh sửa Listener</h2>
</div>
<div class="grid grid-cols-12 gap-12 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <form method="post" action="{{ route('admin.listener.update', $listener->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="intro-y box p-5">
                <!-- Favorite Type -->
                <div class="form-group">
                    <label for="favorite_type" class="form-label">Loại Yêu Thích</label>
                    <select name="favorite_type" id="favorite_type" class="form-control" required>
                        <option value="">Chọn loại yêu thích</option>
                        <option value="song" {{ $listener->favorite_type == 'song' ? 'selected' : '' }}>Bài hát</option>
                        <option value="singer" {{ $listener->favorite_type == 'singer' ? 'selected' : '' }}>Ca sĩ</option>
                        <option value="composer" {{ $listener->favorite_type == 'composer' ? 'selected' : '' }}>Nhà soạn nhạc</option>
                    </select>
                </div>

                <!-- Favorite Song -->
                <div class="form-group">
                    <label for="favorite_song" class="form-label">Bài Hát Yêu Thích</label>
                    <select name="favorite_song" id="favorite_song" class="form-control">
                        <option value="">Chọn bài hát yêu thích</option>
                        @foreach($songs as $song)
                            <option value="{{ $song->id }}" {{ $listener->favorite_song == $song->id ? 'selected' : '' }}>{{ $song->title }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Favorite Singer -->
                <div class="form-group">
                    <label for="favorite_singer" class="form-label">Ca Sĩ Yêu Thích</label>
                    <select name="favorite_singer" id="favorite_singer" class="form-control">
                        <option value="">Chọn ca sĩ yêu thích</option>
                        @foreach($singers as $singer)
                            <option value="{{ $singer->id }}" {{ $listener->favorite_singer == $singer->id ? 'selected' : '' }}>{{ $singer->alias }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Favorite Composer -->
                <div class="form-group">
                    <label for="favorite_composer" class="form-label">Nhà Soạn Nhạc Yêu Thích</label>
                    <select name="favorite_composer" id="favorite_composer" class="form-control">
                        <option value="">Chọn nhà soạn nhạc yêu thích</option>
                        @foreach($composers as $composer)
                            <option value="{{ $composer->id }}" {{ $listener->favorite_composer == $composer->id ? 'selected' : '' }}>{{ $composer->fullname }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Status -->
                <div class="mt-3">
                    <div class="flex flex-col sm:flex-row items-center">
                        <label style="min-width:70px" class="form-select-label" for="status">Tình trạng</label>
                        <select name="status" class="form-select mt-2 sm:mr-2" required>
                            <option value="active" {{ $listener->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $listener->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                <!-- Error handling -->
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
