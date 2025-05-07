<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<style>
    .swal2-confirm{
        background-color: #000000;
        color: #fff;
    }
    .swal2-cancel{
        background-color: #fff;
        color: #000000;
    }
</style>
@if (isset($resources) && count($resources) > 0)
    @foreach ($resources as $resource)
        <div class="intro-y col-span-6 sm:col-span-4 lg:col-span-3">
            <div class="card p-4 border rounded shadow-md relative">
                <a href="{{ route('admin.resources.show', $resource->id) }}">
                    <div class="relative">
                       
                        <iframe style="width: 100%; height: 10rem;"
                        src="{{ str_replace('watch?v=', 'embed/', $resource->url) }}" frameborder="0"
                        allowfullscreen></iframe>
                          
                       
                  

                        <div class="image-title"
                            style="display: flex; align-items: center; justify-content: space-between; position: absolute; inset-x: 0; bottom: -1.5rem; background-color: rgba(255, 255, 255, 0.8); padding: 8px; width: 100%;">
                            <span
                                style="display: block;flex-grow: 1; text-align: left; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 50px;">
                                {{ $resource->title }}
                            </span>
                            <div style="display: flex; align-items: center; gap: 20px;">
                              
                                <form id="delete-form-{{ $resource->id }}"
                                    action="{{ route('admin.resources.destroy', $resource->id) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <a class="dltBtn" data-id="{{ $resource->id }}" href="javascript:;" title="Xóa"
                                        style="color: #ef4444;">
                                        <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
                                    </a>
                                </form>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    @endforeach
    <div class="pagination" style="margin-top: 200px; margin-bottom: 20px;">
        {{ $resources->appends(request()->except('page'))->links() }} <!-- Hiển thị các liên kết phân trang -->
    </div>
@else
    <div class="intro-y col-span-12">
        <div class="text-center p-4">
            <p class="text-lg text-red-600">Không tìm thấy tài nguyên nào!</p>
        </div>
    </div>
@endif
