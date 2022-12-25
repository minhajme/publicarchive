import glob

for txtfile in glob.glob('*.txt'):
    with open(txtfile) as file:
        lines = file.readlines()
        lines[-1] = lines[-1].rstrip("\r\n")
    with open(txtfile, 'w') as file:
        file.writelines(lines)
