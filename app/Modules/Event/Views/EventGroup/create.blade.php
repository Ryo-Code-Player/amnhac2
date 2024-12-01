@extends('backend.layouts.master')

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">Thêm nhóm sự kiện</h2>

    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Form Layout -->
            <form method="POST" action="{{ route('admin.EventGroup.store') }}">
                @csrf
                <div class="intro-y box p-5">

                    <div class="mt-4">
                        <label for="group_id" class="form-label">Nhóm</label>
                        <select name="group_id" id="group_id" class="form-select" required>
                            <option value="">Chọn nhóm</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}" {{ old('group_id') == $group->id ? 'selected' : '' }}>{{ $group->title }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('group_id'))
                            <div class="text-danger mt-2">{{ $errors->first('group_id') }}</div>
                        @endif
                    </div>

                    <div class="mt-3">
                        <label for="event_id" class="form-label">Sự kiện</label>
                        <select name="event_id" id="event_id" class="form-select" required>
                            <option value="">Chọn sự kiện</option>
                            @foreach($events as $event)
                                <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>{{ $event->title }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('event_id'))
                            <div class="text-danger mt-2">{{ $errors->first('event_id') }}</div>
                        @endif
                    </div>

                    <div class="text-right mt-5">
                        <button type="submit" class="btn btn-primary w-24">Lưu</button>
                        <a href="{{ route('admin.EventGroup.index') }}" class="btn btn-secondary w-24">Hủy</a>
                    </div>

                </div>
            </form>
            <!-- END: Form Layout -->
        </div>
    </div>
@endsection
