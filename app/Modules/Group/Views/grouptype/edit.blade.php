@extends('backend.layouts.master')
@section ('scriptop')

<meta name="csrf-token" content="{{ csrf_token() }}">
 
@endsection
@section('content')

<div class = 'content'>
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Điều chỉnh mô tả nhóm
        </h2>
    </div>
    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Form Layout -->
            <form method="post" action="{{route('admin.grouptype.update',$grouptype->id)}}">
                @csrf
                @method('patch')
                <div class="intro-y box p-5">
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Loại nhóm</label>
                        <input id="nametitle" name="nametitle" type="text" value="{{$grouptype->nametitle}}" class="form-control" placeholder="Loại nhóm">
                    </div>

                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Loại nhóm</label>
                        <input id="description" name="description" type="text" value="{{$grouptype->description}}" class="form-control" placeholder="Mô tả">
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