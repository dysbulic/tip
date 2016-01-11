i = 1
(1..ARGV[0].to_i).each do |line|
  (1..line).each do
    print "#{i} "
    i += 1
  end
  puts
end
