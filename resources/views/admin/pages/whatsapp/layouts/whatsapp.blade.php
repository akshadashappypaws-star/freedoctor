@extends('admin.layouts.master')

@section('title', 'WhatsApp Management')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/whatsapp-layout.css') }}">
@endpush

@section('content')
<div class="whatsapp-content">
    @yield('whatsapp_content')
</div>
@endsection

