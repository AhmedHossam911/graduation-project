<style>
    .flash-container {
        position: fixed;
        top: 20px;
        left: 20px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 10px;
        max-width: 350px;
        direction: rtl;
    }
    .flash-container .alert {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        border-radius: 8px;
        margin-bottom: 0;
        animation: slideInLeft 0.3s ease-out forwards;
        position: relative;
        padding-left: 30px;
    }
    .flash-close-btn {
        position: absolute;
        top: 10px;
        left: 10px;
        cursor: pointer;
        opacity: 0.6;
        transition: opacity 0.2s;
        font-size: 16px;
    }
    .flash-close-btn:hover {
        opacity: 1;
    }
    @keyframes slideInLeft {
        from { transform: translateX(-100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
</style>

<div class="flash-container">
    @if(session('success'))
    <div class="alert alert-success">
        <i class="fa-solid fa-xmark flash-close-btn" onclick="this.parentElement.remove()"></i>
        <i class="fa-solid fa-circle-info"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if($errors->has('throttle') || session('error') == 'suspended')
    <div class="alert alert-danger" style="text-align: center; justify-content: center; flex-direction: column; gap: 5px;">
        <i class="fa-solid fa-xmark flash-close-btn" onclick="this.parentElement.remove()"></i>
        <strong>تم إيقاف الحساب مؤقتًا بسبب تكرار محاولات تسجيل الدخول غير الصحيحة.</strong>
        <span>يُرجى المحاولة مرة أخرى بعد قليل.</span>
    </div>
    @elseif(session('error') && session('error') !== 'suspended')
    <div class="alert alert-danger">
        <i class="fa-solid fa-xmark flash-close-btn" onclick="this.parentElement.remove()"></i>
        <i class="fa-solid fa-circle-xmark"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    @if($errors->any() && !session('error') && !($errors->has('throttle')))
    <div class="alert alert-danger" style="display: block; text-align: right;">
        <i class="fa-solid fa-xmark flash-close-btn" onclick="this.parentElement.remove()"></i>
        <div style="margin-bottom: 10px;">
            <i class="fa-solid fa-circle-xmark" style="margin-left: 5px;"></i>
            <strong style="margin-right: 5px;">يرجى التأكد من البيانات المدخلة:</strong>
        </div>
        <ul style="margin: 0; padding-right: 30px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
</div>
