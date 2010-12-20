/* From: http://trolltech.com/developer/knowledgebase/faq.2007-03-08.3778118820/
 *
 * Atttempting to see if the issue of only being able to select
 * multiple directories in a single view is with the PyQT binding or
 * with QT in general.
 */

#include <QtGui>

int main(int argc, char **argv) {
  QApplication a(argc, argv);
  QFileDialog file_dialog;
  file_dialog.setFileMode(QFileDialog::DirectoryOnly);

  // There's a bug in the FAQ and this line is wrong
  // QListView *list_view = file_dialog.findChild<QListView*>();

  QListView *list_view = file_dialog.findChild<QListView*>("listView");

  if(list_view) {
    list_view->setSelectionMode(QAbstractItemView::MultiSelection);
  }
  QTreeView *tree_view = file_dialog.findChild<QTreeView*>();
  if(tree_view) {
    tree_view->setSelectionMode(QAbstractItemView::MultiSelection);
  }
  file_dialog.exec();
}
