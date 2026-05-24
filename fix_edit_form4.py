import re

file_path = 'c:/xampp/htdocs/graduation project/resources/views/admin/permissions/edit.blade.php'

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Fix faculties
content = re.sub(
    r'<input type="checkbox" name="faculties\[\]" class="hidden peer item" value="(.*?)"[^>]*>',
    r'<input type="checkbox" name="faculties[]" class="hidden peer item" value="\1" {{ in_array("\1", old("faculties", $user->faculties ?? [])) ? "checked" : "" }}>',
    content
)

# Fix permissions
content = re.sub(
    r'<input type="checkbox" name="permissions\[\]" class="hidden peer item" value="(.*?)"[^>]*>',
    r'<input type="checkbox" name="permissions[]" class="hidden peer item" value="\1" {{ in_array("\1", old("permissions", $user->custom_permissions ?? (is_array(optional($user->role)->permissions) ? array_keys($user->role->permissions) : []))) ? "checked" : "" }}>',
    content
)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Checkboxes rewritten properly!")
