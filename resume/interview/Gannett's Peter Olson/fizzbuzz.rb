#!/bin/env ruby

(1..100).each do |i|
  if i % 3 == 0 and i % 5 == 0
    puts "#{i} both buxx"
  elsif i % 5 == 0
    puts "#{i} 5 fud"
  elsif i % 3 == 0
    puts "#{i} 3 fuxxbud"
  else
    puts i
  end
end
