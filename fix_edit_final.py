import re

file_path = 'c:/xampp/htdocs/graduation project/resources/views/admin/permissions/edit.blade.php'

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Wrap form
content = content.replace('<form>', '')
content = content.replace('</form>', '')
content = content.replace(
    '<button\n                class="cursor-pointer text-[16px] red-shadow flex justify-center bg-[#F4F7F9] text-[#D92D20] py-2.5 px-10 rounded-[12px]">\n                إلغاء الأمر\n            </button>',
    '<a href="{{ route(\'admin.permissions.index\') }}"\n                class="cursor-pointer text-[16px] red-shadow flex justify-center bg-[#F4F7F9] text-[#D92D20] py-2.5 px-10 rounded-[12px]">\n                إلغاء الأمر\n            </a>'
)

form_start = '<form id="permissions-form" method="POST" action="{{ route(\'admin.permissions.approve\', $user->id) }}">\n    @csrf\n'
content = content.replace('<div class="px-12 py-4 ">', form_start + '    <div class="px-12 py-4 ">', 1)
content = content.replace('<script src="{{ asset(\'JS/grantaccess.js\') }}"></script>', '</form>\n\n    <script src="{{ asset(\'JS/grantaccess.js\') }}"></script>')

# 2. Fix inputs
content = content.replace(
    '<input type="text" placeholder="الاسم المدرج بقرار التعيين"',
    '<input type="text" name="name" value="{{ old(\'name\', $user->name) }}" placeholder="الاسم المدرج بقرار التعيين"'
)
content = content.replace(
    '<input type="email" placeholder="example@helwan.edu.eg"',
    '<input type="email" name="email" value="{{ old(\'email\', $user->email) }}" placeholder="example@helwan.edu.eg"'
)
content = content.replace(
    '<input type="number" placeholder="12345678912345"',
    '<input type="number" name="national_id" value="{{ old(\'national_id\', $user->member->national_id ?? \'\') }}" placeholder="12345678912345"'
)

# 3. Add hidden input for role
hidden_input = '<input type="hidden" name="role_name" id="role_name" value="{{ old(\'role_name\', $user->role->name ?? \'\') }}">\n                    '
content = content.replace('<label\n                        class="absolute bg-[#F4F7F9] text-[#124375] text-[16px] font-medium top-[-15px] right-4 px-1">الصفة', hidden_input + '<label\n                        class="absolute bg-[#F4F7F9] text-[#124375] text-[16px] font-medium top-[-15px] right-4 px-1">الصفة')

content = content.replace(
    '<span\n                            class="text-[#021219] text-center flex-1">اختر</span>',
    '<span\n                            class="text-[#021219] text-center flex-1">{{ old(\'role_name\', $user->role->name ?? \'اختر\') }}</span>'
)

# 4. Fix checkboxes explicitly using regex that finds the inner text
def fix_faculty(match):
    full_match = match.group(0)
    spans = re.findall(r'<span>(.*?)</span>', full_match, re.DOTALL)
    if spans:
        name = spans[-1].strip()
        new_input = f'<input type="checkbox" name="faculties[]" class="hidden peer item" value="{name}" {{{{ in_array("{name}", old("faculties", $user->faculties ?? [])) ? "checked" : "" }}}}>'
        return re.sub(r'<input type="checkbox"[^>]*>', new_input, full_match, count=1)
    return full_match

content = re.sub(r'<label\s+class="flex items-center gap-2 cursor-pointer navy-shadow py-5 px-4 rounded-\[8px\] border-2 border-transparent has-\[:checked\]:border-\[#124375\]">.*?</label>', fix_faculty, content, flags=re.DOTALL)

def fix_permission(match):
    full_match = match.group(0)
    name_match = re.search(r'<span class="text-\[#021219\] text-\[18px\] font-medium">(.*?)</span>', full_match, re.DOTALL)
    if name_match:
        name = name_match.group(1).strip()
        new_input = f'<input type="checkbox" name="permissions[]" class="hidden peer item" value="{name}" {{{{ in_array("{name}", old("permissions", $user->custom_permissions ?? (is_array(optional($user->role)->permissions) ? array_keys($user->role->permissions) : []))) ? "checked" : "" }}}}>'
        return re.sub(r'<input type="checkbox"[^>]*>', new_input, full_match, count=1)
    return full_match

content = re.sub(r'<label\s+class="flex justify-between cursor-pointer navy-shadow py-5 px-4 rounded-\[8px\] border-2 border-transparent has-\[:checked\]:border-\[#124375\]">.*?</label>', fix_permission, content, flags=re.DOTALL)

content = content.replace('<form class="px-12 py-4 ">', '<div class="px-12 py-4 ">')

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Done!")
