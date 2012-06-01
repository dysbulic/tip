#include <iostream>
#include <string>
using namespace std;

/**
 * I want to understand how methods are overridden.
 */

class Shape {
public:
  Shape() { cout << "Constructed Shape\n"; }
  virtual string get_name() const { return "shape"; }
  friend ostream& operator << (ostream& os, const Shape& shape) {
    os << "<shp:" << shape.get_name() << ">";
    return os;
  }
};

class Square : public Shape {
public:
  Square() { cout << "Constructed Square\n"; }
  virtual string get_name() const { return "square"; }
  friend ostream& operator << (ostream& os, const Square& square) {
    os << "<sq:" << square.get_name() << ">";
    return os;
  }
};

/* Outputs:
 *
 * Constructed Shape
 * Constructed Square
 * <sq:square>:<shp:shape>:<shp:square>:<shp:square>
 */
int main(void) {
  Square sq;
  Shape shp = sq;
  Shape& shp_ref = sq;
  Shape* shp_ptr = &sq;
  cout << sq << ":" << shp << ":" << shp_ref << ":" << *shp_ptr << "\n";
}
  
