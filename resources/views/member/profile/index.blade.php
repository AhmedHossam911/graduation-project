@extends('layouts.pages')
@section('title', 'الملف الشخصي')
@section('content')
    @include('partials.flash')
    <style src="{{ asset('css/profile.css') }}"></style>
    <div class="profile transition-all duration-300">
        <div class="profile-header py-7 text-center">
            <h1 class="text-xl font-semibold text-[#124375]">
                الملف الشخصي
            </h1>
        </div>
        <div class="relative profile-body border border-[#124375] max-w-lg mx-auto rounded-2xl bg-[#F4F7F9]">
            <h2 class=" text-base font-medium text-[#124375] absolute top-[-15px] right-3 bg-[#F4F7F9]">
                البيانات الشخصية
            </h2>
            <form class=" px-8 py-10 space-y-5" method="post" action="{{ route('profile.update') }}">
                @csrf
                <div class="flex flex-col gap-5 gap-7">
                    <div class="relative w-full">
                        <label class="text-base font-medium text-[#124375] absolute top-[-15px] right-3 bg-[#F4F7F9]">
                            الأسم كامل
                        </label>
                        <input type="text" placeholder="احمد محمد ابراهيم خليل" value="{{ auth()->user()->name }}"
                            name="name"
                            class="outline-none focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition py-2 w-full border border-[#124375] bg-[#F4F7F9] rounded-xl text-center ">
                    </div>
                    <div class="relative w-full">
                        <label class="text-base font-medium text-[#124375] absolute top-[-15px] right-3 bg-[#F4F7F9]">
                            الرقم الوظيفي
                        </label>
                        <input type="number" placeholder="123456" value="{{ auth()->user()->id }}" disabled
                            class="outline-none focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition py-2 w-full border border-[#124375] bg-[#F4F7F9] rounded-xl text-center ">
                    </div>
                    <div class="relative w-full">
                        <label class="text-base font-medium text-[#124375] absolute top-[-15px] right-3 bg-[#F4F7F9]">
                            البريد الإلكتروني
                        </label>
                        <input type="email" placeholder="ahmedkhalil@gmail.com" value="{{ auth()->user()->email }}"
                            name="email"
                            class="outline-none focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition py-2 w-full border border-[#124375] bg-[#F4F7F9] rounded-xl text-center ">
                    </div>
                    <div class="relative w-full">
                        <label class="text-base font-medium text-[#124375] absolute top-[-15px] right-3 bg-[#F4F7F9]">
                            المسمي الوظيفي
                        </label>
                        <input type="text" placeholder="موظف إدخال بيانات"
                            value="{{ auth()->user()->member?->employmentInfo?->job_title ?? 'غير محدد' }}" disabled
                            class="outline-none focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition py-2 w-full border border-[#124375] bg-[#F4F7F9] rounded-xl text-center ">
                    </div>
                    <div class="relative w-full">
                        <label class="text-base font-medium text-[#124375] absolute top-[-15px] right-3 bg-[#F4F7F9]">
                            حالة الحساب
                        </label>
                        <input type="text" placeholder="نشط" value="{{ auth()->user()->is_suspend ? 'محظور' : 'نشط' }}"
                            disabled
                            class="outline-none focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition py-2 w-full border border-[#124375] bg-[#F4F7F9] rounded-xl text-center ">
                    </div>
                </div>
                <p class="text-base font-medium text-[#D4AF37] underline px-2 forget-pass cursor-pointer">تغيير كلمة المرور
                </p>
                <button
                    class="flex justify-center items-center gap-3 bg-[#124375] text-white w-full py-3 text-base font-medium rounded-xl hover:bg-[#0e3560] transition-colors">
                    <iconify-icon icon="ic:round-edit" class="text-2xl"></iconify-icon>
                    تعديل بيانات
                </button>
                <p class="text-base text-[#124375] px-2 font-medium">أخر تسجيل دخول :</p>
                <div class="text-base font-medium flex items-center gap-7 px-2">
                    <p>يوم : {{ auth()->user()->last_login->isoFormat('dddd D MMMM YYYY') }}</p>
                    <p>الساعة : {{ auth()->user()->last_login->isoFormat('h:mm A') }}</p>
                </div>

                <!-- Passkeys Section -->
                <div class="mt-8 border-t border-[#124375] pt-5">
                    <h3 class="text-lg font-semibold text-[#124375] mb-3">إعدادات البصمة (التحقق الثنائي)</h3>
                    <p class="text-sm text-gray-600 mb-4">يمكنك إضافة بصمة إصبع أو وجه لتأكيد تسجيل الدخول كخطوة إضافية بدلاً من رسائل الإيميل.</p>
                    
                    <ul class="mb-4 space-y-2">
                        @foreach(auth()->user()->passkeys ?? [] as $passkey)
                            <li class="flex justify-between items-center bg-white p-3 rounded shadow-sm border border-gray-200">
                                <div>
                                    <span class="font-medium">{{ $passkey->name }}</span>
                                    <span class="text-xs text-gray-500 block">أخر استخدام: {{ $passkey->last_used_at ? $passkey->last_used_at->diffForHumans() : 'لم تستخدم' }}</span>
                                </div>
                                <form method="POST" action="/user/passkeys/{{ $passkey->id }}" onsubmit="return confirm('هل أنت متأكد من حذف هذه البصمة؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                        <iconify-icon icon="mingcute:delete-2-fill" class="text-xl"></iconify-icon>
                                    </button>
                                </form>
                            </li>
                        @endforeach
                    </ul>

                    <button type="button" id="register-passkey-btn" class="flex justify-center items-center gap-3 bg-[#28a745] text-white w-full py-3 text-base font-medium rounded-xl hover:bg-[#218838] transition-colors">
                        <iconify-icon icon="fa-solid:fingerprint" class="text-2xl"></iconify-icon>
                        إضافة بصمة جديدة
                    </button>
                    <p id="passkey-status" class="text-center mt-2 text-sm hidden"></p>
                </div>
            </form>
        </div>
    </div>

    <div id="modal-backdrop" class="fixed inset-0 bg-black/20 backdrop-blur-sm z-[90] hidden brightness-50"></div>

    <div
        class="modal fixed hidden right-1/2 translate-x-[50%] top-1/2 translate-y-[-50%] surface-shadow rounded-2xl max-w-lg w-[90%] mx-auto bg-[#F4F7F9] px-4 pb-10 pt-5 z-[100]">
        <button class="close-btn text-[#124375] surface-shadow flex justify-center items-center p-2 rounded bg-[#F4F7F9] ">
            <iconify-icon icon="mingcute:close-fill" class="text-xl"></iconify-icon>
        </button>
        <div class="modal-header text-center">
            <h2 class="text-xl font-semibold text-[#124375] py-5 ">
                تغيير كلمة المرور
            </h2>
        </div>
        <form class="px-7" action="{{ route('profile.change-password') }}" method="post">
            @csrf
            <div class="modal-body flex flex-col gap-5 ">
                <p class="px-2 text-[#021219] text-sm font-medium ">يرجى إدخال كلمة المرور الحالية ثم تعيين كلمة مرور جديدة.
                </p>
                <div class="input-group relative">
                    <iconify-icon icon="mingcute:lock-fill"
                        class="absolute right-0 bg-[#124375] text-white h-full flex items-center text-xl px-3 rounded-s-xl"></iconify-icon>
                    <input type="password" placeholder="كلمة المرور الحالية" name="current_password"
                        class="input-field focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition bg-[#F4F7F9] border-2 border-[#124375] outline-none w-full rounded-xl text-center py-2">
                    <iconify-icon icon="solar:eye-outline"
                        class="show-btn cursor-pointer absolute left-4 top-[50%] translate-y-[-50%] text-[#A8A8A8] text-xl"></iconify-icon>
                </div>
                <div class="new-pass space-y-2">
                    <div class="input-group relative">
                        <iconify-icon icon="mingcute:lock-fill"
                            class="absolute right-0 bg-[#124375] text-white h-full flex items-center text-xl px-3 rounded-s-xl"></iconify-icon>
                        <input type="password" placeholder="كلمة المرور الجديدة" name="new_password"
                            class="input-field focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition bg-[#F4F7F9] border-2 border-[#124375] outline-none w-full rounded-xl text-center py-2">
                        <iconify-icon icon="solar:eye-outline"
                            class="show-btn cursor-pointer absolute left-4 top-[50%] translate-y-[-50%] text-[#A8A8A8] text-xl"></iconify-icon>
                    </div>
                    <p class="px-2 text-xs text-[#124375]">يجب أن تحتوي كلمة المرور على 8 أحرف على الأقل وتتضمن أحرفًا
                        وأرقامًا.</p>
                </div>
                <div class="confirm-pass space-y-2">
                    <div class="input-group relative">
                        <iconify-icon icon="mingcute:lock-fill"
                            class="absolute right-0 bg-[#124375] text-white h-full flex items-center text-xl px-3 rounded-s-xl"></iconify-icon>
                        <input type="password" placeholder="تأكيد كلمة المرور " name="new_password_confirmation"
                            class="input-field focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition bg-[#F4F7F9] border-2 border-[#124375] outline-none w-full rounded-xl text-center py-2">
                        <iconify-icon icon="solar:eye-outline"
                            class="show-btn cursor-pointer absolute left-4 top-[50%] translate-y-[-50%] text-[#A8A8A8] text-xl"></iconify-icon>
                    </div>
                    <p class="px-2 text-xs text-[#124375]">يجب أن تكون كلمتا المرور متطابقتين.</p>
                </div>
                <button
                    class="text-white surface-shadow bg-[#124375] rounded-xl py-2 hover:bg-[#0e3560] transition-colors">تحديث
                    كلمة المرور</button>
            </div>
        </form>
    </div>

    <script src="{{ asset('js/profile.js') }}"></script>
    
    <script type="module">
        import { create } from 'https://unpkg.com/@github/webauthn-json@2.1.1/dist/browser-ponyfill.js';

        document.getElementById('register-passkey-btn')?.addEventListener('click', async () => {
            const statusEl = document.getElementById('passkey-status');
            statusEl.classList.remove('hidden');
            statusEl.innerText = 'جاري التحضير... الرجاء استخدام البصمة.';
            statusEl.className = 'text-center mt-2 text-sm text-[#D4AF37]';

            try {
                // 1. Get options from backend
                const optionsRes = await fetch('/user/passkeys/options');
                if (!optionsRes.ok) throw new Error('فشل جلب إعدادات البصمة.');
                const options = await optionsRes.json();

                // 2. Create credential
                const credential = await create(options);

                // 3. Send to backend
                const storeRes = await fetch('/user/passkeys', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        credential,
                        name: 'جهاز ' + navigator.platform
                    })
                });

                if (storeRes.ok) {
                    statusEl.innerText = 'تم إضافة البصمة بنجاح! سيتم تحديث الصفحة.';
                    statusEl.className = 'text-center mt-2 text-sm text-green-600';
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    const errData = await storeRes.json();
                    throw new Error(errData.message || 'فشل حفظ البصمة في السيرفر.');
                }
            } catch (error) {
                console.error(error);
                statusEl.innerText = error.message || 'حدث خطأ أثناء تسجيل البصمة. تأكد من دعم جهازك لها.';
                statusEl.className = 'text-center mt-2 text-sm text-red-600';
            }
        });
    </script>
@endsection
