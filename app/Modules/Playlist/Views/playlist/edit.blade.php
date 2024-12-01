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
    <h2 class="text-lg font-medium mr-auto">Chỉnh sửa Playlist</h2>
</div>
<div class="grid grid-cols-12 gap-12 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <form method="post" action="{{ route('admin.playlist.update', $playlist->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT') <!-- Phương thức PUT cho hành động cập nhật -->

            <div class="intro-y box p-5">
                <!-- Tên Playlist -->
                <div>
                    <label for="title" class="form-label">Tên Playlist</label>
                    <input id="title" name="title" type="text" class="form-control" placeholder="Tên Playlist" 
                           value="{{ old('title', $playlist->title) }}" required>
                </div>

                <!-- Chọn Bài Hát -->
                <select name="song_id[]" id="song_id" class="form-select mt-2 sm:mr-2" multiple required>
    @foreach($songs as $song)
        <option value="{{ $song->id }}" {{ in_array($song->id, $selectedSongs) ? 'selected' : '' }}>
            {{ $song->title }}
        </option>
    @endforeach
</select>

<div class="mt-3">
    <label for="type" class="form-label">Loại Playlist</label>
    <select name="type" id="type" class="form-select mt-2 sm:mr-2" required>
        <option value="public" {{ (old('type', $playlist->type) == 'public') ? 'selected' : '' }}>Công khai</option>
        <option value="private" {{ (old('type', $playlist->type) == 'private') ? 'selected' : '' }}>Riêng tư</option>
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

                <!-- Nút Lưu -->
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
document.addEventListener('DOMContentLoaded', function () {
    // Khởi tạo Tom Select cho trường chọn bài hát
    new TomSelect("#song_id", {
        maxItems: null, // Cho phép chọn không giới hạn số lượng
        placeholder: "Chọn bài hát",
        create: false,  // Không cho phép tạo mục mới
        onChange: function(values) {
            // Cập nhật danh sách bài hát đã chọn
            updateSelectedSongs(values);
        }
    });

    // Hàm để cập nhật danh sách bài hát đã chọn
    function updateSelectedSongs(values) {
        const selectedTitles = values.map(function(id) {
            const option = document.querySelector(`#song_id option[value="${id}"]`);
            return option ? option.text : '';
        });
        document.getElementById('selected-songs').textContent = selectedTitles.join(', ');
    }

    // Gọi hàm lần đầu để hiển thị bài hát đã chọn nếu có
    const initialValues = Array.from(document.getElementById('song_id').selectedOptions).map(option => option.value);
    updateSelectedSongs(initialValues);
});
</script>
@endsection
