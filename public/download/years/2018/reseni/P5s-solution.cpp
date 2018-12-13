/* InterLoS 2018 - P5 "Automat na kafe" (solution) */
#include <iostream>
#include <fstream>
#include <vector>

constexpr const char MAX_OPERAND = 'p';
constexpr const char DOMAIN_SIZE = MAX_OPERAND - 'a' + 1;
constexpr const char KAFE[] = "kafe";
constexpr const unsigned KAFE_LENGTH = sizeof KAFE - 1;

struct PthInfo {
    unsigned long long n_ways[ DOMAIN_SIZE ] = { 0ULL };
    unsigned long long & operator[]( unsigned i ) { return n_ways[ i ]; }
};

std::vector< std::vector< char > > op( DOMAIN_SIZE, std::vector< char >( DOMAIN_SIZE, -1 ) );
std::vector< char > ops; // Operands
std::vector< std::vector < PthInfo > > pths; // [n_operands - 1][leftmost_pos]

constexpr char ord( char opch ) { return opch - 'a'; }
constexpr char chr( char opn ) { return opn + 'a'; }

void load_operator( const char *path );
void load_operands();
void allocate_pths();
void fill_elementary();
void fill_nth( unsigned l );

unsigned long long count_stack_traces( const char *st, unsigned n );

int main(int argc, const char *argv[])
{
    if ( argc != 2 ) {
        std::cerr << "USAGE: ./solve [FILE_WITH_OPERATOR_TABLE]\n"
                  << "Stream of operands is expected on stdin.\n";
        return 1;
    }

    load_operator( argv[ 1 ] );
    load_operands();

    allocate_pths();
    fill_elementary();
    for ( unsigned l = 1; l < ops.size() - KAFE_LENGTH + 1; ++l )
        fill_nth( l );

    /* st = map ord KAFE */
    char st[ KAFE_LENGTH ];
    for ( unsigned i = 0; i < KAFE_LENGTH; ++i )
        st[ i ] = ord( KAFE[ i ] );

    std::cout << "\"_|_" << KAFE << "\": " << std::flush;
    unsigned long long n = count_stack_traces( st, KAFE_LENGTH );
    std::cout << n << std::endl;

    return 0;
}

void allocate_pths()
{
    unsigned m = ops.size();
    unsigned ml = m - KAFE_LENGTH + 1;
    pths.resize( ml );
    for ( unsigned i = 0; i < ml; ++i )
        pths.at( i ).resize( m - i );
}

void fill_elementary()
{
    for ( unsigned pos = 0; pos < ops.size(); ++pos )
        pths.at(  0  ).at(  pos  )[ ops.at( pos ) ] = 1;
}

void fill_nth( unsigned l )
{
    unsigned m = ops.size();
    for ( unsigned leftmost = 0; leftmost < m - l; ++leftmost ) {
        auto & pth = pths.at(  l  ).at(  leftmost  );
        for ( unsigned outer = 0; outer < l; ++outer ) {
            auto & lpth = pths.at(  outer ).at(  leftmost  );
            auto & rpth = pths.at(  l - outer - 1  ).at(  leftmost + outer + 1  );
            for ( char lres = 0; lres < DOMAIN_SIZE; ++lres ) {
                for ( char rres = 0; rres < DOMAIN_SIZE; ++rres ) {
                    //std::cerr << rpth[ rres ];
                    pth[ op.at( lres ).at(  rres  ) ] += lpth[ lres ] * rpth[ rres ];
                }
            }
        }
    }
}

unsigned long long cst( const char *st, unsigned n, unsigned leftmost )
{
    unsigned m = ops.size();

    if ( n == 0 )
        return leftmost == m;

    if ( m - leftmost < n )
        return 0;

    unsigned long long traces = 0;
    for ( unsigned l = 0; l <= m - leftmost - n; ++l ) {
        unsigned long long x = pths.at( l ).at( leftmost )[ *st ];
        if ( x ) {
            traces += x * cst( st + 1, n - 1, leftmost + l + 1 );
        }
    }
    return traces;
}

unsigned long long count_stack_traces( const char *st, unsigned n )
{
    return cst( st, n, 0 );
}

void load_operator( const char *path )
{
    std::ifstream ifs( path );
    if ( !ifs ) {
        std::cerr << "Cannot open file.\n";
        std::exit( 2 );
    }

    for ( char l = 0; l < DOMAIN_SIZE; ++l ) {
        for ( char r = 0; r < DOMAIN_SIZE; ++r ) {
            char res = ifs.get();
            op.at( l ).at( r ) = ord( res );
        }
        ifs.ignore( -1U , '\n' );
    }
}

void load_operands()
{
    while ( !std::cin.eof() ) {
        char c;
        std::cin >> c;
        if ( std::cin.good() )
            ops.push_back( ord( c ) );
    }
}
