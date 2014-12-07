/**
 * () Kamil Kamila
 * [] Alexandr Alexandra
 * {} Jan Jana
 * <> Jiri Jirina
 * \/ Pavel Pavlina
 */

#include <iostream>
#include <stack>
#include <Qtime>
#include <string>
#include <vector>
#include <stdio.h>

//encode (input generator)
/*int main()
{
    FILE *f;
    f = fopen("D://out.txt","w");

    for(int rep=0; rep<104; rep++)
    {
        std::vector<std::string> jmenaA;
        std::vector<std::string> jmenaB;
        jmenaA.push_back("kamil");
        jmenaA.push_back("alexandr");
        jmenaA.push_back("jan");
        jmenaA.push_back("jiri");
        jmenaA.push_back("pavel");

        jmenaB.push_back("kamila");
        jmenaB.push_back("alexandra");
        jmenaB.push_back("jana");
        jmenaB.push_back("jirina");
        jmenaB.push_back("pavlina");

        std::stack<int> zas;
        int a = qrand()%5;
        zas.push(a);
        //std::cout << jmenaA.at(a);
        fprintf(f, "%s", jmenaA.at(a).c_str());

        for(int i=0;i<4000;i++)
        {
            if(qrand()%2 || zas.empty())
            {
                a = qrand()%4;
                zas.push(a);
                //std::cout << jmenaA.at(a);
                fprintf(f, "%s", jmenaA.at(a).c_str());
            }
            else
            {
                //std::cout << jmenaB.at(zas.top());
                fprintf(f, "%s", jmenaB.at(zas.top()).c_str());
                zas.pop();
            }
        }

        while(!zas.empty())
        {
            //std::cout << jmenaB.at(zas.top());
            fprintf(f, "%s", jmenaB.at(zas.top()).c_str());
            zas.pop();
        }

        std::cout << std::endl << std:: endl << std::endl << "konec" << std::endl;
        fprintf(f, "\n");
    }

    fclose(f);

    return 0;
}*/

//solution begins
int const PAVEL    = 1;
int const JIRI     = 2;
int const JAN      = 3;
int const KAMIL    = 4;
int const ALEXANDR = 5;

int const PAVLINA   = 6;
int const JIRINA    = 7;
int const JANA      = 8;
int const KAMILA    = 9;
int const ALEXANDRA = 10;

int getName(FILE *f)
{
    char aa = getc(f);
    if(aa == 'p')
    {
        if( getc(f) != 'a' ) return 0;
        if( getc(f) != 'v' ) return 0;
        char bb = getc(f);
        if( bb == 'e' )
        {
            if( getc(f) != 'l' ) return 0;
            return PAVEL;
        }
        else if(bb == 'l')
        {
            if( getc(f) != 'i' ) return 0;
            if( getc(f) != 'n' ) return 0;
            if( getc(f) != 'a' ) return 0;
            return PAVLINA;
        }
        else return 0;
    }
    else if(aa == 'j')
    {
        char bb = getc(f);
        if( bb == 'i' )
        {
            if( getc(f) != 'r' ) return 0;
            if( getc(f) != 'i' ) return 0;
            char bb = getc(f);
            if( bb == 'n' )
            {
                if(getc(f) != 'a') return 0;
                return JIRINA;
            }
            else
            {
                fseek (f, -1, SEEK_CUR);
                return JIRI;
            }
        }
        else if(bb == 'a')
        {
            if(getc(f) != 'n') return 0;
            char cc = getc(f);
            char dd = getc(f);
            if(cc == 'a' && dd != 'l')
            {
                fseek(f, -1, SEEK_CUR);
                return JANA;
            }
            else
            {
                fseek(f, -2, SEEK_CUR);
                return JAN;
            }
        }
        else return 0;
    }
    else if(aa == 'k')
    {
        if(getc(f) != 'a') return 0;
        if(getc(f) != 'm') return 0;
        if(getc(f) != 'i') return 0;
        if(getc(f) != 'l') return 0;
        char bb = getc(f);
        char cc = getc(f);
        if(bb == 'a' && cc != 'l')
        {
            fseek(f, -1, SEEK_CUR);
            return KAMILA;
        }
        else
        {
            fseek(f, -2, SEEK_CUR);
            return KAMIL;
        }
    }
    else if(aa == 'a')
    {
        if(getc(f) != 'l') return 0;
        if(getc(f) != 'e') return 0;
        if(getc(f) != 'x') return 0;
        if(getc(f) != 'a') return 0;
        if(getc(f) != 'n') return 0;
        if(getc(f) != 'd') return 0;
        if(getc(f) != 'r') return 0;
        char bb = getc(f);
        char cc = getc(f);
        if(bb == 'a' && cc != 'l')
        {
            fseek(f, -1, SEEK_CUR);
            return ALEXANDRA;
        }
        else
        {
            fseek(f, -2, SEEK_CUR);
            return ALEXANDR;
        }
    }
    else return 0;
}

bool convertLine(FILE *f)
{
    std::stack<int> zavorky;

    while(getc(f) != '\n')
    {
        fseek(f, -1, SEEK_CUR);
        int name = getName(f);
        if(name == 0)
        {
            return false;
        }
        else
        {
            if(name <= 5)
            {
                zavorky.push(name);
            }
            else if(!zavorky.empty() && zavorky.top() == name-5)
            {
                zavorky.pop();
            }
            else return false;
        }
    }
    return zavorky.empty();
}

int main()
{
    FILE *f;
    f = fopen("D://in.txt", "r");

    bool success = true;
    for(int i=0;i<104;i++)
    {
        success = convertLine(f);
        if(!success)
        {
            fseek(f, -1, SEEK_CUR);
            while(getc(f) != '\n');
        }
        std::cout << (int)success;
    }
    std::cout << std::endl << std::endl;

    fclose(f);
    return 0;
}
