@extends('frontend.layouts.master')
@section('content')

<div class="fanclub-main-center">
  <div class="fanclub-detail-container">
    <div class="fanclub-header">
      <img class="fanclub-avatar" src="{{ $fanclub->photo }}" alt="{{ $fanclub->title }}">
      <div class="fanclub-info">
        <h1 class="fanclub-title">{{ $fanclub->title }}</h1>
        <p class="fanclub-summary">{{ $fanclub->summary }}</p>
        <div class="fanclub-meta">
          <span>Chủ fanclub: <b>{{ $fanclub->user->full_name ?? '' }}</b></span>
          <span>Lượt quan tâm: <b>{{ $fanclub->quantity }}</b></span>
        </div>
        <div class="fanclub-content">{!! $fanclub->content !!}</div>
      </div>
    </div>
    <div class="fanclub-event-section">
        <div class="event-section-header">
          <h2>Sự kiện</h2>
          @if(auth()->user()->id == $fanclub->user_id)
            <a style="text-decoration: none;" href="{{ route('front.fanclub.event.create', ['fanclub' => $fanclub->id]) }}" class="btn-create-event">Tạo sự kiện</a>
          @endif
        </div>
        <div class="event-list">
          @foreach($fanclub->events as $event)
          <div class="event-card">
            <img class="event-img" src="{{ $event->photo }}" alt="{{ $event->title }}">
            <div class="event-info">
              <h3 class="event-title">{{ $event->title }}</h3>
              <div class="event-meta">
                <span><i class="fa fa-calendar"></i> {{ date('d/m/Y H:i', strtotime($event->timestart)) }}</span>
                <span><i class="fa fa-calendar-check"></i> {{ date('d/m/Y H:i', strtotime($event->timeend)) }}</span>
                <span><i class="fa fa-map-marker"></i> {{ $event->diadiem }}</span>
              </div>
              <div class="event-summary">{{ $event->summary }}</div>
              <div class="event-actions">
                <a href="{{ route('front.fanclub.event.detail', ['event' => $event->slug]) }}" class="btn-event-detail"><i class="fa fa-eye"></i> Xem chi tiết</a>
                @if(auth()->user()->id == $fanclub->user_id)
                  <form action="{{ route('front.fanclub.event.delete', ['id' => $event->id]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sự kiện này?');">
                    @csrf
                    @method('POST')
                    <button type="submit" class="btn-event-delete"><i class="fa fa-trash"></i> Xóa</button>
                  </form>
                @endif
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    
  </div>
</div>

<style>
body, html {
  height: 100%;
}
.fanclub-main-center {
  /* min-height: 100vh; */
  display: flex;
  /* align-items: center; */
  justify-content: center;
  background: transparent;
}
.fanclub-detail-container {
  width: 1100px;
  margin: 0 auto;
  background: #23234a;
  border-radius: 18px;
  box-shadow: 0 4px 32px #0003;
  padding: 36px 40px 32px 40px;
  color: #fff;
}
.fanclub-header {
  display: flex;
  gap: 36px;
  align-items: flex-start;
  margin-bottom: 36px;
}
.fanclub-avatar {
  width: 140px;
  height: 140px;
  border-radius: 16px;
  object-fit: cover;
  box-shadow: 0 2px 12px #a259ff33;
  border: 3px solid #a259ff;
}
.fanclub-info {
  flex: 1;
}
.fanclub-title {
  font-size: 2.2em;
  font-weight: bold;
  margin-bottom: 8px;
  color: #a259ff;
}
.fanclub-summary {
  color: #bbaaff;
  font-size: 1.1em;
  margin-bottom: 10px;
}
.fanclub-meta {
  color: #bbb;
  font-size: 1em;
  margin-bottom: 12px;
  display: flex;
  gap: 24px;
}
.fanclub-content {
  color: #eee;
  font-size: 1.08em;
  margin-bottom: 10px;
}
.fanclub-event-section {
  margin-top: 30px;
}
.event-section-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 18px;
}
.event-section-header h2 {
  color: #a259ff;
  font-size: 1.4em;
  font-weight: bold;
}
.btn-create-event {
  background: #a259ff;
  color: #fff;
  border: none;
  border-radius: 8px;
  padding: 10px 22px;
  font-size: 1.08em;
  cursor: pointer;
  transition: background 0.2s;
  font-weight: 500;
}
.btn-create-event:hover {
  background: #7a1fff;
}
.event-list {
  display: flex;
  flex-direction: column;
  gap: 22px;
}
.event-card {
  display: flex;
  background: linear-gradient(90deg, #28285a 60%, #23234a 100%);
  border-radius: 14px;
  box-shadow: 0 2px 12px #0002;
  overflow: hidden;
  transition: box-shadow 0.2s, transform 0.18s;
  position: relative;
}
.event-card:hover {
  box-shadow: 0 8px 32px #a259ff55, 0 2px 8px #0003;
  transform: translateY(-4px) scale(1.02);
  border: 1.5px solid #a259ff;
}
.event-img {
  width: 160px;
  height: 160px;
  object-fit: cover;
  border-radius: 0 14px 14px 0;
  background: #18162b;
}
.event-info {
  flex: 1;
  padding: 18px 24px 18px 24px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  position: relative;
}
.event-title {
  font-size: 1.25em;
  font-weight: bold;
  color: #fff;
  margin-bottom: 6px;
  letter-spacing: 0.5px;
}
.event-meta {
  color: #a259ff;
  font-size: 0.98em;
  margin-bottom: 8px;
  display: flex;
  gap: 18px;
  align-items: center;
  flex-wrap: wrap;
}
.event-meta i {
  margin-right: 5px;
}
.event-summary {
  color: #bbaaff;
  font-size: 1.05em;
  margin-bottom: 10px;
  margin-top: 2px;
  line-height: 1.5;
}
.event-actions {
  display: flex;
  gap: 14px;
  margin-top: 10px;
}
.btn-event-detail {
  background: linear-gradient(90deg, #7c3aed 0%, #38b6ff 100%);
  color: #fff;
  border: none;
  border-radius: 7px;
  padding: 8px 18px;
  font-size: 1em;
  font-weight: 500;
  text-decoration: none;
  transition: background 0.18s, box-shadow 0.18s;
  box-shadow: 0 2px 8px #7c3aed22;
  display: flex;
  align-items: center;
  gap: 6px;
}
.btn-event-detail:hover {
  background: linear-gradient(90deg, #38b6ff 0%, #7c3aed 100%);
  color: #fff;
}
.btn-event-delete {
  background: #ff4d6d;
  color: #fff;
  border: none;
  border-radius: 7px;
  padding: 8px 18px;
  font-size: 1em;
  font-weight: 500;
  cursor: pointer;
  transition: background 0.18s, box-shadow 0.18s;
  box-shadow: 0 2px 8px #ff4d6d22;
  display: flex;
  align-items: center;
  gap: 6px;
}
.btn-event-delete:hover {
  background: #d90429;
  color: #fff;
}
@media (max-width: 700px) {
  .fanclub-detail-container { padding: 16px 4vw; }
  .fanclub-header { flex-direction: column; gap: 18px; align-items: center; }
  .event-card { flex-direction: column; }
  .event-img { width: 100%; height: 180px; border-radius: 14px 14px 0 0; }
  .event-info { padding: 14px 10px; }
  .event-actions { flex-direction: column; gap: 10px; }
}
</style>

<!-- FontAwesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

@endsection
