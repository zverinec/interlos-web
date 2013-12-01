#include <iostream>
#include <string>
#include <stack>
#include <vector>
#include <assert.h>
#include <fstream>

using namespace std;

//struktura vektoru o 4 složkách
struct vec { int x,y,z,w; };


void go(vec l);
int scan();
void init();
void load();
void save();


//parametry bludištì
const vec start = {0,1,1,1};
const int N = 10;
char maze[N][N][N][N];

//pole pro ukládání tajenky
vector<string> pass;

//zásobník pro prohledávání do hloubky
vec pos = {0,0,0,0};
stack<vec> lvls;


int main(int argc, char** argv) {
	//inicialiace promìnných
	pass.emplace_back();
	pos = start;

	//naèteme soubor (cestu k souboru obdržíme z parametru)
	if(argc != 2)
		return 1;
	load(argv[1]);

	//oznaèíme naši startovací pozici jako navštívenou
	maze[start.x][start.y][start.z][start.w] = '2';

	//zaènìme bloudit!
	//naskenujeme si okolí do vzdálenosti 1, pokud existuje jen jedna cesta
	//na které jsme nebyli, tak jdem ní. Pokud existuje více nenavštívených
	//cest tak si náhodnì vybereme a zbytek uložíme do zásobníku. Pokud
	//neexistuje žádná cesta anebo jsou všechny navštívené tak vybereme první
	//cestu ze zásobníku a "teleportujeme" se tam (èást tajenky od poslední
	//køižovatky zahodíme). Každé pole na které se pohneme, tak oznaèíme za
	//navštívené.
	do {
		int a = scan();
		if(a == 0) {
			pass.pop_back();
		}
		for(int i = 1; i < a; i++)
			pass.emplace_back();
		go(lvls.top());
		lvls.pop();
	} while (
		0 < pos.x && pos.x < N-1 &&
		0 < pos.y && pos.y < N-1 &&
		0 < pos.z && pos.z < N-1 &&
		0 < pos.w && pos.w < N-1
	);


	//vypíšeme tajenku
	cout << "PASSWORD: ";
	for(auto i = pass.cbegin(); i != pass.cend(); i++)
		cout << *i;

	cin.get();

	return 0;
}

//pohneme se o jedno pole, nastavíme pøíznak v bludišti že jsme pole navštívili a pøeèteme písmeno do tajenky
void go(vec l) {
	assert(maze[l.x][l.y][l.z][l.w] != '0' && maze[l.x][l.y][l.z][l.w] != '2');
	if(maze[l.x][l.y][l.z][l.w] != '1')
		pass.back().append(1, maze[l.x][l.y][l.z][l.w]);

	maze[l.x][l.y][l.z][l.w] = '2';
	pos = l;
}

//fce zjistí, jestli z naši pozice mùžeme jít na pole, které jsme ještì nenavštívili
int scan() {
	int size = lvls.size();

	vec l = pos;
	l.x++;
	if(0 <= l.x && l.x <= N && maze[l.x][l.y][l.z][l.w] != '0' && maze[l.x][l.y][l.z][l.w] != '2')
		lvls.push(l);

	l = pos;
	l.x--;
	if(0 <= l.x && l.x <= N && maze[l.x][l.y][l.z][l.w] != '0' && maze[l.x][l.y][l.z][l.w] != '2')
		lvls.push(l);

	l = pos;
	l.y++;
	if(0 <= l.y && l.y <= N && maze[l.x][l.y][l.z][l.w] != '0' && maze[l.x][l.y][l.z][l.w] != '2')
		lvls.push(l);

	l = pos;
	l.y--;
	if(0 <= l.y && l.y <= N && maze[l.x][l.y][l.z][l.w] != '0' && maze[l.x][l.y][l.z][l.w] != '2')
		lvls.push(l);

	l = pos;
	l.z++;
	if(0 <= l.z && l.z <= N && maze[l.x][l.y][l.z][l.w] != '0' && maze[l.x][l.y][l.z][l.w] != '2')
		lvls.push(l);

	l = pos;
	l.z--;
	if(0 <= l.z && l.z <= N && maze[l.x][l.y][l.z][l.w] != '0' && maze[l.x][l.y][l.z][l.w] != '2')
		lvls.push(l);

	l = pos;
	l.w++;
	if(0 <= l.w && l.w <= N && maze[l.x][l.y][l.z][l.w] != '0' && maze[l.x][l.y][l.z][l.w] != '2')
		lvls.push(l);

	l = pos;
	l.w--;
	if(0 <= l.w && l.w <= N && maze[l.x][l.y][l.z][l.w] != '0' && maze[l.x][l.y][l.z][l.w] != '2')
		lvls.push(l);

	return lvls.size() - size;
}


//naèteme blidoštì ze souboru
void load(const char* name) {
	ifstream fin(name);


	for(int w = 0; w < N; w++) {
		for(int z = 0; z < N; z++) {
			for(int y = 0; y < N; y++) {
				for(int x = 0; x < N; x++) {
					fin >> maze[x][y][z][w];
				}
			}
		}
	}
}
