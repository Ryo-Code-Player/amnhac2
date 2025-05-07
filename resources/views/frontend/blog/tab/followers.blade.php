<div class="tab-content" id="tab-followers" style="display:none;">
    <div class="followers-list-container">
      <h2 class="followers-title"><i class="fa fa-users"></i> Người đang theo dõi bạn</h2>
      <!-- <div class="followers-search-bar"> 
        <input type="text" id="followers-search-input" placeholder="Tìm kiếm theo tên...">
        <button id="followers-search-btn"><i class="fa fa-search"></i> Tìm kiếm</button>
      </div> -->
      <div class="followers-list" id="followers-list">
 
          @foreach ($follow as $fl)
            <div class="follower-card">
              <img src="{{ $fl->userFollow->photo ? asset('storage/' . $fl->userFollow->photo) : 'https://i.pinimg.com/736x/bc/43/98/bc439871417621836a0eeea768d60944.jpg' }}" class="follower-avatar" alt="avatar">
              <div class="follower-info">
                <div class="follower-name">{{ $fl->userFollow->full_name }}</div>
                <div class="follower-email"><i class="fa fa-envelope"></i> {{ $fl->userFollow->email }}</div>
              </div>
              <a href="{{ route('front.blog.index', ['id' => $fl->userFollow->id]) }}" class="follower-view-btn">Xem trang</a>
            </div>
          @endforeach  
       
      </div>
      <div class="no-followers" style="display:none;">Không tìm thấy người theo dõi nào.</div>
    </div>
    
    <script>
      document.getElementById('followers-search-btn').onclick = function() {
        var keyword = document.getElementById('followers-search-input').value.toLowerCase();
        var cards = document.querySelectorAll('.follower-card');
        var found = false;
        cards.forEach(function(card) {
          var name = card.querySelector('.follower-name').textContent.toLowerCase();
          if (name.includes(keyword)) {
            card.style.display = '';
            found = true;
          } else {
            card.style.display = 'none';
          }
        });
        document.querySelector('.no-followers').style.display = found ? 'none' : 'block';
      };
    </script>
  </div>