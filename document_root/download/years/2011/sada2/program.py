
DELKA = 9
prvni_pole = [0]*DELKA
druhe_pole = [0]*DELKA

def swap(k, l):
	temp = prvni_pole[k];
	prvni_pole[k]= prvni_pole[l];
	prvni_pole[l] = temp;
	temp = druhe_pole[k];
	druhe_pole[k] = druhe_pole[l];
	druhe_pole[l] = temp;


kod = raw_input("")

for i in range(DELKA):
	prvni_pole[i] = ord(kod[i])-ord('a')+1; 
	druhe_pole[i] = i;

for i in range(DELKA):
	for j in range(DELKA-1):
		if prvni_pole[j] > prvni_pole[j+1]:
			swap(j, j+1);

for i in range(DELKA-1,0,-1):
	prvni_pole[i] -= prvni_pole[i-1];

for i in range(42):
	swap(i%DELKA, 0);

for i in range(1,DELKA):
	prvni_pole[i] += prvni_pole[i-1];

for i in range(DELKA):
	for j in range(DELKA-1):
		if druhe_pole[j] > druhe_pole[j+1]:
			swap(j, j+1);

for i in range(DELKA):
	print chr(ord('a')-1+prvni_pole[i]),

print
