import re
file_path = 'c:/xampp/htdocs/graduation project/resources/views/admin/permissions/edit.blade.php'
with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

content = re.sub(r'<button([^>]*?)class="select-btn', r'<button type="button"\1class="select-btn', content)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)
print("Buttons fixed!")
