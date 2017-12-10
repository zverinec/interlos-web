#include <iostream>
#include <fstream>
#include <vector>
#include <queue>
#include <memory>

struct Node
{
    union {
        Node *neigh[4];
        struct{
            Node *left = nullptr,
                 *right = nullptr,
                 *up = nullptr,
                 *down = nullptr;
        };
    };
    Node *from = nullptr;
    unsigned dist = -1;
    char c;

    Node(Node *upper, Node *lefter, char ch)
        : up(upper)
        , left(lefter)
        , c(ch)
    {
        if (up)
            up->down = this;
        if (left)
            left->right = this;
    }
};


int main(int argc, const char *argv[])
{
    if (argc < 2) {
        std::cerr << "a.out <soubor-s-losem>" << std::endl;
        return 1;
    }

    std::ifstream file(argv[1]);
    if (not file) {
        std::cerr << "nejede" << std::endl;
    }

    std::deque<std::unique_ptr<Node>> nodes;
    std::vector<Node*> prev_row(20000, nullptr);

    Node* nA = nullptr;
    Node* nB = nullptr;

    int c;
    Node *prev_col = nullptr;
    while (not file.eof()) {
        int i = 0;
        while ((c = file.get()) != '\n' && c > 0) {
            Node *nnode = nullptr;
            if (c > 'z') { //0xe2
                file.get();//0x96
                file.get();//0x88
                file.get();//0xe2
                file.get();//0x96
            } else {
                nnode = new Node(prev_row[i], prev_col, c);
                nodes.emplace_back(nnode);
                if (c == 'A')
                    nA = nnode;
                else if (c == 'B')
                    nB = nnode;
            }
            prev_col = nnode;
            prev_row[i] = nnode;
            file.get();
            ++i;
        }
    }

    if (not nA)
        std::cerr << "chybi A" << std::endl;
    if (not nB)
        std::cerr << "chybi B" << std::endl;
    if (not nA or not nB)
        return 2;

    /********************************************/

    std::cout << "Graph built." << std::endl;
    nB->dist = 0;

    [&]{
        std::queue<Node*> bfs;
        bfs.push(nB);

        while (not bfs.empty()) {
            auto t = bfs.front();
            bfs.pop();
            for (auto n : t->neigh) {
                if (not n)
                    continue;
                if (t->dist + 1 < n->dist) {
                    n->from = t;
                    n->dist = t->dist + 1;
                    if (n == nA) {
                        std::cout << "Found A. ";
                        return;
                    }
                    bfs.push(n);
                }
            }
        }
    }();

    std::cout << "Search done." << std::endl;

    /********************************************/

    if (not nA->from) {
        std::cerr << "unreachable" << std::endl;
        return 3;
    }

    Node *n = nA->from;
    while (n != nB) {
        if (n->c != ' ')
            std::cout << n->c;
        n = n->from;
    }
    std::cout << std::endl;

    return 0;
}
