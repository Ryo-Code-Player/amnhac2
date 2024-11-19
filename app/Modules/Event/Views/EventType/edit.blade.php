@extends('backend.layouts.master')

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">Sửa loại sự kiện</h2>

    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Form Layout -->
            <form method="post" action="{{ route('admin.EventType.update', $eventType->id) }}">
                @csrf
                @method('PUT')
                <div class="intro-y box p-5">

                    <div class="mt-4">
                        <label for="title" class="form-label">Tiêu đề</label>
                        <input id="title" name="title" type="text" class="form-control" placeholder="Nhập tiêu đề" value="{{ old('title', $eventType->title) }}" required>
                        @if ($errors->has('title'))
                            <div class="text-danger mt-2">{{ $errors->first('title') }}</div>
                        @endif
                    </div>

                    <div class="mt-3">
                        <div class="flex flex-col sm:flex-row items-center">
                            <label style="min-width:70px" class="form-select-labe"l for="status">Trạng thái</label>
                            <select name="status" class="form-select mt-2 sm:mr-2">
                                <option value="inactive" {{ old('status', $eventType->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="active" {{ old('status', $eventType->status) == 'active' ? 'selected' : '' }}>Active</option>
                            </select>
                        </div>
                    </div>

                    <div class="text-right mt-5">
                        <button type="submit" class="btn btn-primary w-24">Cập nhật</button>
                        <a href="{{ route('admin.EventType.index') }}" class="btn btn-secondary w-24">Hủy</a>
                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection
