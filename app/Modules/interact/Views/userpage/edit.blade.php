@extends('backend.layouts.master')
@section ('scriptop')

<meta name="csrf-token" content="{{ csrf_token() }}">
 
@endsection
@section('content')

<div class = 'content'>
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Điều chỉnh trang cá nhân
        </h2>
    </div>
    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Form Layout -->
            <form method="post" action="{{route('admin.userpage.update',$userpage->id)}}">
                @csrf
                @method('patch')
                <div class="intro-y box p-5">
                    <div class="mt-3">
                        <label for="title" class="form-label">Tiêu đề</label>
                        <input id="title" name="title" type="text" value="{{ old('title', $userpage->title) }}" class="form-control" placeholder="Tên nhóm">
                    </div>

                    <div class="mt-3">
                        <label for="summary" class="form-label">Mô tả</label>
                        <textarea id="summary" name="summary" class="form-control" placeholder="Mô tả">{{ old('summary', $userpage->summary) }}</textarea>
                    </div>

                    
                    <div class="mt-3">
                        @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>    {{$error}} </li>
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
</div>
@endsection

@section ('scripts')


 
 
 
@endsection