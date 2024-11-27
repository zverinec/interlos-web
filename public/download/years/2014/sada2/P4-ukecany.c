#include <stdio.h>
#include <string.h>
#include <stdlib.h>
#include <ctype.h>
#if defined( __unix ) || defined( LINUX )
#include <unistd.h>
#define SLEEP(x) sleep(x)
#elif defined( WINDOWS ) || defined( WIN32 ) || defined( _WIN32 )
#include <windows.h>
#define SLEEP(x) Sleep( (x) * 1000 )
#else
#define SLEEP(x) ((void)x)
#endif

const char retezec1[] = "tato uloha je ponekud netradicni";
const char retezec2[] = "vasim ukolem je";
/*					 tento program spustit */
char retezec3[] = "az budu velky bude tady heslo";






unsigned crc32( const char *str );
unsigned long long dcrc32( const char *a, const char *b );
void assert( unsigned crc );
int dejHeslo( void );
int calc( void );
const char *_get( int x );

int main( void ) {
	switch ( dcrc32( retezec1, retezec2 ) ) {
		case 13372957282755392377ull:
			puts( retezec1 );
			puts( retezec2 );
			puts( _get( 0 ) );
			break;

		case 2016743016858628985ull:
			puts( _get( 1 ) );
			break;

		case 2016743016002400708ull:
			puts( _get( 2 ) );
			SLEEP( 10 );
			puts( _get( 3 ) );
			break;

		case 2407810392577454532ull:
			puts( _get( 4 ) );
			break;

		case 9228634042367886027ull:
			puts( _get( 5 ) );
			puts( _get( 6 ) );
			break;

		case 9228634042037654159ull:
			switch ( dejHeslo() ) {
				case 42:
					if ( crc32( retezec3 ) == 3464211625u ) {
						puts( _get( 7 ) );
						puts( retezec3 );
					} else {
						puts( _get( 8 ) );
					}
					break;

				case 1:
					puts( _get( 9 ) );
					break;

				case 40:
					assert( 2440053459 );
					puts( _get( 10 ) );
					break;

				case 35:
					assert( 81535042 );

					if ( calc() ) {
						puts( _get( 11 ) );
						puts( retezec3 );
					}
					break;
			}
	}
	return 0;
}

