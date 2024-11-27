fid = fopen('slovnik.txt');

i=0;
while ~feof(fid)
	i = i+1;
	curr = (fgets(fid));
	A{i} = curr;
	if strcmp(deblank(curr),'PISAR')
		init = i;
	end
	if strcmp(deblank(curr),'TEXT')
		final = i;
	end
end

n=i;
 
current= init;
next = [];
nextnext = [];

found = 0;

dist = 0;
distance=[1:n];
pred=[1:n];
for i=1:n
	distance(i)=0;
	pred(i)=0;
end

while found == 0		 
	for i = 1: length(current)
		for j=1:n
			if distance(j) == 0
				if compare(A{current(i)},A{j}) == 1
					  next =[next j];
					  distance(j) = dist + 1;
					  pred(j) = current(i);
					  if j==final
						   found = 1;
						   break;
					  end
				end
				if compare(A{current(i)},A{j}) == 2
					  nextnext =[nextnext j];
					  distance(j) = dist + 2;
					  pred(j) = current(i);
					  if j==final
						   found = 1;
						   break;
					  end
				end
			end
		end
		if found == 1
			break
		end
	end
	if found == 1
		break
	end
	
	current =  next;
	next = nextnext;
	nextnext = [];
	dist = dist+1;
end

i=final;
A{i}
while i~=init
	i = pred(i);
	A{i}
end

	

