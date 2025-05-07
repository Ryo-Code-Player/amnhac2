<div id="tab-albums" class="tab-content" style="display:none; padding: 20px;">
    
    <div id="avatar-gallery" class="modern-gallery">
      @foreach ($image_user as $image)
        <div class="modern-gallery-item">
          <img src="{{ asset('storage/' . $image->image) }}" alt="Ảnh" class="modern-gallery-img">
        
          <button class="modern-gallery-delete" data-id="{{ $image->id }}"><i class="fa fa-trash"></i></button>
        </div>
        
        
      @endforeach
    </div>
    <div id="avatar-lightbox" class="modern-lightbox">
      <span id="close-avatar-lightbox" class="modern-lightbox-close">&times;</span>
      <button id="avatar-lightbox-prev" style="position:absolute;left:30px;top:50%;transform:translateY(-50%);font-size:2em;background:none;border:none;color:#fff;z-index:10001;cursor:pointer;">&#8592;</button>
      <img id="avatar-lightbox-img" src="">
      <button id="avatar-lightbox-next" style="position:absolute;right:30px;top:50%;transform:translateY(-50%);font-size:2em;background:none;border:none;color:#fff;z-index:10001;cursor:pointer;">&#8594;</button>
    </div>
 
   
</div>