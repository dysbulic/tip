#include "robot_and_sdl.h"
#include "mainwindow.h"

#include <QtPlugin>
#include <QApplication>
//#include <QDomDocument>
#include <QSplashScreen>
#include <QFile>
#include <QTextStream>
#include <QMessageBox>
#include <QString>
#include <QFont>

int main(int argc, char ** argv){
    // Initialize SDL
    SDL_Init(SDL_INIT_JOYSTICK);
    
	QApplication application(argc, argv);

	QSplashScreen splashScreen;
	splashScreen.setPixmap(QPixmap("images/hci-splash-screen.png"));
	splashScreen.show();

    // Change the application's default font (windows only)
    #ifdef __WIN32__
    QFont defaultFont("Arial", 13, QFont::Bold);
    QApplication::setFont(defaultFont);
    #endif

	QFile qstdout;
	qstdout.open(stdout, QIODevice::WriteOnly); 
	QTextStream qcout(&qstdout);
	
	if(argc != 2){
		qcout << "Usage: " << argv[0] << " <config>" << endl;
		qcout << "   Where <config> is a interface configuration" << endl;
		//return -1;
	}

	// Create the main window
	MainWindow mainWindow(atol(argv[1]));

/*
	QDomDocument doc("HCIConfigML");
	QFile file(argv[1]);
	if(!file.open(QFile::ReadOnly)) {
		qcout << "Error: Could not open config: " << argv[1] << endl;
		return -1;
	}
	if(!doc.setContent(&file)) {
		qcout << "Error: Could not read config: " << argv[1] << endl;
		file.close();
		return -2;
	}
	file.close();

	QDomNode node = doc.documentElement().firstChild();
	while(!node.isNull()) {
		QDomElement elm = node.toElement(); // try to convert the node to an element.
		if(!elm.isNull()) {
			qcout << elm.tagName() << endl; // the node really is an element.
		}
		node = node.nextSibling();
	}
*/

	
	//sleep(4);

	qcout << "MainWindow::showFullScreen" << endl;
	mainWindow.showFullScreen();
	splashScreen.finish(&mainWindow);

	qcout << "Application::Exec" << endl;
	int status = application.exec();
	SDL_Quit();
	return(status);
};
