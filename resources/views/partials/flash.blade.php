<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .swal2-popup {
        font-family: 'Cairo', sans-serif;
        direction: rtl;
        border-radius: 8px;
    }
    .swal2-confirm {
        border-radius: 8px !important;
        background-color: #193e6a !important;
    }
    .swal-errors-list {
        margin: 0;
        padding-right: 22px;
        text-align: right;
    }
    .swal-errors-list li {
        margin-bottom: 6px;
    }
</style>

<script>
    window.showFlash = function(title, text, icon = 'error') {
        Swal.fire({
            icon: icon,
            title: title,
            text: text,
            confirmButtonText: 'حسنًا',
            timer: icon === 'success' ? 3000 : null,
            timerProgressBar: icon === 'success',
        });
    };
</script>

@php
    $flashMessage = null;

    if (session('success')) {
        $flashMessage = [
            'icon' => 'success',
            'title' => 'تم بنجاح',
            'text' => session('success'),
        ];
    } elseif ($errors->has('throttle') || session('error') === 'suspended') {
        $flashMessage = [
            'icon' => 'error',
            'title' => 'تم إيقاف الحساب مؤقتًا',
            'html' => 'تم إيقاف الحساب مؤقتًا بسبب تكرار محاولات تسجيل الدخول غير الصحيحة.<br>يرجى المحاولة مرة أخرى بعد قليل.',
        ];
    } elseif (session('error')) {
        $flashMessage = [
            'icon' => 'error',
            'title' => 'حدث خطأ',
            'text' => session('error'),
        ];
    } elseif ($errors->any()) {
        $flashMessage = [
            'icon' => 'error',
            'title' => 'يرجى التأكد من البيانات المدخلة',
            'html' => '<ul class="swal-errors-list">'.collect($errors->all())->map(fn ($error) => '<li>'.e($error).'</li>')->implode('').'</ul>',
        ];
    }
@endphp

@if ($flashMessage)
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: @json($flashMessage['icon']),
                title: @json($flashMessage['title']),
                @if (isset($flashMessage['html']))
                    html: {!! json_encode($flashMessage['html']) !!},
                @else
                    text: @json($flashMessage['text']),
                @endif
                confirmButtonText: 'حسنًا',
                timer: @json($flashMessage['icon'] === 'success' ? 3000 : null),
                timerProgressBar: @json($flashMessage['icon'] === 'success'),
            });
        });
    </script>
@endif
