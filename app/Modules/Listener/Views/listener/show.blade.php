@extends('backend.layouts.master')

@section('scriptop')

@section('content')

<div class="content">
    @include('backend.layouts.notification')

    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto text-indigo-600">
            Thông tin Listener
        </h2>
    </div>

    <div class="grid grid-cols-12 gap-6">
        <!-- BEGIN: Profile Info -->
        <div class="col-span-12 lg:col-span-9 2xl:col-span-10">
            <div class="intro-y box mt-5">
                <div class="relative flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                    <h2 class="font-medium text-base mr-auto text-indigo-500">
                        Thông tin Listener: {{ $listener->id }}
                    </h2>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-12 gap-6">
                        <!-- Favorite Type -->
                        <div class="col-span-12 sm:col-span-6">
                            <label for="favorite_type" class="font-medium form-label">Loại yêu thích:</label>
                            <p>{{ $listener->favorite_type }}</p>
                        </div>

                        <!-- Favorite Song -->
                        <div class="col-span-12 sm:col-span-6">
                            <label for="favorite_song" class="font-medium form-label">Bài hát yêu thích:</label>
                            <p>
                                @if($listener->favoriteSong)
                                    {{ $listener->favoriteSong->title }}
                                @else
                                    Không có bài hát yêu thích
                                @endif
                            </p>
                        </div>

                        <!-- Favorite Singer -->
                        <div class="col-span-12 sm:col-span-6">
                            <label for="favorite_singer" class="font-medium form-label">Ca sĩ yêu thích:</label>
                            <p>
                                @if($listener->favoriteSinger)
                                    {{ $listener->favoriteSinger->alias }}
                                @else
                                    Không có ca sĩ yêu thích
                                @endif
                            </p>
                        </div>

                        <!-- Favorite Composer -->
                        <div class="col-span-12 sm:col-span-6">
                            <label for="favorite_composer" class="font-medium form-label">Nhạc sĩ yêu thích:</label>
                            <p>
                                @if($listener->favoriteComposer)
                                    {{ $listener->favoriteComposer->fullname }}
                                @else
                                    Không có nhạc sĩ yêu thích
                                @endif
                            </p>
                        </div>

                        <!-- Status -->
                        <div class="col-span-12 sm:col-span-6">
                            <label for="status" class="font-medium form-label">Trạng thái:</label>
                            <p>{{ $listener->status ?? 'Không có trạng thái' }}</p>
                        </div>

                        <!-- Created At -->
                        <div class="col-span-12 sm:col-span-6">
                            <label for="created_at" class="font-medium form-label">Ngày Tạo:</label>
                            <p>{{ $listener->created_at ? $listener->created_at->format('d/m/Y H:i:s') : 'Không có thông tin ngày tạo' }}</p>
                        </div>

                        <!-- Updated At -->
                        <div class="col-span-12 sm:col-span-6">
                            <label for="updated_at" class="font-medium form-label">Ngày Cập Nhật:</label>
                            <p>{{ $listener->updated_at ? $listener->updated_at->format('d/m/Y H:i:s') : 'Không có thông tin ngày cập nhật' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: Profile Info -->
    </div>
</div>

@endsection

@section('scripts')
<script>
    function togglePiP(videoId) {
        const videoElement = document.getElementById(videoId);

        // Kiểm tra xem trình duyệt có hỗ trợ Picture-in-Picture không
        if (document.pictureInPictureEnabled) {
            if (videoElement !== document.pictureInPictureElement) {
                // Kích hoạt Picture-in-Picture
                videoElement.requestPictureInPicture()
                    .catch(error => {
                        console.log("Error entering Picture-in-Picture: ", error);
                    });
            } else {
                // Thoát khỏi Picture-in-Picture
                document.exitPictureInPicture()
                    .catch(error => {
                        console.log("Error exiting Picture-in-Picture: ", error);
                    });
            }
        } else {
            alert("Your browser does not support Picture-in-Picture.");
        }
    }
</script>
@endsection
