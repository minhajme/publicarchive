s = input(':')
sub = ''
for i in range(len(s)):
    if i == 0:
        continue
    if s[i] >= s[i - 1]:
        if not sub.endswith(s[i - 1]):
            sub += s[i - 1]
        sub += s[i]
    else:
        sub += ';'
sub_ar = [e for e in sub.split(';') if e]
# print(sub_ar)
longest = ''
if not len(sub_ar):  # if no serial found, just find the highest character
    for char in s:
        if char > longest:
            longest = char
else:
    for i in range(len(sub_ar)):
        if len(sub_ar[i]) > len(longest):
            longest = sub_ar[i]

print("Longest substring in alphabetical order is:", longest)
