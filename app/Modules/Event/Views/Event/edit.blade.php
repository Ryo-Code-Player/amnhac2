    @extends('backend.layouts.master')
    @section('content')


        @include('backend.layouts.notification')
        <h2 class="intro-y text-lg font-medium mt-10">
            Chỉnh sửa sự kiện: {{ $event->title }}
        </h2>

        <div class="grid grid-cols-12 gap-12 mt-5">
            <div class="intro-y col-span-12 lg:col-span-8">
                <div class="box p-5">
                    <form action="{{ route('admin.Event.update', $event->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Tiêu đề -->
                        <div class="mb-4">
                            <label for="title" class="form-label">Tiêu đề sự kiện</label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $event->title) }}" required>
                            @error('title')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
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
        // Optional: Adding any JS logic (like image preview or validation) if needed
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
