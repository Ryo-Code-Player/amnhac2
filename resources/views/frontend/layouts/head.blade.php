<?php
   $detail = \App\Models\SettingDetail::find(1);
  
  $keyword = '';
  $description='';
?>
<head>
    <meta charset="UTF-8">
    <meta charset="utf-8">
    <link href="{{$detail?$detail->icon:''}}" rel="shortcut icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="GENERATOR" content="{{$detail?$detail->short_name:''}}" />
    <meta name="keywords" content= "{{isset($keyword)?$keyword:$detail->keyword}}"/>
    <meta name="description" content= "{{isset($description)?strip_tags($description):$detail->memory}}"/>
    <meta name="author" content="{{$detail?$detail->short_name:''}}">
    <title>{{$detail?$detail->company_name:''}} - Âm nhạc của tương lai</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/notiflix@3.2.8/dist/notiflix-aio-3.2.8.min.js"></script>

</head>