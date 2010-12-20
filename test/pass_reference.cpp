#include <iostream>
using namespace std;

/**
 * I want to be able to store a reference to an object within
 * another.
 */

class Member {
public:
  int x, y;
  Member(int x = 0, int y = 0) : x(x), y(y) { }
  friend ostream& operator << (ostream& os, const Member& member) {
    os << "<" << member.x << "," << member.y << ">";
    return os;
  }
};

class Container {
  Member& member;
public:
  Container(Member& mem) : member(mem) { cout << "Constructed: " << &member << " : " << &mem << "\n"; }
  int addy(int num) { return member.y += num; }
  friend ostream& operator << (ostream& os, const Container& container) {
    os << "C:" << &(container.member) << ":" << container.member;
    return os;
  }
};

int main(void) {
  Member test_mem;
  cout << &test_mem << ": " << test_mem << "\n";    // check the initialization to <0,0>
  Container contain(test_mem); // Put a reference to the Member in the Container
  test_mem.x = 10;             // Change the original value
  cout << contain << "\n";     // Check that the referenced value is now <10,0>
  contain.addy(10);            // Add 10 to the reference
  cout << contain << "\n";     // Check that the referenced value is now <10,10>
}
  
