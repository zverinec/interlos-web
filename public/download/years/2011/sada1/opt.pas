{
  Computation of a really sophisticated function.
  Programmed by the Interlos organizing team.
  All rights reserved.
}

Program Interlos_Opt;
Var A, B, C : LongInt;

Function f(A : LongInt; B : LongInt; C : LongInt) : LongInt; Forward;
Function g(a : LongInt; b : LongInt; c : LongInt) : LongInt; Forward;
Function h(i : LongInt) : LongInt; Forward;
Function m(i : LongInt) : LongInt; Forward;


Function f(A : LongInt; B : LongInt; C : LongInt) : LongInt;
Var i, s : LongInt;
Begin
	s := 0;
	for i := 0 to C-1 do
		s := (s + A * g(B, i, h(i))) mod h(i+1);
	f := s mod h(C);
End;


Function g(a : LongInt; b : LongInt; c : LongInt) : LongInt;
Var i, r : LongInt;
Begin
	r := 1;
	for i := 0 to b-1 do
		r := (r * a) mod c;
	g := r;
End;


Function h(i : LongInt) : LongInt;
Var p : Array[0..9999] of LongInt;
	pc, n : LongInt;
	ok, thisOk : Boolean;
	j : LongInt;
Begin
	pc := 1;
	p[1] := 2;
	repeat
		n := p[pc];
		ok := false;
		repeat
			thisOk := true;
			inc(n);
			for j := 1 to pc do
				if n mod p[j] = 0 then thisOk := false;
			if thisOk then ok := true;
		until ok;

		inc(pc);
		p[pc] := n;
	until pc >= 9999;
	h := p[ m(i) ];
End;


Function m(i : LongInt) : LongInt;
Var k, j, n : LongInt;
Begin
	k := 6543 xor i;
	for n := 0 to (3456 and i) do
		for j := 0 to i-1 do
			k := k xor j;
	m := i xor k;
End;


Begin
	Read(A, B, C);
	Writeln(f(A, B, C));
End.
