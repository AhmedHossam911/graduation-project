@extends('layouts.dashboard')

@section('title', 'لوحة التحكم')

@section('content')
<div class="text-center py-16 px-5">
    <h2 class="text-primary text-2xl font-bold mb-4">مرحباً في لوحة التحكم</h2>
    <p class="text-slate-500 mb-8">هذه صفحة لوحة التحكم الرئيسية</p>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="inline-flex items-center justify-center gap-2 px-7 py-2.5 bg-primary text-white rounded-md font-semibold transition-colors duration-200 hover:bg-primary-light">
            <i class="fa-solid fa-right-from-bracket"></i>
            تسجيل خروج
        </button>
    </form>
</div>
@endsection
