#include <iostream>
#include <fstream>
#include <sstream>
#include <algorithm>
#include <vector>

using namespace std;

//dimenzia pola
#define DIM 100
//textove pole
int array[DIM][DIM][DIM];

//struktura znaku hesla s jeho hodnotou palindromov
typedef struct codeChar{
	char a; //stred palindromu
	int value; //dlzka palindromov
}codeChar;

void load(const char* filaname);
bool sortFuncCode(codeChar i, codeChar j);
vector<codeChar> findPalindrom();
int checkNeighbors(int x,int y,int z);


int main(int argc, char** argv) {

	vector<codeChar> code;

	//nacita subor (cestu k suboru obdrzime z parametru)
	if(argc != 2)
		return 1;
	load(argv[1]);

	//najde vsetky pozicie v texte kde sa krizia 3 palindromy
	code = findPalindrom();

	//usporiadame znaky podla dlzky palindromov
	sort(code.begin(), code.end(), sortFuncCode);

	//vypis hesla:
	cout << "PASSWORD: ";
	for(std::vector<codeChar>::iterator it = code.begin(); it < code.end(); it++)
	{
		cout << (*it).a;
	}

	cout << endl;
	return 0;
}

//usporiada prvky hesla podla velkosti palindromov
bool sortFuncCode(codeChar i, codeChar j)
{
	return (i.value < j.value);
}

//najde miesta s 3 palindromi a vrati zoznam znakov hesla
vector<codeChar> findPalindrom()
{
	//inicializujem pole pre najdene znaky
	vector<codeChar> code;
	//prejdem cely 3d text
	for(int i = 0; i < DIM; i++ ){
		for(int j = 0; j < DIM; j++){
			for(int k = 0; k < DIM; k++){
				//pozrem vsetky smery od bodu [i,j,k] a vratim dlzku vsetkych palindromov
				//ak nenajdem palindrom v niektorom zo smervo vratim -1
				int pal = checkNeighbors(i,j,k);
				//ak som nasiel palindromy ulozim znak
				if(pal != -1)
				{
					codeChar palinChar;
					palinChar.a = array[i][j][k];
					palinChar.value = pal;
					code.push_back(palinChar);
				}
			}
		}
	}
	//vratim neusporiadane znaky hesla
	return code;
}

//kontroluje palindromy vo vsetkych osiach x, y, z
int checkNeighbors(int x,int y,int z)
{
	//inicializujem dlzku najdenych palindromov
	int count = 0;

	int n = 1;
	//krokujem od stredu a porovnavam znaky kym sa rovnaju
	//ak sa dostanem na koniec pola modulom sa dostanem na opacnu stranu
	while(array[(x + n) % DIM][y][z] == array[(x + DIM - n) % DIM][y][z]){
		n++;
	}
	//ak som nenasiel jediny zhodny znak
	//tak som nenasiel palindrom a vratim -1
	if(n == 1)
		return -1;
	else
	//inak dopocitam dlzku palindromu
		count += 2*(n - 1) + 1;

	n = 1;
	//to iste pre y-os
	while(array[x][(y + n) % DIM][z] == array[x][(y + DIM - n) % DIM][z]){
		n++;
	}
	if(n == 1)
		return -1;
	else
		count += 2*(n - 1) + 1;

	n = 1;
	//to iste pre z-os
	while(array[x][y][(z + n) % DIM] == array[x][y][(z + DIM - n) % DIM]){
		n++;
	}
	if(n == 1)
		return -1;
	else
		count += 2*(n - 1) + 1;

	//ak som nasiel vsade palindromy vratim ich sucet dlzok
	return count;
}

//nacita text zo suboru
void load(const char* filename) {
	std::ifstream input;
	input.open(filename);
	std::string line;
	//nacitaj vrstvu
	for(int i = 0; i < DIM; i++) {
		//nacitaj riadok
		for(int j = 0; j < DIM; j++) {
			std::getline(input,line);
			std::istringstream iss(line);
			std::string character;
			//nacitaj znak
			for(int k = 0; k < DIM; k++) {
				  getline(iss, character, ' ');
				  char a = (character.c_str())[0];
				  array[i][j][k] = a;
			}
		}
		std::getline(input,line);
	}
	input.close();
}
