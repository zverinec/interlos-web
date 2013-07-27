#include<stdio.h>
#define DELKA 9

int prvni_pole[DELKA], druhe_pole[DELKA];

void swap(int k, int l){
    int temp;
    temp = prvni_pole[k];
    prvni_pole[k]= prvni_pole[l];
    prvni_pole[l] = temp;
    temp = druhe_pole[k];
    druhe_pole[k] = druhe_pole[l];
    druhe_pole[l] = temp;
}

int main(){
    int i,j;
    char kod[42];
    scanf("%s\n", kod);
    for (i=0; i<DELKA; i++){
        prvni_pole[i] = kod[i]-'a'+1; 
        druhe_pole[i] = i;
    }

    for (i=0; i<DELKA; i++)
        for (j=0; j<DELKA-1; j++)
            if (prvni_pole[j] > prvni_pole[j+1])
                swap(j, j+1);

    for (i=DELKA-1; i>0; i--){
        prvni_pole[i] -= prvni_pole[i-1];
    }

    for (i=0; i<42; i++){
        swap(i%DELKA, 0);
    }

	for (i=1; i<DELKA; i++){
        prvni_pole[i] += prvni_pole[i-1];
    }
	
    for (i=0; i<DELKA; i++)
        for (j=0; j<DELKA-1; j++)
            if (druhe_pole[j] > druhe_pole[j+1])
                swap(j, j+1);

    for (i=0; i<DELKA; i++){
        printf("%c", 'a'-1+prvni_pole[i]);
    }
    printf("\n");

    return 0;
}

