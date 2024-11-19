@extends('backend.layouts.master')

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">Sửa thành viên câu lạc bộ</h2>

    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Form Layout -->
            <form method="post" action="{{ route('admin.FanclubUser.update', $fanclubuser->id) }}">
                @csrf
                @method('PUT')
                <div class="intro-y box p-5">

                    <!-- Chọn User -->
                    <div class="mt-4">
                        <label for="user_id" class="form-label">Chọn thành viên</label>
                        <select name="user_id" id="user_id" class="form-select mt-2 sm:mr-2" required>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $fanclubuser->user_id == $user->id ? 'selected' : '' }}>{{ $user->username }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('user_id'))
                            <div class="text-danger mt-2">{{ $errors->first('user_id') }}</div>
                        @endif
                    </div>

                    <!-- Chọn Fanclub -->
                    <div class="mt-3">
                        <label for="fanclub_id" class="form-label">Chọn câu lạc bộ</label>
                        <select name="fanclub_id" id="fanclub_id" class="form-select mt-2 sm:mr-2" required>
                            @foreach($fanclubs as $fanclub)
                                <option value="{{ $fanclub->id }}" {{ $fanclubuser->fanclub_id == $fanclub->id ? 'selected' : '' }}>{{ $fanclub->title }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('fanclub_id'))
                            <div class="text-danger mt-2">{{ $errors->first('fanclub_id') }}</div>
                        @endif
                    </div>

                    <!-- Chọn Role -->
                    <div class="mt-3">
                        <label for="role_id" class="form-label">Chọn vai trò</label>
                        <select name="role_id" id="role_id" class="form-select mt-2 sm:mr-2" required>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ $fanclubuser->role_id == $role->id ? 'selected' : '' }}>{{ $role->title }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('role_id'))
                            <div class="text-danger mt-2">{{ $errors->first('role_id') }}</div>
                        @endif
                    </div>

                    <!-- Nút Cập nhật và Hủy -->
                    <div class="text-right mt-5">
                        <button type="submit" class="btn btn-primary w-24">Cập nhật</button>
                        <a href="{{ route('admin.FanclubUser.index') }}" class="btn btn-secondary w-24">Hủy</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
