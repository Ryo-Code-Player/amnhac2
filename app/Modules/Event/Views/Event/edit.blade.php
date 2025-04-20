    @extends('backend.layouts.master')
    @section('content')

    <meta name="csrf-token" content="{{ csrf_token() }}">
        @include('backend.layouts.notification')
        <h2 class="intro-y text-lg font-medium mt-10">
            Chỉnh sửa sự kiện: {{ $event->title }}
        </h2>

        <div class="grid grid-cols-12 gap-12 mt-5">
            <div class="intro-y col-span-12 lg:col-span-8">
                <div class="box p-5">
                    <form action="{{ route('admin.Event.update', $event->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <!-- Tiêu đề -->
                        <div class="mb-4">
                            <label for="title" class="form-label">Tiêu đề sự kiện</label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $event->title) }}" required>
                            @error('title')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mt-3">
                            <label for="" class="form-label">Ảnh</label>
                            <div class="px-4 pb-4 mt-5 flex items-center  cursor-pointer relative">
                                <div data-single="true" id="mydropzone" class="dropzone  "    url="{{route('admin.upload.avatar')}}" >
                                    <div class="fallback"> <input name="file" type="file" /> </div> 
                                    <div class="dz-message" data-dz-message>
                                        <div class=" font-medium">Kéo thả hoặc chọn ảnh.</div>
                                            
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-10 gap-5 pl-4 pr-5 py-5">
                                    <?php
                                        $photos = explode( ',', $event->photo);
                                    ?>
                                    @foreach ( $photos as $photo)
                                    <div data-photo="{{$photo}}" class="product_photo col-span-5 md:col-span-2 h-28 relative image-fit cursor-pointer zoom-in">
                                        <img class="rounded-md "   src="{{$photo}}">
                                        <div title="Xóa hình này?" class="tooltip w-5 h-5 flex items-center justify-center absolute rounded-full text-white bg-danger right-0 top-0 -mr-2 -mt-2"> <i data-lucide="x" class="btn_remove w-4 h-4"></i> </div>  
                                    </div>
                                    @endforeach  
                                
                                    <input type="hidden" id="photo_old" name="photo_old"/>
                                    
                            </div>
                            <input type="hidden" id="photo" name="photo"/>
                        </div>

                        <!-- Mô tả -->
                        <div class="mb-4">
                            <label for="summary" class="form-label">Mô tả</label>
                            <textarea name="summary" id="summary" class="form-control" rows="4">{{ old('summary', $event->summary) }}</textarea>
                            @error('summary')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nội dung -->
                        <div class="mb-4">
                            <label for="" class="form-label">Nội dung</label>
                        
                        <textarea class="editor"  id="editor1" name="description" >
                            {{old('description', $event->description)}}
                        </textarea>
                        </div>

                        <!-- địa điểm -->
                        <div class="mb-4">
                            <label for="diadiem" class="form-label">Mô tả</label>
                            <textarea name="diadiem" id="diadiem" class="form-control" rows="4">{{ old('diadiem', $event->diadiem) }}</textarea>
                            @error('diadiem')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="mt-4">
                            <label for="event_type_id" class="form-label">Chọn loại sự kiện</label>
                            <select name="event_type_id" id="event_type_id" class="form-select mt-2 sm:mr-2" required>
                                @foreach($eventtype as $type)
                                    <option value="{{ $type->id }}" {{ $event->event_type_id == $type->id ? 'selected' : '' }}>{{ $type->title }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('event_type_id'))
                                <div class="text-danger mt-2">{{ $errors->first('event_type_id') }}</div>
                            @endif
                        </div>
                        

                        <!-- Thời gian bắt đầu -->
                        <div class="mb-4">
                            <label for="timestart" class="form-label">Thời gian bắt đầu</label>
                            <input type="datetime-local" name="timestart" id="timestart" class="form-control" value="{{ old('timestart', \Carbon\Carbon::parse($event->timestart)->format('Y-m-d\TH:i')) }}" required>
                            @error('timestart')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Thời gian kết thúc -->
                        <div class="mb-4">
                            <label for="timeend" class="form-label">Thời gian kết thúc</label>
                            <input type="datetime-local" name="timeend" id="timeend" class="form-control" value="{{ old('timeend', \Carbon\Carbon::parse($event->timeend)->format('Y-m-d\TH:i')) }}" required>
                            @error('timeend')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Button Submit -->
                        <div class="mb-4 text-right">
                            <button type="submit" class="btn btn-primary">Cập nhật sự kiện</button>
                            <a href="{{ route('admin.Event.index') }}" class="btn btn-secondary">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>


    @endsection

    @section('scripts')
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
