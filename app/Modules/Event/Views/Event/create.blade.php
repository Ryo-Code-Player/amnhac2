@extends('backend.layouts.master')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">Tạo sự kiện mới</h2>

    <form action="{{ route('admin.Event.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="title">Tiêu đề</label>
            <input type="text" name="title" class="form-control" required>
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

        <div class="form-group">
            <label for="summary">Mô tả</label>
            <textarea name="summary" class="form-control"></textarea>
        </div>

            
        <label for="" class="form-label">Nội dung</label>
            
        <textarea class="editor"  id="editor1" name="description" >
            {{old('description')}}
        </textarea>

        <div class="form-group">
            <label for="diadiem">Địa điểm</label>
            <input type="text" name="diadiem" class="form-control" required>
        </div>

        {{-- <div class="mt-4">
            <label for="event_type_id" class="form-label">Chọn loại sự kiện</label>
            <select name="event_type_id" id="event_type_id" class="form-select mt-2 sm:mr-2" required>
                @foreach($eventtype as $type)
                    <option value="{{ $type->id }}">{{ $type->title }}</option>
                @endforeach
            </select>
            @if ($errors->has('event_type_id'))
                <div class="text-danger mt-2">{{ $errors->first('event_type_id') }}</div>
            @endif
        </div> --}}

        <div class="form-group">
            <label for="timestart">Thời gian bắt đầu</label>
            <input type="datetime-local" name="timestart" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="timeend">Thời gian kết thúc</label>
            <input type="datetime-local" name="timeend" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Lưu sự kiện</button>
    </form>

    <script src="{{asset('js/js/ckeditor.js')}}"></script>

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
     
        // CKSource.Editor
        ClassicEditor.create( document.querySelector( '#editor1' ), 
        {
            ckfinder: {
                uploadUrl: '{{route("admin.upload.ckeditor")."?_token=".csrf_token()}}'
                },
                mediaEmbed: {previewsInData: true}
            

        })

        .then( editor => {
            console.log( editor );
        })
        .catch( error => {
            console.error( error );
        })

</script>
@endsection
