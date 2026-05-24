import re

file_path = 'c:/xampp/htdocs/graduation project/resources/views/admin/permissions/edit.blade.php'

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Fix the `{ in_array... }>` to `{{ in_array... }}>`
content = re.sub(r'\{ in_array\("(.*?)", old\("(.*?)", (.*?) \?\? \[\]\)\) \? "checked" : "" \}', r'{{ in_array("\1", old("\2", \3 ?? [])) ? "checked" : "" }}', content)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Braces fixed!")
