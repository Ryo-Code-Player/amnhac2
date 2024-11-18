@extends('backend.layouts.master')
@section ('scriptop')
 
@section('content')

<div class = 'content'>
@include('backend.layouts.notification')
 
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
           Thông tin nhạc sĩ
        </h2>
    </div>
                <div class="grid grid-cols-12 gap-6">
                    <!-- BEGIN: Profile Menu -->
                    <div class="col-span-12 lg:col-span-4 2xl:col-span-3 flex lg:block flex-col-reverse">
                        <div class="intro-y box mt-5">
                            <div class="relative flex items-center p-5">
                                <div class="mx-6"> 
                                    <div class="single-item">
                                        <div class="h-32 px-2"> 
                                            <div class="h-full bg-slate-100 dark:bg-darkmode-400 rounded-md"> 
                                                <img src="{{ $composer->photo }}" alt="Composer Photo"/>
                                            </div> 
                                        </div> 
                                    </div> 
                                </div> 
                           </div>
                        </div>
                    </div>
                    <!-- END: Profile Menu -->
                    <div class="col-span-12 lg:col-span-8 2xl:col-span-9">
                        <!-- BEGIN: Display Information -->
                        <div class="intro-y box lg:mt-5">
                        <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400 justify-center">
    <h2 class="font-medium text-base">
        {{$composer->fullname}}
    </h2>
</div>

                            <div class="p-5">
                                <div class="grid grid-cols-12 gap-x-5">
                                            <div class="col-span-12 2xl:col-span-6">
                                                <div>
                                                    <label class="font-medium form-label">Tên:</label>
                                                    {{$composer->fullname}}
                                                </div>
                                            </div>
                                            <div class="col-span-12 2xl:col-span-6">
                                                <div>
                                                    <label class="font-medium form-label">Slug:</label>
                                                    {{$composer->slug}}
                                                </div>
                                            </div>
                                            <div class="col-span-12 2xl:col-span-6">
                                                <div>
                                                    <label class="font-medium form-label">Trạng thái:</label>
                                                    <span class="badge badge-{{ $composer->status == 'active' ? 'success' : 'danger' }}">{{ $composer->status }}</span>
                                                </div>
                                            </div>
                                            <div class="col-span-12 2xl:col-span-12">
                                                <div>
                                                    <label class="font-medium form-label">Tóm tắt:</label>
                                                    <p>{!! $composer->summary !!}</p>
                                                </div>
                                            </div>
                                            <div class="col-span-12 2xl:col-span-12">
                                                <div>
                                                    <label class="font-medium form-label">Tiểu sử:</label>
                                                    <p>{!! $composer->content !!}</p>
                                                </div>
                                            </div>
                                            <div class="col-span-12 2xl:col-span-6">
                                                <div>
                                                    <label class="font-medium form-label">Ngày tạo:</label>
                                                    {{ $composer->created_at->format('d/m/Y') }}
                                                </div>
                                            </div>
                                            <div class="col-span-12 2xl:col-span-6">
                                                <div>
                                                    <label class="font-medium form-label">Ngày cập nhật:</label>
                                                    {{ $composer->updated_at->format('d/m/Y') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>  
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
