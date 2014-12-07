#include <iostream>
#include <string>
#include <vector>
#include <QTime>
#include <stdio.h>

int const max_iterations = 5;

int pow(int n, int e)
{
    int out = 1;
    for(int i=0;i<e;i++)
    {
        out *= n;
    }
    return out;
}

// input generator
/*
//char in[1*pow(5,max_iterations)][11*pow(3,max_iterations)];
//char out[1*pow(5,max_iterations)][11*pow(3,max_iterations)];
char in[15625][8019];
char out[15625][8019];

int main()
{
    FILE *f;
    f = fopen("D://out.txt", "w");

    std::string temp;
    getline(std::cin, temp);
    int rows = 1;
    int cols = temp.size();

    //char in[rows*pow(3,max_iterations)][cols*pow(5,max_iterations)];
    //char out[rows*pow(3,max_iterations)][cols*pow(5,max_iterations)];

    for(int iterations=0; iterations<max_iterations; iterations++)
    {
        if(iterations==0)
        {
            for(int i=0;i<cols;i++)
            {
                in[0][i] = temp.at(i);
            }
        }
        else
        {
            for(int i=0;i<rows;i++)
            {
                for(int j=0;j<cols;j++)
                {
                    in[i][j] = out[i][j];
                }
            }
        }

        for(int h=0; h<rows; h++)
        {
            for(int i=0; i<cols; i++)
            {

                char p1 = 0;
                char p2 = 0;
                while(p1 == p2)
                {
                    p1 = qrand()%6+0x41;
                    p2 = qrand()%6+0x41;
                }
                if(in[h][i] == 'A')
                {
                    out[5*h+0][3*i+0] = p1;  out[5*h+0][3*i+1] = p2;  out[5*h+0][3*i+2] = p1;
                    out[5*h+1][3*i+0] = p2;  out[5*h+1][3*i+1] = p1;  out[5*h+1][3*i+2] = p2;
                    out[5*h+2][3*i+0] = p2;  out[5*h+2][3*i+1] = p2;  out[5*h+2][3*i+2] = p2;
                    out[5*h+3][3*i+0] = p2;  out[5*h+3][3*i+1] = p1;  out[5*h+3][3*i+2] = p2;
                    out[5*h+4][3*i+0] = p2;  out[5*h+4][3*i+1] = p1;  out[5*h+4][3*i+2] = p2;
                }
                if(in[h][i] == 'B')
                {
                    out[5*h+0][3*i+0] = p2;  out[5*h+0][3*i+1] = p2;  out[5*h+0][3*i+2] = p1;
                    out[5*h+1][3*i+0] = p2;  out[5*h+1][3*i+1] = p1;  out[5*h+1][3*i+2] = p2;
                    out[5*h+2][3*i+0] = p2;  out[5*h+2][3*i+1] = p2;  out[5*h+2][3*i+2] = p1;
                    out[5*h+3][3*i+0] = p2;  out[5*h+3][3*i+1] = p1;  out[5*h+3][3*i+2] = p2;
                    out[5*h+4][3*i+0] = p2;  out[5*h+4][3*i+1] = p2;  out[5*h+4][3*i+2] = p1;
                }
                if(in[h][i] == 'C')
                {
                    out[5*h+0][3*i+0] = p1;  out[5*h+0][3*i+1] = p2;  out[5*h+0][3*i+2] = p1;
                    out[5*h+1][3*i+0] = p2;  out[5*h+1][3*i+1] = p1;  out[5*h+1][3*i+2] = p2;
                    out[5*h+2][3*i+0] = p2;  out[5*h+2][3*i+1] = p1;  out[5*h+2][3*i+2] = p1;
                    out[5*h+3][3*i+0] = p2;  out[5*h+3][3*i+1] = p1;  out[5*h+3][3*i+2] = p2;
                    out[5*h+4][3*i+0] = p1;  out[5*h+4][3*i+1] = p2;  out[5*h+4][3*i+2] = p1;
                }
                if(in[h][i] == 'D')
                {
                    out[5*h+0][3*i+0] = p2;  out[5*h+0][3*i+1] = p2;  out[5*h+0][3*i+2] = p1;
                    out[5*h+1][3*i+0] = p2;  out[5*h+1][3*i+1] = p1;  out[5*h+1][3*i+2] = p2;
                    out[5*h+2][3*i+0] = p2;  out[5*h+2][3*i+1] = p1;  out[5*h+2][3*i+2] = p2;
                    out[5*h+3][3*i+0] = p2;  out[5*h+3][3*i+1] = p1;  out[5*h+3][3*i+2] = p2;
                    out[5*h+4][3*i+0] = p2;  out[5*h+4][3*i+1] = p2;  out[5*h+4][3*i+2] = p1;
                }
                if(in[h][i] == 'E')
                {
                    out[5*h+0][3*i+0] = p2;  out[5*h+0][3*i+1] = p2;  out[5*h+0][3*i+2] = p2;
                    out[5*h+1][3*i+0] = p2;  out[5*h+1][3*i+1] = p1;  out[5*h+1][3*i+2] = p1;
                    out[5*h+2][3*i+0] = p2;  out[5*h+2][3*i+1] = p2;  out[5*h+2][3*i+2] = p1;
                    out[5*h+3][3*i+0] = p2;  out[5*h+3][3*i+1] = p1;  out[5*h+3][3*i+2] = p1;
                    out[5*h+4][3*i+0] = p2;  out[5*h+4][3*i+1] = p2;  out[5*h+4][3*i+2] = p2;
                }
                if(in[h][i] == 'F')
                {
                    out[5*h+0][3*i+0] = p2;  out[5*h+0][3*i+1] = p2;  out[5*h+0][3*i+2] = p2;
                    out[5*h+1][3*i+0] = p2;  out[5*h+1][3*i+1] = p1;  out[5*h+1][3*i+2] = p1;
                    out[5*h+2][3*i+0] = p2;  out[5*h+2][3*i+1] = p2;  out[5*h+2][3*i+2] = p1;
                    out[5*h+3][3*i+0] = p2;  out[5*h+3][3*i+1] = p1;  out[5*h+3][3*i+2] = p1;
                    out[5*h+4][3*i+0] = p2;  out[5*h+4][3*i+1] = p1;  out[5*h+4][3*i+2] = p1;
                }
            }
        }
        rows *= 5;
        cols *= 3;

        //std::cout << std::endl;
        for(int i=0;i<rows;i++)
        {
            for(int j=0;j<cols;j++)
            {
                //std::cout << out[i][j];
                fprintf(f, "%c", out[i][j]);
            }
            //std::cout << std::endl;
            fprintf(f, "\n");
        }
        //std::cout << std::endl;
        fprintf(f, "\n");
    }

    fclose(f);
    return 0;
}*/

