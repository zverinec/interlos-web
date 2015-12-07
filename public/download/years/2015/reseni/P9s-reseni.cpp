// g++ -std=c++11 P9s-reseni.cpp -o p9s

#include <iostream>
#include <fstream>
#include <string>
#include <cassert>
#include <vector>
#include <algorithm>

enum class Ingredience {
    rajce,
    zampion,
    lilek,
    cibule,
    paprika,
    syr,
    maso,
    slanina
};

enum class Typ { vegetariansky, masovy, vadny };

std::string show( Ingredience i ) {
    if ( i == Ingredience::rajce ) return "rajce";
    if ( i == Ingredience::zampion ) return "zampion";
    if ( i == Ingredience::lilek ) return "lilek";
    if ( i == Ingredience::cibule ) return "cibule";
    if ( i == Ingredience::paprika ) return "paprika";
    if ( i == Ingredience::syr ) return "syr";
    if ( i == Ingredience::maso ) return "maso";
    if ( i == Ingredience::slanina ) return "slanina";
    return "<error>";
}

struct Spiz {
    static const int height = 7;

    Spiz( std::istream &is ) {
        std::string line;
        _len = 0;
        while ( std::getline( is, line ) ) {
            if ( line.empty() )
                break;
            if ( !_len )
                _len = line.size();
            else
                assert( _len == line.size() ); // kontrola validity vstupu
            // zkopirujeme radek do dat
            std::copy( line.begin(), line.end(), std::back_inserter( _data ) );
        }
        assert( _data.size() / _len == height ); // dalsi kontrola validity vstupu

        for ( int i = 0; i < size(); ++i )
            _parsed.push_back( parse( i ) );
    }

    Ingredience parse( size_t pos ) {
        pos = pos * 2 + 1; // ignoruji '-'
        std::string data;

        // cteme po sloupcich
        for ( int i = 0; i < height; ++i, pos += _len )
            if ( _data[ pos ] != ' ' )
                data.push_back( _data[ pos ] );

        if ( data == "rajce" ) return Ingredience::rajce;
        if ( data == "zampion" ) return Ingredience::zampion;
        if ( data == "lilek" ) return Ingredience::lilek;
        if ( data == "cibule" ) return Ingredience::cibule;
        if ( data == "paprika" ) return Ingredience::paprika;
        if ( data == "syr" ) return Ingredience::syr;
        if ( data == "maso" ) return Ingredience::maso;
        if ( data == "slanina" ) return Ingredience::slanina;
        std::cerr << "ERR: " << data << std::endl;
        std::abort();
    }

    int size() { return _len / 2; }

    std::vector< Ingredience >::iterator begin() { return _parsed.begin(); }
    std::vector< Ingredience >::iterator end() { return _parsed.end(); }

    int _len;
    std::vector< char > _data;
    std::vector< Ingredience > _parsed;
};

Typ vyres( Spiz &sp ) {
    auto it = std::find( sp.begin(), sp.end(), Ingredience::rajce );
    // zadna rajce
    if ( it == sp.end() )
        return Typ::vadny;
    // dve rajcata
    if ( std::find( it + 1, sp.end(), Ingredience::rajce ) != sp.end() )
        return Typ::vadny;

    // dva masove vyrobky, dva syry vedle sebe
    for ( auto i1 = it, i2 = it + 1; i2 != sp.end(); ++i1, ++i2 ) {
        if ( (*i1 == *i2 && (*i1 == Ingredience::syr || *i1 == Ingredience::maso || *i1 == Ingredience::slanina ))
                || (*i1 == Ingredience::maso && *i2 == Ingredience::slanina)
                || (*i1 == Ingredience::slanina && *i2 == Ingredience::maso ) )
            return Typ::vadny;
    }

    // na zacatku je spiz vegetariansky az do nalezeni jine ingredience
    Typ typ = Typ::vegetariansky;
    auto rajce = it, levy = it - 1, pravy = it + 1;

    // dale postupujeme od stredu, nejprve preskocime vsechny ingredience ktere
    // se neparuji (a jen pokud musíme kontrolujeme, ze jsme vegetariansky spiz)
    while ( levy >= sp.begin() && pravy < sp.end() ) {
        while ( *pravy == Ingredience::cibule && pravy < sp.end() ) {
            pravy++;
            if ( typ != Typ::vegetariansky )
                return Typ::vadny;
        }

        while ( *levy == Ingredience::syr && levy >= sp.begin() ) {
            levy--;
            if ( typ != Typ::vegetariansky )
                return Typ::vadny;
        }

        while ( *levy == Ingredience::paprika && levy >= sp.begin() ) {
            typ = Typ::masovy;
            --levy;
        }

        while ( *pravy == Ingredience::paprika && pravy < sp.end() ) {
            typ = Typ::masovy;
            pravy++;
        }

        // mohli jsme dojít na konec
        if ( ( levy < sp.begin() || pravy >= sp.end() ) ) {
            if ( !( levy < sp.begin() && pravy >= sp.end() ) )
                return Typ::vadny;
            break;
        }

        if ( *levy == Ingredience::lilek ) {
            if ( *(levy - 1) != Ingredience::zampion || *pravy != Ingredience::zampion )
                return Typ::vadny;
            levy -= 2;
            pravy++;
            if ( typ != Typ::vegetariansky )
                return Typ::vadny;
        } else if ( *levy == Ingredience::maso ) {
            if ( *pravy != Ingredience::maso )
                return Typ::vadny;
            typ = Typ::masovy;
            --levy;
            pravy++;
        } else if ( *levy == Ingredience::slanina ) {
            if ( *pravy != Ingredience::slanina )
                return Typ::vadny;
            typ = Typ::masovy;
            --levy;
            pravy++;
        } else {
            return Typ::vadny;
        }
    }

    return typ;
}

int main( int argc, char **argv ) {
    std::string name = argc > 1 ? argv[1] : "P9-spizy.txt";
    std::ifstream in( name );

    std::vector< Spiz > spizy;
    while ( in.good() )
        spizy.push_back( Spiz( in ) );

    for ( Spiz &sp : spizy ) {
        Typ r = vyres( sp );
        if ( r == Typ::vadny )
            std::cout << "N";
        if ( r == Typ::vegetariansky )
            std::cout << "V";
        if ( r == Typ::masovy )
            std::cout << "M";
    }
    std::cout << std::endl;
}
