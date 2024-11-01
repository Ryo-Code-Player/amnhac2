@extends('backend.layouts.master')
@section ('scriptop')

<meta name="csrf-token" content="{{ csrf_token() }}">
 
@endsection
@section('content')

<div class = 'content'>
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Điều chỉnh nhóm
        </h2>
    </div>
    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Form Layout -->
            <form method="post" action="{{route('admin.group.update',$group->id)}}">
                @csrf
                @method('patch')
                <div class="intro-y box p-5">
                    <div class="mt-3">
                        <label for="title" class="form-label">Tên nhóm</label>
                        <input id="title" name="title" type="text" value="{{ old('title', $group->title) }}" class="form-control" placeholder="Tên nhóm">
                    </div>

                    <div class="mt-3">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea id="description" name="description" class="form-control" placeholder="Mô tả">{{ old('description', $group->description) }}</textarea>
                    </div>

                    <div class="mt-3">
                        <label for="" class="form-label">Ảnh đại diện</label>
                        <div class="px-4 pb-4 mt-5 flex items-center cursor-pointer relative">
                            <div data-single="true" id="mydropzone" class="dropzone" url="{{ route('admin.upload.avatar') }}">
                                <div class="fallback"><input name="file" type="file" /></div>
                                <div class="dz-message" data-dz-message>
                                    <div class="font-medium">Kéo thả hoặc chọn ảnh.</div>
                                </div>
                            </div>
                            <input type="hidden" id="photo" name="photo" value="{{ $group->photo }}"/>
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="flex flex-col sm:flex-row items-center">
                            <label style="min-width:70px" class="form-select-label" for="is_private">Tình trạng</label>
                            <select name="is_private" class="form-select mt-2 sm:mr-2">
                                <option value="public" {{ old('is_private', $group->is_private) == 'public' ? 'selected' : '' }}>Public</option>
                                <option value="private" {{ old('is_private', $group->is_private) == 'private' ? 'selected' : '' }}>Private</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="flex flex-col sm:flex-row items-center">
                            <label style="min-width:70px" class="form-select-label" for="type_id">Loại nhóm</label>
                            <select name="type_id" class="form-select mt-2 sm:mr-2">
                                @foreach($grouptype as $item)
                                    <option value="{{ $item->id }}" {{ $group->type_id == $item->id ? 'selected' : '' }}>{{ $item->nametitle }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="flex flex-col sm:flex-row items-center">
                            <label style="min-width:70px" class="form-select-label" for="status">Trạng thái</label>
                            <select name="status" class="form-select mt-2 sm:mr-2">
                                <option value="inactive" {{ old('status', $group->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="active" {{ old('status', $group->status) == 'active' ? 'selected' : '' }}>Active</option>
                            </select>
                        </div>
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