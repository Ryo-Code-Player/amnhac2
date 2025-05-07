@extends('backend.layouts.master')

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">Danh sách người tham gia sự kiện</h2>
    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Table -->
            <div class="intro-y box p-5">
                <a href="{{ route('admin.EventUser.create') }}" class="btn btn-primary mb-5">Thêm mới</a>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Mã giao dịch</th>
                            <th>Người dùng</th>
                            <th>Sự kiện</th>
                            <th>Giá</th>
                            <th>Vai trò</th>
                            {{-- <th>Điểm bầu chọn</th> --}}
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if (isset($eventUsers) && count($eventUsers) > 0)
                        @foreach($eventUsers as $eventUser)
                            <tr>
                                <td>{{ $eventUser->code ?? 'Chưa có mã giao dịch' }}</td>
                                <td>{{ $eventUser->user->username }}</td>
                                <td>{{ $eventUser->event->title }} </td>
                                <td>{{ number_format($eventUser->event->price) }} /vnđ</td>
                                <td>{{ $eventUser->role->title ?? 'N/A' }}</td>
                                {{-- <td>{{ $eventUser->vote }}</td> --}}
                                <td>
                                    <a href="{{ route('admin.EventUser.edit', $eventUser->id) }}" class="btn btn-sm btn-warning">Chỉnh sửa</a>
                                    <form action="{{ route('admin.EventUser.destroy', $eventUser->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        @else
                                    <tr>
                                        <td colspan="6" class="text-center p-4">
                                            <p class="text-lg text-red-600">Không tìm thấy người tham gia sự kiện nào!</p>
                                        </td>
                                    </tr>
                                @endif
                    </tbody>
                </table>
                </div>
            <!-- END: Table -->
        </div>
    </div>

    {{ $eventUsers->links() }}
@endsection
