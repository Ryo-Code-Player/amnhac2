@extends('backend.layouts.master')
@section ('scriptop')
 
@section('content')

<div class = 'content'>
@include('backend.layouts.notification')
 
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
           Thông tin ca sĩ
        </h2>
    </div>
                <div class="grid grid-cols-12 gap-6">
                    <!-- BEGIN: Profile Menu -->
                    <div class="col-span-12 lg:col-span-4 2xl:col-span-3 flex lg:block flex-col-reverse">
                    <?php
                                $photos = explode( ',', $singer->photo);
                    ?>
                        <div class="intro-y box mt-5">
                            <div class="relative flex items-center p-5">
                            <div class="mx-6"> 
                                <div class="single-item"> 
                                     @foreach ($photos as $photo)
                                        <div class="h-32 px-2"> 
                                            <div class="h-full bg-slate-100 dark:bg-darkmode-400 rounded-md"> 
                                            <img    src="{{$photo}}"/>
                                            </div> 
                                        </div> 
                                    @endforeach
                                </div> 
                            </div> 
                           </div>
                        </div>
                    </div>
                    <!-- END: Profile Menu -->
                    <div class="col-span-12 lg:col-span-8 2xl:col-span-9">
                        <!-- BEGIN: Display Information -->
                        <div class="intro-y box lg:mt-5">
                            <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                                <h2 class="font-medium text-base mr-auto">
                                    {{$singer->alias}}
                                </h2>
                            </div>
                            <div class="p-5">
                                <div class="flex flex-col-reverse xl:flex-row flex-col">
                                    <div class="flex-1 mt-6 xl:mt-0">
                                        <div class="grid grid-cols-12 gap-x-5">
                                            <div class="col-span-12 2xl:col-span-3">
                                                <div>
                                                    <label for="update-profile-form-1" class="font-medium form-label">Năm bắt đầu:</label>
                                                    {{$singer->start_year}}
                                                </div>
                                            </div>
                                            <div class="col-span-12 2xl:col-span-3">
                                                <div>
                                                    <label for="update-profile-form-1" class="font-medium form-label">Công ty:</label>
                                                    {{optional($singer->company)->title ?? 'N/A'}}
                                                </div>
                                            </div>
                                            <div class="col-span-12 2xl:col-span-3">
                                                <div>
                                                    <label for="update-profile-form-1" class="font-medium form-label">Loại âm nhạc:</label>
                                                    {{optional($singer->musicType)->title ?? 'N/A'}}
                                                </div>
                                            </div>
                                            <div class="col-span-12 2xl:col-span-3">
                                                <div>
                                                    <label for="update-profile-form-1" class="font-medium form-label">Trạng thái:</label>
                                                    <span class="badge badge-{{ $singer->status == 'active' ? 'success' : 'danger' }}">{{ $singer->status }}</span>
                                                </div>
                                            </div>
                                            <div class="col-span-12 2xl:col-span-6">
                                                <div>
                                                    <label class="font-medium form-label">Ngày tạo:</label>
                                                    {{ $singer->created_at->format('d/m/Y') }}
                                                </div>
                                            </div>
                                            <div class="col-span-12 2xl:col-span-6">
                                                <div>
                                                    <label class="font-medium form-label">Ngày cập nhật:</label>
                                                    {{ $singer->updated_at->format('d/m/Y') }}
                                                </div>
                                            </div>
                                            <div class="col-span-12 2xl:col-span-12">
                                                <div>
                                                    <label class="font-medium form-label">Tóm tắt:</label>
                                                    <p>{!! $singer->summary !!}</p>
                                                </div>
                                            </div>
                                            <div class="col-span-12 2xl:col-span-12">
                                                <div>
                                                    <label class="font-medium form-label">Tiểu sử:</label>
                                                    <p>{!! $singer->content !!}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>  
                                </div>
                                <div class="col-span-12">
    <label for="update-profile-form-1" class="font-medium form-label">Tags:</label>
    <p>
        @foreach (json_decode($singer->tags) as $tagId)
            @php
                $tag = \App\Models\Tag::find($tagId);
            @endphp
            {{ $tag ? $tag->title : 'Tag not found' }}@if(!$loop->last), @endif
        @endforeach
    </p>
</div>

                            </div>
                        </div>
                        <!-- END: Display Information -->
                    </div>
                </div>
        
</div>
@endsection

@section ('scripts')
  
@endsection
