function z = summed_similarity(c,w,stim,categories,r) 
z = zeros(1,length(categories)); 
for i = 1:length(categories)                % looping through all categories. 
    for j = 1:size(categories{i},1)                % looping through all items in a category. 
        z(i) = z(i) + similarity(c,w,stim,categories{i}(j,:),r); 
    end
end
