#include <iostream>
#include <string>
#include <stack>
#include <vector>
#include <assert.h>
#include <fstream>

using namespace std;

//struktura vektoru o 4 slo�k�ch
struct vec { int x,y,z,w; };


void go(vec l);
int scan();
void init();
void load();
void save();


//parametry bludi�t�
const vec start = {0,1,1,1};
const int N = 10;
char maze[N][N][N][N];

//pole pro ukl�d�n� tajenky
vector<string> pass;

//z�sobn�k pro prohled�v�n� do hloubky
vec pos = {0,0,0,0};
stack<vec> lvls;


int main(int argc, char** argv) {
    //inicialiace prom�nn�ch
    pass.emplace_back();
    pos = start;

    //na�teme soubor (cestu k souboru obdr��me z parametru)
    if(argc != 2)
        return 1;
    load(argv[1]);

    //ozna��me na�i startovac� pozici jako nav�t�venou
    maze[start.x][start.y][start.z][start.w] = '2';

    //za�n�me bloudit!
    //naskenujeme si okol� do vzd�lenosti 1, pokud existuje jen jedna cesta
    //na kter� jsme nebyli, tak jdem n�. Pokud existuje v�ce nenav�t�ven�ch
    //cest tak si n�hodn� vybereme a zbytek ulo��me do z�sobn�ku. Pokud
    //neexistuje ��dn� cesta anebo jsou v�echny nav�t�ven� tak vybereme prvn�
    //cestu ze z�sobn�ku a "teleportujeme" se tam (��st tajenky od posledn�
    //k�i�ovatky zahod�me). Ka�d� pole na kter� se pohneme, tak ozna��me za
    //nav�t�ven�.
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


    //vyp�eme tajenku
    cout << "PASSWORD: ";
    for(auto i = pass.cbegin(); i != pass.cend(); i++)
        cout << *i;

    cin.get();

    return 0;
}

//pohneme se o jedno pole, nastav�me p��znak v bludi�ti �e jsme pole nav�t�vili a p�e�teme p�smeno do tajenky
void go(vec l) {
    assert(maze[l.x][l.y][l.z][l.w] != '0' && maze[l.x][l.y][l.z][l.w] != '2');
    if(maze[l.x][l.y][l.z][l.w] != '1')
        pass.back().append(1, maze[l.x][l.y][l.z][l.w]);

    maze[l.x][l.y][l.z][l.w] = '2';
    pos = l;
}

//fce zjist�, jestli z na�i pozice m��eme j�t na pole, kter� jsme je�t� nenav�t�vili
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


//na�teme blido�t� ze souboru
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
