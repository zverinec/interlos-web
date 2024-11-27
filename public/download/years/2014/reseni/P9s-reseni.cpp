/* Vzorove reseni k P9 Pojistky
 * C++98, kompilovatelne napriklad GCC ale pravdepodobne i libovolnym jinym
 * C++ kompilatorem:
 * g++ P9-reseni.cpp -o P9-reseni
 *
 * pouzivani: spustte s nazvem souboru se zadanim jako jedinym parametrem
 */

#include <iostream>
#include <string>
#include <vector>
#include <set>
#include <sstream>
#include <cassert>
#include <fstream>

typedef unsigned long long u64_t;

struct Data {
	std::vector< std::set< std::string > > okruhy; // mapovani pojistka -> spotrebice
	std::set< std::string > spotrebice;  // mnozina vsech spotrebicu
	std::vector< std::string > pojistky; // jmena pojistek
};

Data parse( const char *fname ) {
	std::ifstream file( fname );
	std::string buff;
	Data data;

	// cteme vstup po radcich
	while ( std::getline( file, buff ) ) {
		if ( buff.empty() )
			continue;

		// vyuzijeme toho ze getline umi cist nejen po radcich ale
		// obecne do nejakeho zadaneho separatoru
		std::stringstream ss( buff );
		std::string name;
		std::getline( ss, name, ':' );
		data.pojistky.push_back( name ); // ulozime si jmeno pojistky

		// kazdy okruj je reprezentovan mnozinou zarizeni k nemu pripojenych
		data.okruhy.push_back( std::set< std::string >() );
		std::string spotrebic;
		while ( std::getline( ss, spotrebic, ' ' ) ) {
			if ( spotrebic.empty() )
				continue;
			data.okruhy.back().insert( spotrebic );
			data.spotrebice.insert( spotrebic );
		}
	}
	assert( data.okruhy.size() == data.pojistky.size() );
	return data;
}

int main( int argc, char **argv ) {
	assert( argc > 1 && "ocekavam 1 parametr: jmeno souboru" );
	Data data = parse( argv[1] );

	std::cout << "celkem zarizeni: " << data.spotrebice.size() << std::endl;

	assert( data.okruhy.size() <= 64 );

	/* Algoritmus funguje na principu hrube sily, tj. prohleda vsechna mozna
	 * zapojeni pojistek, pro kazde sjisti jestli jsou pripojena vsechna
	 * zarizeni a pokud ano podiva se jestli je mensi nez nejmensi dosud zname
	 * reseni. Pokud je nalezene reseni alespon tak dobre jako nejlepsi zname
	 * ulozime jej.
	 *
	 * Pozorovani: mame N pojistek, tedy N mnozin zarizeni, v kazdem reseni
	 * se kazda z N mnozin bud nachazi (pojistka je zapojena) nebo nenachazi.
	 * To lze reprezentovat pomoci N bitu, kde nejnizsi bit znaci zda je prvni
	 * pojistka zapojemna, druhy zda je druha pojistka zapojena...
	 * Vsechna mozna zapojeni jsou tak reprezentovana cisly mezi 1 a (2^N) - 1
	 * (tedy vsemi N-bitovymi cisly krome 0). Tato cisla muzeme ziskat
	 * inkrementaci modulo 2^N
	 */

	const u64_t size = data.okruhy.size(); // N
	const u64_t mask = (u64_t( 1 ) << size) - 1; // maska pro modulo

	int mincnt = data.okruhy.size(); // velikost minimalniho nalezeneho reseni
	std::vector< u64_t > solix; // nalezena minimalni reseni
	int hits = 0; // indikuje kolik celkem existuje reseni (vsech velikosti)

	// cyklus inkrementuje od 1 modulo N (size), tedy ze mamo vsechno pozname
	// tak se setIndex pretece na 0
	// setIndex udava ktere mnoziny mame sjednotit
	for ( u64_t setIndex = 1; setIndex != 0; ++setIndex, setIndex &= mask ) {
		std::set< std::string > connected; // mnozina pripojenych zarizeni
		int cnt = 0; // pocet zapojenych pojistek
		// pro kazdou pojistku zjistime zda ma byt zapojena
		for ( u64_t i = 0; i < size; ++i ) {
			if ( setIndex & (u64_t( 1 ) << i) ) {
				++cnt;
				// a pridame prislusna zarizeni k zapojenym
				connected.insert( data.okruhy[ i ].begin(), data.okruhy[ i ].end() );
			}
		}

		// pokud jsme zapojili vsechna zarizeni
		if ( connected == data.spotrebice ) {
			++hits;
			// nove reseni je lepsi nez nejlepsi dosud zname
			if ( cnt < mincnt ) {
				std::cout << "zlepsuji odhad minima z " << mincnt << " na "
						  << cnt << " pojistek" << std::endl;
				solix.clear(); // v solix jsou jen vetsi reseni, ta uz necheme
				mincnt = cnt;
			}
			// pokud je reseni alespon tak dobre jako nejlepsi zapamantujeme si
			if ( cnt == mincnt )
				solix.push_back( setIndex );
		}
	}

	// nyni jiz mame spocitane nejlepsi reseni, jen jej prevedeme do
	// citelne podoby
	std::set< std::string > solutions;
	// pro kazde nalzene reseni
	for ( std::vector< u64_t >::const_iterator sol = solix.begin();
			sol != solix.end(); ++sol )
	{
		std::set< std::string > s; // set je lexikograficky usporadany
		// zjistime ktere pojistky jsou pripojene a jejich nazvy pridame
		for ( u64_t i = 0; i < size; ++i )
			if ( *sol & (u64_t( 1 ) << i) )
				s.insert( data.pojistky[ i ] );

		// zapiseme nazvy vsech pozitych pojistek do retezce
		std::stringstream ss;
		for ( std::set< std::string >::const_iterator it = s.begin();
				it != s.end(); ++it )
			ss << *it;
		solutions.insert( ss.str() );
	}

	std::cout << "velikost minimalniho reseni: " << std::dec << mincnt << std::endl
			  << "pocet minimalnich reseni: " << solutions.size() << ", celkem reseni " << hits << std::endl
			  << "prvni reseni: " << *solutions.begin() << std::endl;
}
