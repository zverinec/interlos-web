program interlos;

const
	DELKA = 9;

var     
	i,j : Integer;
	prvni_pole: array [0..DELKA-1] of Integer;
	druhe_pole: array [0..DELKA-1] of Integer;
	kod : String;
  
  
procedure swap(k: Integer; l: Integer);
var
	temp: Integer;
begin
    temp := prvni_pole[k];
    prvni_pole[k] := prvni_pole[l];
    prvni_pole[l] := temp;
    temp := druhe_pole[k];
    druhe_pole[k] := druhe_pole[l];
    druhe_pole[l] := temp;
end;
  
  
begin
	ReadLn(kod);
    for i:=0 to DELKA-1 do begin
        prvni_pole[i] := ord(kod[i+1])-ord('a')+1; 
        druhe_pole[i] := i;
    end;

    for i:=0 to DELKA-1 do begin
        for j:=0 to DELKA-2 do begin
            if (prvni_pole[j] > prvni_pole[j+1]) then begin
                swap(j, j+1);
			end;
		end;
	end;

    for i:=DELKA-1 downto 1 do begin
        prvni_pole[i] := prvni_pole[i] - prvni_pole[i-1];
    end;

    for i:=0 to 41 do begin
        swap(i mod DELKA, 0);
    end;

	for i:=1 to DELKA-1 do begin
        prvni_pole[i] := prvni_pole[i] + prvni_pole[i-1];
    end;

	for i:=0 to DELKA-1 do begin
		for j:=0 to DELKA-2 do begin
			if (druhe_pole[j] > druhe_pole[j+1]) then begin
				swap(j,j+1);
			end;
		end;
	end;
	
    for i:=0 to DELKA-1 do begin
        Write(chr(ord('a')-1+prvni_pole[i]));
    end;
  
    WriteLn;
end.