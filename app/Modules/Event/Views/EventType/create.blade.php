@extends('backend.layouts.master')

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">Thêm loại sự kiện</h2>

    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Form Layout -->
            <form method="post" action="{{ route('admin.EventType.store') }}">
                @csrf
                <div class="intro-y box p-5">

                    <div class="mt-4">
                        <label for="title" class="form-label">Tiêu đề</label>
                        <input id="title" name="title" type="text" class="form-control" placeholder="Nhập tiêu đề" value="{{ old('title') }}" required>
                        @if ($errors->has('title'))
                            <div class="text-danger mt-2">{{ $errors->first('title') }}</div>
                        @endif
                    </div>

                    <div class="mt-3">
                        <label for="status" class="form-label">Trạng thái</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value ="active" {{old('status')=='active'?'selected':''}}>Active</option>
                            <option value = "inactive" {{old('status')=='inactive'?'selected':''}}>Inactive</option>
                        </select>
                    </div>

                    <div class="text-right mt-5">
                        <button type="submit" class="btn btn-primary w-24">Lưu</button>
                        <a href="{{ route('admin.EventType.index') }}" class="btn btn-secondary w-24">Hủy</a>
                    </div>

                </div>
            </form>
        </div>
    </div>
    
@endsection