//solution begins
std::string decodeLine(FILE *f)
{
    std::string decoded;

    char aa = getc(f);
    if(aa == EOF) return "";
    while(aa != '\n')
    {
        char line[3];
        line[0] = aa;
        line[1] = getc(f);
        line[2] = getc(f);

        if(line[0] == line[1] && line[1] == line[2])
        {
            //*** EF
            decoded.push_back('E');
        }
        else if(line[0] == line[1])
        {
            //**. BD
            decoded.push_back('B');
        }
        else
        {
            //.*. AC
            decoded.push_back('A');
        }

        aa = getc(f);
    }
    while(getc(f) != '\n');
    aa = getc(f);
    for(int i=0; aa != '\n'; i++)
    {
        char line[3];
        line[0] = aa;
        line[1] = getc(f);
        line[2] = getc(f);

        if(decoded[i] == 'A' || decoded[i] == 'B')
        {
            if(line[0] == line[1] && line[1] == line[2])
            {
                //*** A
                decoded[i] = 'A';
            }
            else if(line[0] == line[1])
            {
                //**. B
                decoded[i] = 'B';
            }
            else if(line[0] == line[2])
            {
                //*.* D
                decoded[i] = 'D';
            }
            else
            {
                //.**
                decoded[i] = 'C';
            }
        }
        aa = getc(f);
    }
    while(getc(f) != '\n');
    aa = getc(f);
    for(int i=0; aa != '\n'; i++)
    {
        char line[3];
        line[0] = aa;
        line[1] = getc(f);
        line[2] = getc(f);

        if(decoded[i] == 'E')
        {
            if(line[0] == line[1])
            {
                //*** E
                decoded[i] = 'E';
            }
            else
            {
                //*.. F
                decoded[i] = 'F';
            }
        }
        aa = getc(f);
    }

    return decoded;
}

int main()
{
    for(int lines=0; lines!=1;)
    {
        lines=0;

        FILE *f, *g;
        f = fopen("D://in.txt", "r");
        g = fopen("D://out.txt", "w");
        while(!feof(f))
        {
            std::string decoded = decodeLine(f);
            if(!decoded.empty())
            {
                fprintf(g, "%s\n", decoded.c_str());
                lines++;
            }
        }
        fclose(f);
        fclose(g);

        f = fopen("D://out.txt", "r");
        g = fopen("D://in.txt", "w");
        char aa = getc(f);
        while(aa != EOF)
        {
            fprintf(g, "%c", aa);
            aa = getc(f);
        }
        fclose(f);
        fclose(g);
    }

    return 0;
}
