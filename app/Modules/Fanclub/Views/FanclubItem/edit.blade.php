@extends('backend.layouts.master')

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">Sửa loại liên kết tài nguyên</h2>

    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Form Layout -->
            <form method="post" action="{{ route('admin.FanclubItem.update', $fanclubitem->id) }}">
                @csrf
                @method('PUT')
                <div class="intro-y box p-5">

                    <div class="mt-4">
                        <label for="code" class="form-label">Mã tài nguyên</label>
                        <input id="code" name="resource_code" type="text" class="form-control" placeholder="Nhập mã tài nguyên" value="{{ old('resource_code', $fanclubitem->resource_code) }}" required>
                        @if ($errors->has('code'))
                            <div class="text-danger mt-2">{{ $errors->first('code') }}</div>
                        @endif
                    </div>

                    <div class="mt-3">
                        <div class="flex flex-col sm:flex-row items-center">
                            <label style="min-width:70px" class="form-select-label" for="type_id">Loại nhóm</label>
                            <select name="resource_id" class="form-select mt-2 sm:mr-2">
                                @foreach($resource as $item)
                                    <option value="{{ $item->id }}" {{ $fanclubitem->resource_id == $item->id ? 'selected' : '' }}>{{ $item->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="text-right mt-5">
                        <button type="submit" class="btn btn-primary w-24">Cập nhật</button>
                        <a href="{{ route('admin.FanclubItem.index') }}" class="btn btn-secondary w-24">Hủy</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