int get( unsigned crc ) {
	switch ( crc ) {
		case 1779537535ul: return 't';
		case 2483127713ul: return 'd';
		case 3398046497ul: return 'a';
		case 233564014ul: return 'o';
		case 1677464263ul: return 'w';
		case 561736815ul: return 'l';
		case 3257974359ul: return 'r';
		case 3125776056ul: return 'u';
		case 3560385230ul: return 'i';
		case 2110863481ul: return 'r';
		case 3772476261ul: return 'y';
		case 1040050005ul: return 'l';
		case 3698728198ul: return 'l';
		case 2958688300ul: return 'g';
		case 3630609178ul: return 'x';
		case 4005491979ul: return 'a';
		case 1816956355ul: return 'i';
		case 4290159709ul: return 'g';
		case 2132794281ul: return 'l';
		case 1883816523ul: return 'm';
		case 1446663513ul: return 'q';
		case 1986008501ul: return 'v';
		case 3596498760ul: return 'c';
		case 2657842087ul: return 'z';
		case 2741367340ul: return 'g';
		case 4216456466ul: return 'e';
		case 787080856ul: return 'q';
		case 4056040826ul: return 'k';
		case 2746988798ul: return 'q';
		case 562290095ul: return 'e';
		case 4033314034ul: return 'v';
		case 2073802677ul: return 'x';
		case 2229228098ul: return 'd';
		case 2047741729ul: return 'z';
		case 3749566386ul: return 'j';
		case 1815799515ul: return 'i';
		case 4064094893ul: return 'm';
		case 750542976ul: return 'l';
		case 641316292ul: return 'o';
		case 1980948626ul: return 't';
		case 3570346854ul: return 'r';
		case 4096149894ul: return 'r';
		case 3073177382ul: return 'd';
		case 919885837ul: return 'u';
		case 1530176709ul: return 'c';
		case 1684465730ul: return 'm';
		case 1612582886ul: return 't';
		case 4059112219ul: return 'r';
		case 3405019309ul: return 'h';
		case 1921758568ul: return 'h';
		case 29312750ul: return 'e';
		case 1558690028ul: return 'y';
		case 3176388492ul: return 'j';
		case 3283740660ul: return 'g';
		case 4065456556ul: return 'z';
		case 3048705250ul: return 'l';
		case 3187435968ul: return 'z';
		case 1901764756ul: return 'y';
		case 3042035741ul: return 'e';
		case 157843044ul: return 'u';
		case 1180135605ul: return 'r';
		case 3405774481ul: return 'i';
		case 3965023804ul: return 'u';
		case 3894705765ul: return 'f';
		case 138082760ul: return 'c';
		case 1473910718ul: return 'u';
		case 3160506140ul: return 'z';
		case 2508349882ul: return 't';
		case 762512483ul: return 'n';
		case 627251610ul: return 'r';
		case 1232398242ul: return 'i';
		case 3125228848ul: return 'j';
		case 1292050560ul: return 'm';
		case 1422841236ul: return 'y';
		case 3604246572ul: return 'z';
		case 2366308197ul: return 'i';
		case 3827737031ul: return 'v';
		case 252694600ul: return 's';
		case 1311096632ul: return 'i';
		case 1178788366ul: return 'v';
		case 1875848683ul: return 'v';
		case 2229501681ul: return 'l';
		case 3162375183ul: return 'm';
		case 3556537940ul: return 'o';
		case 773694648ul: return 'u';
		case 2480316063ul: return 'r';
		case 4129582482ul: return 'o';
		case 3807185528ul: return 'y';
		case 4229790091ul: return 'a';
		case 1449518971ul: return 'i';
		case 3773095603ul: return 'q';
		case 2157302792ul: return 'x';
		case 4128423838ul: return 'o';
		case 4008267985ul: return 'i';
		case 2145492459ul: return 'n';
		case 4028830586ul: return 'k';
		case 3914646788ul: return 'z';
		case 4097468308ul: return 's';
		case 4243120245ul: return 'c';
		case 541136608ul: return 'e';
		case 1451523472ul: return 'r';
		case 1183155300ul: return 'n';
		case 1573710016ul: return 's';
		case 1711224943ul: return 'v';
		case 2738032036ul: return 'q';
		case 3773361330ul: return 'x';
		case 1842833713ul: return 'x';
		case 3856848570ul: return 'i';
		case 2483672146ul: return 'j';
		case 2560036935ul: return 'i';
		case 4245113421ul: return 'n';
		case 3986594273ul: return 'w';
		case 2545985121ul: return 'n';
		case 3346619387ul: return 'o';
		case 985526990ul: return 'm';
		case 392678407ul: return 'z';
	}
	return 0;
}

int calc() {
	char buf[4];
	int i;
	buf[3] = 0;
	buf[0] = 0;
bal:
	++buf[0];
	if ( 1 ) {
		int i, c;
		int val;
		if ( crc32( retezec3 ) == 3464211625ul ) {
			for ( i = 0; i < strlen( retezec3 ); ++i )
				retezec3[ i ] = toupper( retezec3[ i ] );
			return 1;
		}

		i = 0; c = 0;
lab:
		if ( ((unsigned)i) == strlen( retezec3 ) ) {
			if ( c )
				goto bal;
			else
				goto lba;
		}

		buf[1] = i + 1;
		buf[2] = retezec3[i];
		val = get( crc32( buf ) );
		if ( val != 0 ) {
			c = 1;
			retezec3[i] = val;
			goto bal;
		}

		if ( ~crc32( "" ) )
			goto bla;
		((void)0);
		++i;
		goto lab;
	}
	puts( _get( 12 ) );
bla:
	puts( _get( 13 ) );
lba:
	return 0;
}


int chr( char x ) { return 0; }

int dejHeslo() {
	/*
	int i;
	int r = 0;
	for ( i = 0; i < sizeof( retezec3 ) - 1; ++i ) {
		r += chr( retezec3[ i ] );
		r %= 42;
	}
	return r + 1;
	*/
	return 0;
}

void assert( unsigned crc ) {
	if ( crc32( retezec3 ) != crc ) {
		puts( _get( 14 ) );
		exit( 1 );
	}
}

