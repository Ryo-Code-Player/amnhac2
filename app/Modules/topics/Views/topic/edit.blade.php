@extends('backend.layouts.master')

@section('scriptop')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/js/tom-select.complete.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('/js/css/tom-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> <!-- Thêm CSS cho ứng dụng -->
@endsection

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">Điều chỉnh chủ đề âm nhạc</h2>
    </div>
    
    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <form method="post" action="{{ route('admin.topic.update', $topic_edit->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <div class="intro-y box p-5">
                    <div>
                        <label for="title" class="form-label">Tên chủ đề</label>
                        <input id="title" name="title" type="text" value="{{ old('title', $topic_edit->title) }}" class="form-control" placeholder="Tên chủ đề" required>
                    </div>

                   

             
                    
                    <div class="mt-3">
                        <div class="flex flex-col sm:flex-row items-center">
                            <label class="form-select-label" for="status">Tình trạng</label>
                            <select name="status" class="form-select mt-2 sm:mr-2">
                                <option value="active" {{ $topic_edit->status == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="inactive" {{ $topic_edit->status == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
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


