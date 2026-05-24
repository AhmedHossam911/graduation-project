import re

file_path = 'c:/xampp/htdocs/graduation project/resources/views/admin/permissions/edit.blade.php'

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Add type="button" to select-btn
content = content.replace(
    '<button\n                        class="select-btn',
    '<button type="button"\n                        class="select-btn'
)
content = content.replace(
    '<button class="select-btn',
    '<button type="button" class="select-btn'
)

# 2. Replace the hardcoded faculties with a dynamic loop
# We can find the grid container for faculties which starts with:
# <div class="grid grid-cols-4 gap-x-12 gap-y-7 mt-8">
# and ends right before:
# <div class="px-12 py-4 mt-7"> (the permissions section)

# Let's extract the first grid which is for faculties.
start_idx = content.find('<div class="grid grid-cols-4 gap-x-12 gap-y-7 mt-8">')

# The end of this grid is before the next section
next_section_idx = content.find('<div class="px-12 py-4 mt-7">', start_idx)

# We want to keep the `<div class="grid grid-cols-4 gap-x-12 gap-y-7 mt-8">`
# and replace its content until `next_section_idx` (excluding the closing </div> of the grid, or just replace the inner contents)

# Actually, the grid closes at `</div>` before `<div class="px-12 py-4 mt-7">`.
# The content between start_idx and next_section_idx looks like:
# <div class="grid grid-cols-4 gap-x-12 gap-y-7 mt-8">
#    <label ...> ... </label>
#    ... (25 labels)
# </div>
# </div> (closes the bg-[#F4F7F9] container)
# 
# Let's use regex to grab the block of labels.
# A label starts with <label class="flex items-center gap-2 cursor-pointer navy-shadow py-5 px-4 rounded-[8px] border-2 border-transparent has-[:checked]:border-[#124375]">
# and ends with </label>.
# We will just replace all consecutive labels with the foreach loop.

labels_pattern = r'(<label\s+class="flex items-center gap-2 cursor-pointer navy-shadow py-5 px-4 rounded-\[8px\] border-2 border-transparent has-\[:checked\]:border-\[#124375\]">.*?</label>\s*)+'

loop_template = """@foreach($departments as $department)
                    <label
                        class="flex items-center gap-2 cursor-pointer navy-shadow py-5 px-4 rounded-[8px] border-2 border-transparent has-[:checked]:border-[#124375]">
                        <input type="checkbox" name="faculties[]" class="hidden peer item" value="{{ $department->name }}" {{ in_array($department->name, old('faculties', $user->faculties ?? [])) ? 'checked' : '' }}>
                        <span
                            class="custom-checkbox flex items-center justify-center h-[22px] w-[22px] rounded-sm border-[3px] border-[#124375] peer-checked:bg-[#124375] peer-checked:border-[#124375] text-transparent peer-checked:text-white transition-all duration-200">
                            <iconify-icon icon="mdi:check-bold" class="text-[14px]"></iconify-icon>
                        </span>
                        <span>{{ $department->name }}</span>
                    </label>
                    @endforeach
                    """

content = re.sub(labels_pattern, loop_template, content, count=1, flags=re.DOTALL)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Edit buttons and faculties loop updated!")
