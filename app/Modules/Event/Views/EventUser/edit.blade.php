@extends('backend.layouts.master')

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">Chỉnh sửa người tham gia sự kiện</h2>

    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Form Layout -->
            <form method="POST" action="{{ route('admin.EventUser.update', $eventUser->id) }}">
                @csrf
                @method('PUT')
                <div class="intro-y box p-5">

                    <div class="mt-4">
                        <label for="user_id" class="form-label">Người dùng</label>
                        <select name="user_id" id="user_id" class="form-select" required>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id', $eventUser->user_id) == $user->id ? 'selected' : '' }}>{{ $user->username }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mt-3">
                        <label for="event_id" class="form-label">Sự kiện</label>
                        <select name="event_id" id="event_id" class="form-select" required>
                            @foreach($events as $event)
                                <option value="{{ $event->id }}" {{ old('event_id', $eventUser->event_id) == $event->id ? 'selected' : '' }}>{{ $event->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mt-3">
                        <label for="role_id" class="form-label">Vai trò</label>
                        <select name="role_id" id="role_id" class="form-select">
                            <option value="">Chọn vai trò</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id', $eventUser->role_id) == $role->id ? 'selected' : '' }}>{{ $role->title }}</option>
                            @endforeach
                        </select>
                    </div>

                        {{-- <div class="mt-3">
                            <label for="vote" class="form-label">Điểm bầu chọn</label>
                            <input type="number" name="vote" id="vote" class="form-control" value="{{ old('vote', $eventUser->vote) }}">
                        </div> --}}

                    <div class="text-right mt-5">
                        <button type="submit" class="btn btn-primary w-24">Cập nhật</button>
                        <a href="{{ route('admin.EventUser.index') }}" class="btn btn-secondary w-24">Hủy</a>
                    </div>

                </div>
            </form>
            <!-- END: Form Layout -->
        </div>
    </div>
@endsection
