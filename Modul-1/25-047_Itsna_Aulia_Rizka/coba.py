nama = [9, 7, 8]



for i in range(5):
    nama.append(i)
    jumlah = len(nama)


for j in range(jumlah):
    print(nama[j])
    for k in range(jumlah):
        print(k, end="")
    print()

if True:
    if True:
        print(jumlah, " ini ini")