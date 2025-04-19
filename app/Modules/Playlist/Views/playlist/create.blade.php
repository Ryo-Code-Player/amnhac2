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
    <h2 class="text-lg font-medium mr-auto">Thêm Playlist</h2>
</div>
<div class="grid grid-cols-12 gap-12 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <form method="post" action="{{ route('admin.playlist.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="intro-y box p-5">
                <!-- Tên Playlist -->
                <div>
                    <label for="title" class="form-label">Tên Playlist</label>
                    <input id="title" name="title" type="text" class="form-control" placeholder="Tên Playlist" value="{{ old('title') }}" required>
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


                <div>
    <label for="song_id" class="form-label">Chọn Bài Hát</label>
    <select name="song_id[]" id="song_id" class="form-select mt-2 sm:mr-2" multiple required>
        @foreach($songs as $song)
            <option value="{{ $song->id }}" {{ in_array($song->id, old('song_id', [])) ? 'selected' : '' }}>
                {{ $song->title }}
            </option>
        @endforeach
    </select>
    <div id="selected-songs" class="mt-2"></div> <!-- Để hiển thị danh sách bài hát đã chọn -->
</div>


          
                <!-- Type -->
                <div class="mt-3">
                    <label for="type" class="form-label">Loại Playlist</label>
                    <select name="type" id="type" class="form-select mt-2 sm:mr-2" required>
                        <option value="public" {{ old('type') == 'public' ? 'selected' : '' }}>Công khai</option>
                        <option value="private" {{ old('type') == 'private' ? 'selected' : '' }}>Riêng tư</option>
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
                    <button type="submit" class="btn btn-primary w-24">Lưu</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    Dropzone.autoDiscover = false;
    
    // Dropzone class:
  
        Dropzone.instances[0].options.multiple = true;
        Dropzone.instances[0].options.autoQueue= true;
        Dropzone.instances[0].options.maxFilesize =  5; // MB
        Dropzone.instances[0].options.maxFiles =5;
        Dropzone.instances[0].options.dictDefaultMessage = 'Drop images anywhere to upload (6 images Max)';
        Dropzone.instances[0].options.acceptedFiles= "image/jpeg,image/png,image/gif";
        Dropzone.instances[0].options.previewTemplate =  '<div class=" d-flex flex-column  position-relative">'
                                        +' <img    data-dz-thumbnail >'
                                        
                                    +' </div>';
        // Dropzone.instances[0].options.previewTemplate =  '<li><figure><img data-dz-thumbnail /><i title="Remove Image" class="icon-trash" data-dz-remove ></i></figure></li>';      
        Dropzone.instances[0].options.addRemoveLinks =  true;
        Dropzone.instances[0].options.headers= {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')};

        Dropzone.instances[0].on("addedfile", function (file ) {
        // Example: Handle success event
        console.log('File addedfile successfully!' );
        });
        Dropzone.instances[0].on("success", function (file, response) {
        // Example: Handle success event
        // file.previewElement.innerHTML = "";
        if(response.status == "true")
        $('#photo').val(response.link);
        console.log('File success successfully!' +response.link);
        });
        Dropzone.instances[0].on("removedfile", function (file ) {
        $('#photo').val('');
        console.log('File removed successfully!'  );
        });
        Dropzone.instances[0].on("error", function (file, message) {
        // Example: Handle success event
        file.previewElement.innerHTML = "";
        console.log(file);

        console.log('error !' +message);
        });
        console.log(Dropzone.instances[0].options   );
</script>


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