unsigned rc_crc32(unsigned crc, const char *buf, size_t len)
{
	static unsigned table[256];
	static int have_table = 0;
	unsigned rem;
	unsigned char octet;
	int i, j;
	const char *p, *q;


	if (have_table == 0) {

		for (i = 0; i < 256; i++) {
			rem = i;
			for (j = 0; j < 8; j++) {
				if (rem & 1) {
					rem >>= 1;
					rem ^= 0xedb88320;
				} else
					rem >>= 1;
			}
			table[i] = rem;
		}
		have_table = 1;
	}

	crc = ~crc;
	q = buf + len;

	for (p = buf; p < q; p++) {
		octet = *p;
		crc = (crc >> 8) ^ table[(crc & 0xff) ^ octet];
	}
	return ~crc;
}

unsigned crc32( const char *str ) {
	return rc_crc32( 0, str, strlen(str) );
}

unsigned long long dcrc32( const char *a, const char *b ) {
	return (((unsigned long long)crc32( a )) << 32) | ((unsigned long long)crc32( b ));
}

const char data[] = { 0x6e, 0x61, 0x70, 0x73, 0x61, 0x74, 0x20, 0x6a, 0x6d,
	0x65, 0x6e, 0x6f, 0x20, 0x73, 0x6f, 0x75, 0x74, 0x65, 0x7a, 0x65, 0x20,
	0x6d, 0x61, 0x6c, 0x79, 0x6d, 0x69, 0x20, 0x70, 0x69, 0x73, 0x6d, 0x65,
	0x6e, 0x79, 0x20, 0x64, 0x6f, 0x20, 0x70, 0x72, 0x76, 0x6e, 0x69, 0x68,
	0x6f, 0x20, 0x72, 0x65, 0x74, 0x65, 0x7a, 0x63, 0x65, 0x20, 0x61, 0x20,
	0x7a, 0x6b, 0x6f, 0x6d, 0x70, 0x69, 0x6c, 0x6f, 0x76, 0x61, 0x74, 0x20,
	0x7a, 0x6e, 0x6f, 0x76, 0x75, 0x00, 0x53, 0x76, 0x61, 0x74, 0x79, 0x20,
	0x4d, 0x69, 0x6b, 0x75, 0x4c, 0x6f, 0x53, 0x20, 0x6a, 0x65, 0x20, 0x70,
	0x6f, 0x74, 0x65, 0x73, 0x65, 0x6e, 0x2c, 0x20, 0x7a, 0x6b, 0x75, 0x73,
	0x74, 0x65, 0x20, 0x6e, 0x61, 0x70, 0x73, 0x61, 0x74, 0x20, 0x64, 0x65,
	0x6a, 0x20, 0x6d, 0x69, 0x20, 0x68, 0x65, 0x73, 0x6c, 0x6f, 0x20, 0x64,
	0x6f, 0x20, 0x64, 0x72, 0x75, 0x68, 0x65, 0x68, 0x6f, 0x20, 0x72, 0x65,
	0x74, 0x65, 0x7a, 0x63, 0x65, 0x00, 0x6e, 0x65, 0x64, 0x61, 0x6d, 0x2c,
	0x20, 0x63, 0x65, 0x6b, 0x65, 0x6a, 0x74, 0x65, 0x20, 0x63, 0x68, 0x76,
	0x69, 0x6c, 0x69, 0x00, 0x6e, 0x61, 0x70, 0x73, 0x69, 0x74, 0x65, 0x20,
	0x68, 0x65, 0x73, 0x6c, 0x6f, 0x20, 0x64, 0x6f, 0x20, 0x70, 0x72, 0x76,
	0x6e, 0x69, 0x68, 0x6f, 0x20, 0x72, 0x65, 0x74, 0x65, 0x7a, 0x63, 0x65,
	0x00, 0x6e, 0x65, 0x64, 0x61, 0x6d, 0x2c, 0x20, 0x6c, 0x65, 0x64, 0x61,
	0x20, 0x7a, 0x65, 0x20, 0x62, 0x79, 0x73, 0x20, 0x70, 0x72, 0x6f, 0x68,
	0x6f, 0x64, 0x69, 0x6c, 0x20, 0x74, 0x79, 0x20, 0x72, 0x65, 0x74, 0x65,
	0x7a, 0x63, 0x65, 0x00, 0x6d, 0x75, 0x6a, 0x20, 0x73, 0x75, 0x62, 0x73,
	0x79, 0x73, 0x74, 0x65, 0x6d, 0x20, 0x70, 0x72, 0x6f, 0x20, 0x64, 0x61,
	0x76, 0x61, 0x6e, 0x69, 0x20, 0x68, 0x65, 0x73, 0x6c, 0x61, 0x20, 0x6a,
	0x65, 0x20, 0x70, 0x6f, 0x6e, 0x65, 0x6b, 0x75, 0x64, 0x20, 0x72, 0x6f,
	0x7a, 0x62, 0x69, 0x74, 0x79, 0x2c, 0x20, 0x61, 0x6c, 0x65, 0x20, 0x73,
	0x6c, 0x6f, 0x20, 0x62, 0x79, 0x20, 0x74, 0x6f, 0x20, 0x6f, 0x70, 0x72,
	0x61, 0x76, 0x69, 0x74, 0x00, 0x6e, 0x61, 0x70, 0x69, 0x73, 0x20, 0x70,
	0x6f, 0x6b, 0x6f, 0x75, 0x73, 0x69, 0x6d, 0x20, 0x73, 0x65, 0x20, 0x64,
	0x6f, 0x20, 0x64, 0x72, 0x75, 0x68, 0x65, 0x68, 0x6f, 0x20, 0x72, 0x65,
	0x74, 0x65, 0x7a, 0x63, 0x65, 0x20, 0x61, 0x20, 0x6e, 0x61, 0x6a, 0x64,
	0x69, 0x20, 0x66, 0x75, 0x6e, 0x6b, 0x63, 0x69, 0x20, 0x64, 0x65, 0x6a,
	0x48, 0x65, 0x73, 0x6c, 0x6f, 0x20, 0x61, 0x20, 0x6f, 0x64, 0x6b, 0x6f,
	0x6d, 0x65, 0x6e, 0x74, 0x75, 0x6a, 0x20, 0x76, 0x20, 0x6e, 0x69, 0x20,
	0x6b, 0x6f, 0x64, 0x00, 0x68, 0x65, 0x73, 0x6c, 0x6f, 0x20, 0x6a, 0x65,
	0x3a, 0x00, 0x6d, 0x61, 0x74, 0x65, 0x20, 0x76, 0x20, 0x68, 0x65, 0x73,
	0x6c, 0x65, 0x20, 0x70, 0x72, 0x6f, 0x68, 0x6f, 0x7a, 0x65, 0x6e, 0x61,
	0x20, 0x6e, 0x65, 0x6a, 0x61, 0x6b, 0x61, 0x20, 0x70, 0x69, 0x73, 0x6d,
	0x65, 0x6e, 0x61, 0x00, 0x6a, 0x65, 0x20, 0x74, 0x6f, 0x20, 0x72, 0x6f,
	0x7a, 0x62, 0x69, 0x74, 0x79, 0x2c, 0x20, 0x6d, 0x75, 0x73, 0x69, 0x74,
	0x65, 0x20, 0x6f, 0x70, 0x72, 0x61, 0x76, 0x69, 0x74, 0x20, 0x66, 0x75,
	0x6e, 0x6b, 0x63, 0x69, 0x20, 0x63, 0x68, 0x72, 0x20, 0x74, 0x61, 0x6b,
	0x20, 0x61, 0x62, 0x79, 0x20, 0x76, 0x72, 0x61, 0x63, 0x65, 0x6c, 0x61,
	0x20, 0x70, 0x6f, 0x72, 0x61, 0x64, 0x69, 0x0a, 0x70, 0x69, 0x73, 0x6d,
	0x65, 0x6e, 0x65, 0x20, 0x76, 0x20, 0x61, 0x62, 0x65, 0x63, 0x65, 0x64,
	0x65, 0x20, 0x28, 0x27, 0x20, 0x27, 0x20, 0x6a, 0x65, 0x20, 0x30, 0x2c,
	0x20, 0x27, 0x61, 0x27, 0x20, 0x6a, 0x65, 0x20, 0x31, 0x2c, 0x20, 0x75,
	0x76, 0x61, 0x7a, 0x75, 0x6a, 0x74, 0x65, 0x20, 0x6a, 0x65, 0x6e, 0x20,
	0x6d, 0x61, 0x6c, 0x61, 0x20, 0x70, 0x69, 0x73, 0x6d, 0x65, 0x6e, 0x61,
	0x29, 0x00, 0x6f, 0x64, 0x73, 0x74, 0x72, 0x61, 0x6e, 0x74, 0x65, 0x20,
	0x7a, 0x20, 0x33, 0x2e, 0x20, 0x72, 0x65, 0x74, 0x65, 0x7a, 0x63, 0x65,
	0x20, 0x6d, 0x65, 0x7a, 0x65, 0x72, 0x79, 0x20, 0x61, 0x20, 0x70, 0x6f,
	0x74, 0x6f, 0x6d, 0x20, 0x6a, 0x65, 0x6a, 0x20, 0x7a, 0x6b, 0x72, 0x61,
	0x74, 0x74, 0x65, 0x20, 0x6e, 0x61, 0x20, 0x31, 0x36, 0x20, 0x7a, 0x6e,
	0x61, 0x6b, 0x75, 0x00, 0x53, 0x6c, 0x61, 0x76, 0x61, 0x2c, 0x20, 0x6d,
	0x61, 0x74, 0x65, 0x20, 0x68, 0x65, 0x73, 0x6c, 0x6f, 0x3a, 0x00, 0x74,
	0x6f, 0x68, 0x6c, 0x65, 0x20, 0x64, 0x6f, 0x70, 0x61, 0x64, 0x6c, 0x6f,
	0x20, 0x64, 0x6f, 0x73, 0x74, 0x20, 0x73, 0x70, 0x61, 0x74, 0x6e, 0x65,
	0x2c, 0x20, 0x7a, 0x6b, 0x75, 0x73, 0x20, 0x70, 0x72, 0x65, 0x70, 0x73,
	0x61, 0x74, 0x20, 0x74, 0x65, 0x6e, 0x20, 0x69, 0x66, 0x20, 0x6e, 0x61,
	0x20, 0x7a, 0x61, 0x63, 0x61, 0x74, 0x75, 0x20, 0x66, 0x75, 0x6e, 0x6b,
	0x63, 0x65, 0x20, 0x6e, 0x61, 0x20, 0x77, 0x68, 0x69, 0x6c, 0x65, 0x00,
	0x6e, 0x65, 0x6b, 0x64, 0x6f, 0x20, 0x74, 0x75, 0x20, 0x7a, 0x61, 0x73,
	0x65, 0x20, 0x70, 0x6f, 0x75, 0x7a, 0x69, 0x76, 0x61, 0x20, 0x67, 0x6f,
	0x74, 0x6f, 0x2c, 0x20, 0x74, 0x6f, 0x20, 0x6e, 0x65, 0x6e, 0x69, 0x20,
	0x6d, 0x6f, 0x63, 0x20, 0x70, 0x65, 0x6b, 0x6e, 0x65, 0x2c, 0x20, 0x73,
	0x6d, 0x61, 0x7a, 0x20, 0x74, 0x6f, 0x20, 0x63, 0x6f, 0x20, 0x76, 0x65,
	0x64, 0x65, 0x20, 0x6e, 0x61, 0x20, 0x6e, 0x61, 0x76, 0x65, 0x73, 0x74,
	0x69, 0x20, 0x62, 0x6c, 0x61, 0x20, 0x62, 0x65, 0x7a, 0x20, 0x6e, 0x61,
	0x68, 0x72, 0x61, 0x64, 0x79, 0x00, 0x6e, 0x65, 0x6b, 0x64, 0x65, 0x20,
	0x6a, 0x73, 0x74, 0x65, 0x20, 0x73, 0x65, 0x20, 0x75, 0x70, 0x73, 0x61,
	0x6c, 0x69, 0x2c, 0x20, 0x7a, 0x6b, 0x6f, 0x6e, 0x74, 0x72, 0x6f, 0x6c,
	0x75, 0x6a, 0x74, 0x65, 0x20, 0x73, 0x69, 0x20, 0x74, 0x6f, 0x00, 0x00 };

const char *__get( int x, const char *ptr ) {
	return x == 0 ? ptr : (ptr[0] == 0 ? __get( x - 1, ptr + 1 ) : __get( x, ptr + 1 ));
}

const char *_get( int x ) {
	return __get( x, data );
}
