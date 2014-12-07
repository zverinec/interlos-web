#include <iostream>
#include <fstream>
#include <vector>
#include <stack>

#define MAX 4294967000 //4294967295

using namespace std;

typedef unsigned int ui;

class Maze
{
public:
    Maze() {}
    bool parse(ifstream &input) {
        int tmp;
        input >> i >> j;
        for (ui k = 0; k < i; ++k) {
            vector< ui > tmpVect;
            vector< int > tmpVectVert;
            vector< int > tmpVectHor;
            for (ui l = 0; l < j; ++l) {
                input >> tmp;
                tmpVect.push_back(tmp);
                tmpVectVert.push_back(-1);
                tmpVectHor.push_back(-1);
            }
            matrix.push_back(tmpVect);
            wallsHorizontal.push_back(tmpVectHor);
            wallsVertical.push_back(tmpVectVert);
        }

        ui wallsHorizontalCount, wallsVerticalCount;
        input >> wallsHorizontalCount;
        ui x;
        ui y;
        ui freq;
        for (ui k = 0; k < wallsHorizontalCount; ++k) {
            input >> x >> y >> freq;
            wallsHorizontal.at(y).at(x) = freq;
        }

        input >> wallsVerticalCount;
        for (ui k = 0; k < wallsVerticalCount; ++k) {
            input >> x >> y >> freq;
            wallsVertical.at(y).at(x) = freq;
        }

        return true;
    }

    void init() {
        for (ui y = 0; y < i; ++y) {
            vector< vector< ui > > tmp;
            for (ui x = 0; x < j; ++x) {
                vector< ui > tmpIn;
                tmpIn.push_back(MAX);
                tmp.push_back(tmpIn);
            }
            distance.push_back(tmp);
        }
        distance.at(0).at(0).at(0) = matrix.at(0).at(0);
    }
    /**
     * @brief findPath
     *
     * starting at lap 1!
     * @return
     */
    int findPath() {
        for (ui lap = 1; lap < i*j; ++lap) {
            for (ui y = 0; y < i; ++y) {
                for (ui x = 0; x < j; ++x) {
                    ui dist = getMin(x, y, lap);
                    distance.at(y).at(x).push_back(dist);
                }
            }
            //if(stop(lap))
                //return distance.at(i-1).at(j-1).at(lap);
        }
        ui dist = MAX;
        for (ui k = 0; k < i*j; ++k) {
            dist = min(dist, distance.at(i-1).at(j-1).at(k));
        }
        return dist;
    }

    ui getMin(ui x, ui y, ui lap) {
        ui minVal = MAX;
        minVal = min(minVal, (distance.at(y).at(x).at(lap-1) + matrix.at(y).at(x)));
        if(x > 0
                && distance.at(y).at(x-1).at(lap-1) != MAX
                && lap % wallsVertical.at(y).at(x-1) != 0)
            minVal = min(minVal, (distance.at(y).at(x-1).at(lap-1) + matrix.at(y).at(x)));
        if(y > 0
                && distance.at(y-1).at(x).at(lap-1) != MAX
                && lap % wallsHorizontal.at(y-1).at(x) != 0)
            minVal = min(minVal, (distance.at(y-1).at(x).at(lap-1) + matrix.at(y).at(x)));
        if(x < j-1
                && distance.at(y).at(x+1).at(lap-1) != MAX
                && lap % wallsVertical.at(y).at(x) != 0)
            minVal = min(minVal, (distance.at(y).at(x+1).at(lap-1) + matrix.at(y).at(x)));
        if(y < i-1
                && distance.at(y+1).at(x).at(lap-1) != MAX
                && lap % wallsHorizontal.at(y).at(x) != 0)
            minVal = min(minVal, (distance.at(y+1).at(x).at(lap-1) + matrix.at(y).at(x)));
        return minVal;
    }

    //not working correctly
    bool stop(ui lap) {
        ui dist = distance.at(i-1).at(j-1).at(lap);
        for (ui k = 0; k < lap; ++k) {
            dist = min(dist, distance.at(i-1).at(j-1).at(k));
        }
        if(dist == MAX)
            return false;
        for (ui k = 0; k < i; ++k) {
            for (ui l = 0; l < j; ++l) {
                if(distance.at(k).at(l).at(lap) < dist)
                    return false;
            }
        }
        return true;
    }

    void toString() {
        cout << "matrix" << endl;
        for (ui k = 0; k < i; ++k) {
            for (ui l = 0; l < j; ++l) {
                if(wallsVertical.at(k).at(l) == -1)
                    cout << matrix.at(k).at(l) << " ";
                else
                    cout << matrix.at(k).at(l) << "|";
            }
            cout << endl;
            for (ui l = 0; l < j; ++l) {
                if(wallsHorizontal.at(k).at(l) == -1)
                    cout << "  ";
                else
                    cout << "- ";
            }
            cout << endl;
        }
    }

    void toString(ui lap) {
        cout << "matrix" << endl;
        for (ui k = 0; k < i; ++k) {
            for (ui l = 0; l < j; ++l) {
                if(wallsVertical.at(k).at(l) == -1)
                    cout << matrix.at(k).at(l) << " ";
                else if(lap % wallsVertical.at(k).at(l) == 0)
                    cout << matrix.at(k).at(l) << "|";
                else
                    cout << matrix.at(k).at(l) << " ";
            }
            cout << endl;
            for (ui l = 0; l < j; ++l) {
                if(wallsHorizontal.at(k).at(l) == -1)
                    cout << "  ";
                else if(lap % wallsHorizontal.at(k).at(l) == 0)
                    cout << "- ";
                else
                    cout << "  ";
            }
            cout << endl;
        }
    }

private:

    ui i, j; //i = y, j = x
    vector< vector< ui > > matrix;
    vector< vector< int > > wallsHorizontal;
    vector< vector< int > > wallsVertical;

    vector< vector< vector< ui > > > distance;

};

int main()
{
    ifstream input;
    input.open("../input.txt");

    Maze m;
    m.parse(input);
    //m.toString();
    m.init();
    cout << m.findPath() << endl;
    /*m.toString(0);
    cout << endl;
    m.toString(1);
    cout << endl;
    m.toString(2);
    cout << endl;
    m.toString(3);*/


    cout << "Hello World!" << endl;
    return 0;
}

