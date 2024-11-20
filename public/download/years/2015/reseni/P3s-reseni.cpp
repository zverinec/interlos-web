// pro kompilaci použijte tento příkaz:
// g++ -Wall -O2 -std=c++11 -o P3-reseni P3-reseni.cpp

#include <fstream>
#include <iostream>
#include <vector>
using namespace std;

class Game
{
private:
    string current_word;
    vector<char> wrong_letters;
    vector<int> letter_masks;
    void initialize_letter_masks();
    int get_letter_mask(const string &word, char letter);
    vector<string> get_matching_words(const vector<string> &dictionary);
    bool is_matching(const string &word);
    bool contains_letter(const string &word, char letter);
public:
    Game(const string &current_word, const vector<char> &wrong_letters);
    char get_best_letter(const vector<string> &dictionary);
};

vector<string> load_dictionary();
vector<Game> load_games();
Game read_game(istream &stream);

int main()
{
    vector<string> dictionary = load_dictionary();
    vector<Game> games = load_games();

    for (Game game : games) {
        cout << game.get_best_letter(dictionary);
    }

    cout << endl;

    return 0;
}

Game::Game(const string &current_word, const vector<char> &wrong_letters)
{
    this->current_word = current_word;
    this->wrong_letters = wrong_letters;
    this->initialize_letter_masks();
}

void Game::initialize_letter_masks()
{
    this->letter_masks.resize(26);

    for (int i = 0; i < 26; i++) {
        int mask = get_letter_mask(this->current_word, i + 'A');
        letter_masks[i] = mask != 0 ? mask : -1;
    }

    for (char c : this->wrong_letters) {
        letter_masks[c - 'A'] = 0;
    }
}

int Game::get_letter_mask(const string &word, char letter)
{
    int mask = 0;

    for (int i = 0; i < (int) word.length(); i++) {
        if (word[i] == letter) {
            mask |= (1 << i);
        }
    }

    return mask;
}

char Game::get_best_letter(const vector<string> &dictionary)
{
    vector<string> words = this->get_matching_words(dictionary);
    char best_letter = 'A';
    int best_letter_count = 0;

    for (int i = 0; i < 26; i++) {
        if (letter_masks[i] == -1) {
            int count = 0;

            for (string word : words) {
                if (contains_letter(word, i + 'A')) {
                    count++;
                }
            }

            if (count > best_letter_count) {
                best_letter = i + 'A';
                best_letter_count = count;
            }
        }
    }

    return best_letter;
}

vector<string> Game::get_matching_words(const vector<string> &dictionary)
{
    vector<string> words;

    for (string word : dictionary) {
        if (word.length() == this->current_word.length() && this->is_matching(word)) {
            words.push_back(word);
        }
    }

    return words;
}

bool Game::is_matching(const string &word)
{
    for (int i = 0; i < 26; i++) {
        int mask = this->letter_masks[i];

        if (mask != -1 && mask != get_letter_mask(word, i + 'A')) {
            return false;
        }
    }

    return true;
}

bool Game::contains_letter(const string &word, char letter)
{
    for (char c : word) {
        if (c == letter) {
            return true;
        }
    }

    return false;
}

vector<string> load_dictionary()
{
    ifstream file("P3-slovnik.txt");
    vector<string> dictionary;
    string word;

    while (file >> word) {
        dictionary.push_back(word);
    }

    return dictionary;
}

vector<Game> load_games()
{
    ifstream file("P3-hry.txt");
    vector<Game> games;

    while (file.good()) {
        Game game = read_game(file);
        games.push_back(game);
    }

    return games;
}

Game read_game(istream &stream)
{
    string current_word;
    vector<char> wrong_letters;
    string line;

    for (int i = 0; i < 11; i++) {
        getline(stream, line);

        if (i == 0) {
            for (char c : line) {
                if (c >= 'A' && c <= 'Z') {
                    wrong_letters.push_back(c);
                }
            }
        }

        if (i == 9) {
            for (char c : line) {
                if ((c >= 'A' && c <= 'Z') || c == '_') {
                    current_word += c;
                }
            }
        }
    }

    return Game(current_word, wrong_letters);
}
