@extends('backend.layouts.master')
@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">Tạo sự kiện mới</h2>

    <form action="{{ route('admin.Event.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="title">Tiêu đề</label>
            <input type="text" name="title" class="form-control" required>
        </div>

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

        <div class="mt-4">
            <label for="event_type_id" class="form-label">Chọn loại sự kiện</label>
            <select name="event_type_id" id="event_type_id" class="form-select mt-2 sm:mr-2" required>
                @foreach($eventtype as $type)
                    <option value="{{ $type->id }}">{{ $type->title }}</option>
                @endforeach
            </select>
            @if ($errors->has('event_type_id'))
                <div class="text-danger mt-2">{{ $errors->first('event_type_id') }}</div>
            @endif
        </div>

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
