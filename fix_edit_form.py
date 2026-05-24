import re

file_path = 'c:/xampp/htdocs/graduation project/resources/views/admin/permissions/edit.blade.php'

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Wrap the form around the whole content area
# Find the first <div class="px-12 py-4 "> (starts the content blocks)
# Actually, the 'إلغاء الأمر' button is currently in a form. Let's make it a normal link or keep it outside the main form.
content = content.replace('<form>', '')
content = content.replace('</form>', '')
content = content.replace(
    '<button\n                class="cursor-pointer text-[16px] red-shadow flex justify-center bg-[#F4F7F9] text-[#D92D20] py-2.5 px-10 rounded-[12px]">\n                إلغاء الأمر\n            </button>',
    '<a href="{{ route(\'admin.permissions.index\') }}"\n                class="cursor-pointer text-[16px] red-shadow flex justify-center bg-[#F4F7F9] text-[#D92D20] py-2.5 px-10 rounded-[12px]">\n                إلغاء الأمر\n            </a>'
)

# Insert <form> before the first px-12 py-4 block
form_start = '<form id="permissions-form" method="POST" action="{{ route(\'admin.permissions.approve\', $user->id) }}">\n    @csrf\n'
content = content.replace('<div class="px-12 py-4 ">', form_start + '    <div class="px-12 py-4 ">', 1)

# Append </form> at the end, right before the script tag
content = content.replace('<script src="{{ asset(\'JS/grantaccess.js\') }}"></script>', '</form>\n\n    <script src="{{ asset(\'JS/grantaccess.js\') }}"></script>')

# 2. Fix the inputs for Name, Email, National ID
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

# 3. Add hidden input for role_name inside the dropDown section
hidden_input = '<input type="hidden" name="role_name" id="role_name" value="{{ old(\'role_name\', $user->role->name ?? \'\') }}">\n                    '
content = content.replace('<label\n                        class="absolute bg-[#F4F7F9] text-[#124375] text-[16px] font-medium top-[-15px] right-4 px-1">الصفة', hidden_input + '<label\n                        class="absolute bg-[#F4F7F9] text-[#124375] text-[16px] font-medium top-[-15px] right-4 px-1">الصفة')

# Also, set the default text of the dropdown button to the current role name if it exists
content = content.replace(
    '<span\n                            class="text-[#021219] text-center flex-1">اختر</span>',
    '<span\n                            class="text-[#021219] text-center flex-1">{{ old(\'role_name\', $user->role->name ?? \'اختر\') }}</span>'
)

# 4. Fix faculties checkboxes
# We want to replace value="يناير 2026" with value="اسم الكلية" and add name="faculties[]"
# We also want to check it if it's in the user's faculties array
def faculty_replacer(match):
    full_match = match.group(0)
    faculty_name = match.group(1).strip()
    # Replace value="..." with value="faculty_name" and add name="faculties[]"
    new_input = f'<input type="checkbox" name="faculties[]" class="hidden peer item" value="{faculty_name}" {{ in_array("{faculty_name}", old("faculties", $user->faculties ?? [])) ? "checked" : "" }}>'
    # return the modified block
    return re.sub(r'<input type="checkbox" class="hidden peer item" value="[^"]*">', new_input, full_match)

faculty_pattern = re.compile(r'<label\s+class="flex items-center gap-2 cursor-pointer navy-shadow py-5 px-4 rounded-\[8px\] border-2 border-transparent has-\[:checked\]:border-\[#124375\]">.*?<span[^>]*>(.*?)</span>\s*</label>', re.DOTALL)
content = faculty_pattern.sub(faculty_replacer, content)

# 5. Fix permissions checkboxes
# Similar pattern, but the text is inside `<span class="text-[#021219] text-[18px] font-medium">اسم الصلاحية</span>`
def permission_replacer(match):
    full_match = match.group(0)
    permission_name = match.group(1).strip()
    new_input = f'<input type="checkbox" name="permissions[]" class="hidden peer item" value="{permission_name}" {{ in_array("{permission_name}", old("permissions", $user->custom_permissions ?? (is_array(optional($user->role)->permissions) ? array_keys($user->role->permissions) : []))) ? "checked" : "" }}>'
    return re.sub(r'<input type="checkbox" class="hidden peer item" value="[^"]*">', new_input, full_match)

permission_pattern = re.compile(r'<label\s+class="flex justify-between cursor-pointer navy-shadow py-5 px-4 rounded-\[8px\] border-2 border-transparent has-\[:checked\]:border-\[#124375\]">.*?<span class="text-\[#021219\] text-\[18px\] font-medium">(.*?)</span>', re.DOTALL)
content = permission_pattern.sub(permission_replacer, content)

# Remove the dummy form from the submit button
content = content.replace('<form class="px-12 py-4 ">', '<div class="px-12 py-4 ">')
# we already appended </form> at the end of the file, wait, replace '</form>' might have deleted it!
# Yes, `content.replace('</form>', '')` earlier deleted all </form>!
# So we need to put the closing tag back for the main form.
# Instead of replacing all </form>, we should be precise.
# Let's write the file.

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("HTML template successfully updated!")
