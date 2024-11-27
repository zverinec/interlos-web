function x = compare(a,b)

x = 3;

lena = length(a);
lenb = length(b);

if (lena == lenb)
	zmena = 0;
	for i=1:lena
		if (a(i) ~= b(i))
			zmena = zmena+1;
			if zmena > 1
				break;
			end
		end
	end		
	if zmena == 1
		x=1;
	end
end

if (lena == lenb -1)
	c = a;
	a = b;
	b = c;
end
lena = length(a);
lenb = length(b);

if (lena == lenb +1)
	zmena = 0;
	j=1;
	for i = 1:lenb
		if zmena == 0			
			if (a(j) ~= b(i))
				zmena = zmena + 1;
				j = j+1;
			end
		end
		if zmena > 0 
			if (a(j) ~= b(i))
				zmena = zmena + 1;
				break;
			end			
		end
		j = j+1;
	end
	if zmena == 1
		x=2;
	end
end

if (lena == lenb +1)
	zmena = 0;
	for i = 1:lenb
		if (a(i) ~= b(i))
			zmena = zmena +1;
		end
	end
	if zmena == 0
		x = 2;
	end
end

	


