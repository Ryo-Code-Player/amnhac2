@extends('backend.layouts.master')

@section('scriptop')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/js/tom-select.complete.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('/js/css/tom-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> <!-- Thêm CSS cho ứng dụng -->
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
                    <div>
                        <label for="title" class="form-label">Tên công ty</label>
                        <input id="title" name="title" type="text" value="{{ old('title', $musicCompany->title) }}" class="form-control" placeholder="Tên công ty" required>
                    </div>

                    <div class="mt-3">
                        <label for="address" class="form-label">Địa chỉ</label>
                        <input id="address" name="address" type="text" value="{{ old('address', $musicCompany->address) }}" class="form-control" placeholder="Địa chỉ công ty" required>
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
                        <div class="grid grid-cols-10 gap-5 pl-4 pr-5 py-5">
                            @foreach (explode(',', $musicCompany->photo) as $photo)
                                <div data-photo="{{ $photo }}" class="product_photo col-span-5 md:col-span-2 h-28 relative image-fit cursor-pointer zoom-in">
                                    <img class="rounded-md" src="{{ $photo }}">
                                    <div title="Xóa hình này?" class="tooltip w-5 h-5 flex items-center justify-center absolute rounded-full text-white bg-danger right-0 top-0 -mr-2 -mt-2 btn_remove">
                                        <i data-lucide="x" class="w-4 h-4"></i>
                                    </div>
                                </div>
                            @endforeach
                            <input type="hidden" id="photo" name="photo" value="{{ old('photo', $musicCompany->photo) }}"/>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label for="summary" class="form-label">Mô tả ngắn</label>
                        <textarea class="form-control" name="summary" id="editor1" required>{{ old('summary', $musicCompany->summary) }}</textarea>
                    </div>

                    <div class="mt-3">
                        <label for="content" class="form-label">Nội dung chi tiết</label>
                        <textarea class="editor" name="content" id="editor2" required>{{ old('content', $musicCompany->content) }}</textarea>
                    </div>

                    <!-- Mẫu cho việc chọn tài nguyên -->
                    <div class="form-group mt-3">
                        <label for="resources">Chọn Tài Nguyên:</label>
                        <select name="resources[]" id="resources" multiple>
                            @foreach($allResources as $resource)
                                <option value="{{ $resource->id }}" {{ in_array($resource->id, old('resources', explode(',', $musicCompany->resources))) ? 'selected' : '' }}>
                                    {{ $resource->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mt-3">
                        <div class="flex flex-col sm:flex-row items-center">
                            <label class="form-select-label" for="status">Tình trạng</label>
                            <select name="status" class="form-select mt-2 sm:mr-2">
                                <option value="active" {{ $musicCompany->status == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="inactive" {{ $musicCompany->status == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                            </select>
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
                        <button type="submit" class="btn btn-primary w-24">Cập nhật</button>
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
    $(".btn_remove").click(function(){
        $(this).parent().parent().remove();   
        var link_photo = "";
        $('.product_photo').each(function() {
            if (link_photo != '')
            {
            link_photo+= ',';
            }   
            link_photo += $(this).data("photo");
        });
        $('#photo_old').val(link_photo);
    });

 
                // previewsContainer: ".dropzone-previews",
    Dropzone.instances[0].options.multiple = true;
    Dropzone.instances[0].options.autoQueue= true;
    Dropzone.instances[0].options.maxFilesize =  1; // MB
    Dropzone.instances[0].options.maxFiles =5;
    Dropzone.instances[0].options.acceptedFiles= "image/jpeg,image/png,image/gif";
    Dropzone.instances[0].options.previewTemplate =  '<div class="col-span-5 md:col-span-2 h-28 relative image-fit cursor-pointer zoom-in">'
                                               +' <img    data-dz-thumbnail >'
                                               +' <div title="Xóa hình này?" class="tooltip w-5 h-5 flex items-center justify-center absolute rounded-full text-white bg-danger right-0 top-0 -mr-2 -mt-2"> <i data-lucide="octagon"   data-dz-remove> x </i> </div>'
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
        {
            var value_link = $('#photo').val();
            if(value_link != "")
            {
                value_link += ",";
            }
            value_link += response.link;
            $('#photo').val(value_link);
        }
           
        // console.log('File success successfully!' +$('#photo').val());
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
 
    // console.log(Dropzone.optionsForElement);
 
</script>
 
<script src="{{asset('js/js/ckeditor.js')}}"></script>
<script>
     
        // CKSource.Editor
        ClassicEditor.create( document.querySelector( '#editor2' ), 
        {
            ckfinder: {
                uploadUrl: '{{route("admin.upload.ckeditor")."?_token=".csrf_token()}}'
                }
                ,
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
