import re

file_path = 'c:/xampp/htdocs/graduation project/resources/views/admin/permissions/edit.blade.php'

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Fix the value attribute for faculties
def fix_faculty(match):
    full_match = match.group(0)
    # find all <span>...</span>
    spans = re.findall(r'<span>(.*?)</span>', full_match, re.DOTALL)
    if spans:
        faculty_name = spans[-1].strip()
        # replace the value inside the input
        new_input = f'<input type="checkbox" name="faculties[]" class="hidden peer item" value="{faculty_name}" {{ in_array("{faculty_name}", old("faculties", $user->faculties ?? [])) ? "checked" : "" }}>'
        # we need to replace the badly formatted input from earlier
        # the previous script put value="<iconify-icon..."
        # we can just use re.sub to replace the whole input line
        return re.sub(r'<input type="checkbox" name="faculties\[\]" class="hidden peer item" value="[^"]*">', new_input, full_match)
    return full_match

faculty_pattern = re.compile(r'<label\s+class="flex items-center gap-2 cursor-pointer navy-shadow py-5 px-4 rounded-\[8px\] border-2 border-transparent has-\[:checked\]:border-\[#124375\]">.*?</label>', re.DOTALL)
content = faculty_pattern.sub(fix_faculty, content)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("HTML template successfully updated!")
