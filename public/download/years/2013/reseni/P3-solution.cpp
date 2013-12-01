/* C++
 * kompilujte takto: g++ P6s.cpp -o P6s
 * pak spustte P6s
 */
#include <iostream>

/* tato struktura umoznuje iterativni vypocet Fibonnaciho posloupnosti,
 * kde z kazdeho cisla jsou vzaty jen 3 bajty
 *
 * struktra ma ulozena cisla o dve dobredu
 */
struct FibState {
    int a, b; // posledni 2 cisla posloupnosti
    int i;    // indikuje kolikate cislo v posloupnosti prave mame
    int current; // aktualni cislo (s poradovim cislem i)
    FibState() : a( 0 ), b( 1 ), i( 0 ), current( -1 ) { // inicializujeme
        next(); // provedeme "nulty" krok (tedy i = 1, current = 0 nyni)
    }

    int next() {
        // aktualizujeme stav a vratime cislo jehoz poradove cislo je i
        current = a;
        a = b;
        b += current;
        b %= 0x1000000; // orezeme na 3 bajty
        ++i;
        return current;
    }
};

int main( void ) {
    int los = (int('L') << 16) | (int('o') << 8) | int('S'); // cil
    FibState fib; // struktura pocitajici Fibonnaciho radu

    while ( fib.current != los ) { // dokud aktualni neni LoS
        fib.next(); // posun Fibonnaciho radu
    }
    // vysledky
    std::cout << std::hex << "0x" << fib.current
              << std::dec << ": " << fib.i << std::endl;
    return 0;
}

