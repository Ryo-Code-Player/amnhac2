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
    <h2 class="text-lg font-medium mr-auto">Thêm âm nhạc vào chủ đề</h2>
</div>
<div class="grid grid-cols-12 gap-12 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <form method="post" action="{{ route('admin.topic_music.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="intro-y box p-5">
                <div>
                    <label for="title" class="form-label">Tên chủ đề</label>
                    <input id="title" name="title" type="text" class="form-control" placeholder="Tên chủ đề" value="{{ old('title') }}" required>
                </div>
               
                <div class="mt-3">
                    <label for="status" class="form-label">Tình trạng</label>
                    <select name="status" class="form-select mt-2 sm:mr-2" required>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
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
    // Khởi tạo Tom Select cho tags nếu cần
    // var tagSelect = new TomSelect('#select-junk', {
    //     create: true,
    //     maxItems: null,
    //     placeholder: 'Chọn tags',
    // });
</script>
@endsection
