/**
 * Simple QT Test Program. To get this compiling on Windows:
 * 1. Install QT from http://trolltech.com/products/qt
 * 2. Install Cygwin from http://www.cygwin.com -- not strictly necessary, I just hate to DOS prompt
 * 3. Install MinGW from http://www.mingw.org
 * 4. Put the MinGW bin directory at the front of your path
 * 5. Put QT on your path
 * 6. Alias make="make.bat" to use the QT make batch file
 * 7. Run qmake -project
 * 8. Run qmake
 * 9. Run make
 * 10. Run ./release/dirname.exe
 */
#include <QApplication>
#include <QLabel>

int main(int argc, char* argv[]) {
  QApplication app(argc, argv);
  QLabel label("Hello Qt!", NULL);
  //app.setMainWidget(label);
  label.show();
  return app.exec();
}
