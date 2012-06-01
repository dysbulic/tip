#!/usr/bin/ruby

$salut = ['Hello World',
  'Good Bye World',
  'What do you mean Good Bye World?']

print "When you enter the world, what do you say? "
while enterWorld = STDIN.gets
  enterWorld.chop!
  if enterWorld == $salut[0]
    print "\n" + "Yes. Hello World would be polite.\n"
    break
  else
    print "You say '", enterWorld, "'?!\n" + "You are so rude!\n"
  end
end
