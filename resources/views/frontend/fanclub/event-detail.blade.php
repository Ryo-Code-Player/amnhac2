@extends('frontend.layouts.master')
@section('content')
<div class="event-detail-main">
  <div class="event-detail-container">
    <div class="event-detail-header">
      <img class="event-detail-img" src="{{ $event->photo }}" alt="{{ $event->title }}">
      <div class="event-detail-info">
        <h1 class="event-detail-title">{{ $event->title }}</h1>
        <div class="event-detail-meta">
          <span><i class="fa fa-calendar"></i> Bắt đầu: {{ date('d/m/Y H:i', strtotime($event->timestart)) }}</span>
          <span><i class="fa fa-calendar-check"></i> Kết thúc: {{ date('d/m/Y H:i', strtotime($event->timeend)) }}</span>
          <span><i class="fa fa-map-marker"></i> {{ $event->diadiem }}</span>
        </div>
        <div class="event-detail-ticket">
          <span><i class="fa fa-ticket"></i> Giá vé: <b>{{ number_format($event->price, 0, ',', '.') }} VNĐ</b></span>
          <span><i class="fa fa-users"></i> Số lượng vé: <b>{{ $event->quantity }}</b></span>
        </div>
        @if(auth()->check())
          @if($event->quantity > 0)
            <form action="{{ route('front.payment.link',['id' => $event->id]) }}" method="post">
              @csrf
              <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
              <button type="submit" class="btn-back-event"><i class="fa-solid fa-circle-check"></i> ĐẶT VÉ</button>
            </form>
          @endif
        @endif
        <a href="{{ url()->previous() }}" class="btn-back-event"><i class="fa fa-arrow-left"></i> Quay lại</a>
      </div>
    </div>
    <div class="event-detail-description">
      {!! $event->description !!}
    </div>
  </div>
</div>
<style>
.event-detail-main {
  display: flex;
  justify-content: center;
  background: transparent;
}
.event-detail-container {
  width: 80%;
  margin: 40px auto;
  background: #23234a;
  border-radius: 18px;
  box-shadow: 0 4px 32px #0003;
  padding: 36px 40px 32px 40px;
  color: #fff;
}
.event-detail-header {
  display: flex;
  gap: 36px;
  align-items: flex-start;
  margin-bottom: 32px;
}
.event-detail-img {
  width: 220px;
  height: 220px;
  border-radius: 16px;
  object-fit: cover;
  box-shadow: 0 2px 12px #a259ff33;
  border: 3px solid #a259ff;
  background: #18162b;
}
.event-detail-info {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 12px;
}
.event-detail-title {
  font-size: 2em;
  font-weight: bold;
  color: #a259ff;
  margin-bottom: 6px;
}
.event-detail-meta {
  color: #a259ff;
  font-size: 1.08em;
  display: flex;
  gap: 22px;
  align-items: center;
  flex-wrap: wrap;
}
.event-detail-meta i {
  margin-right: 6px;
}
.event-detail-ticket {
  color: #bbaaff;
  font-size: 1.08em;
  display: flex;
  gap: 22px;
  align-items: center;
  flex-wrap: wrap;
}
.event-detail-ticket i {
  margin-right: 6px;
}
.btn-back-event {
  margin-top: 18px;
  background: linear-gradient(90deg, #7c3aed 0%, #38b6ff 100%);
  color: #fff;
  border: none;
  border-radius: 7px;
  padding: 10px 22px;
  font-size: 1.08em;
  font-weight: 500;
  text-decoration: none;
  transition: background 0.18s, box-shadow 0.18s;
  box-shadow: 0 2px 8px #7c3aed22;
  display: inline-flex;
  align-items: center;
  gap: 8px;
}
.btn-back-event:hover {
  background: linear-gradient(90deg, #38b6ff 0%, #7c3aed 100%);
  color: #fff;
}
.event-detail-description {
  margin-top: 18px;
  background: #28285a;
  border-radius: 12px;
  padding: 24px 22px;
  color: #eee;
  font-size: 1.13em;
  line-height: 1.7;
  box-shadow: 0 2px 8px #0002;
}
.event-detail-description img {
  max-width: 100%;
  height: auto;
  border-radius: 12px;
  display: block;
  margin: 18px auto;
  box-shadow: 0 2px 8px #0003;
}
@media (max-width: 900px) {
  .event-detail-container { width: 98vw; padding: 12px 2vw; }
  .event-detail-header { flex-direction: column; gap: 18px; align-items: center; }
  .event-detail-img { width: 100%; height: 220px; border-radius: 14px 14px 0 0; }
}
</style>
<!-- FontAwesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
@endsection

