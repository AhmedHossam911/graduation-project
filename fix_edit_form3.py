import re

file_path = 'c:/xampp/htdocs/graduation project/resources/views/admin/permissions/edit.blade.php'

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

def fix_faculty(match):
    full_match = match.group(0)
    # find all <span>...</span>
    spans = re.findall(r'<span>(.*?)</span>', full_match, re.DOTALL)
    if spans:
        faculty_name = spans[-1].strip()
        # we know the input looks like <input type="checkbox" name="faculties[]" class="hidden peer item" value="<iconify-icon...">
        # We can just replace everything from <input up to class="hidden peer item" value="...">
        # A simpler way is to regex the whole input tag
        new_input = f'<input type="checkbox" name="faculties[]" class="hidden peer item" value="{faculty_name}" {{ in_array("{faculty_name}", old("faculties", $user->faculties ?? [])) ? "checked" : "" }}>'
        
        # replace the <input ...> tag entirely
        return re.sub(r'<input type="checkbox"[^>]*>', new_input, full_match, count=1)
    return full_match

faculty_pattern = re.compile(r'<label\s+class="flex items-center gap-2 cursor-pointer navy-shadow py-5 px-4 rounded-\[8px\] border-2 border-transparent has-\[:checked\]:border-\[#124375\]">.*?</label>', re.DOTALL)
content = faculty_pattern.sub(fix_faculty, content)

# Check permissions checkboxes as well
def fix_permission(match):
    full_match = match.group(0)
    name_match = re.search(r'<span class="text-\[#021219\] text-\[18px\] font-medium">(.*?)</span>', full_match, re.DOTALL)
    if name_match:
        permission_name = name_match.group(1).strip()
        new_input = f'<input type="checkbox" name="permissions[]" class="hidden peer item" value="{permission_name}" {{ in_array("{permission_name}", old("permissions", $user->custom_permissions ?? (is_array(optional($user->role)->permissions) ? array_keys($user->role->permissions) : []))) ? "checked" : "" }}>'
        return re.sub(r'<input type="checkbox"[^>]*>', new_input, full_match, count=1)
    return full_match

permission_pattern = re.compile(r'<label\s+class="flex justify-between cursor-pointer navy-shadow py-5 px-4 rounded-\[8px\] border-2 border-transparent has-\[:checked\]:border-\[#124375\]">.*?</label>', re.DOTALL)
content = permission_pattern.sub(fix_permission, content)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Fixed!")
