#!/usr/bin/env python

# Attempting to generate a QT file selection dialog that allows the
# multiple selection of directories

from PyQt4 import QtCore, QtGui
import sys

app = QtGui.QApplication(sys.argv)
win = QtGui.QMainWindow()
file_dialog = QtGui.QFileDialog(win)
file_dialog.setFileMode(QtGui.QFileDialog.DirectoryOnly)

tree_view = file_dialog.findChild(QtGui.QTreeView)
tree_view.setSelectionMode(QtGui.QAbstractItemView.ExtendedSelection)
list_view = file_dialog.findChild(QtGui.QListView, "listView")
list_view.setSelectionMode(QtGui.QAbstractItemView.ExtendedSelection)

if file_dialog.exec_() == QtGui.QDialog.Accepted:
    fileList =  file_dialog.selectedFiles()
else:
    fileList = file_dialog.getOpenFileNames(win, "", "/")

if fileList is not None and len(fileList) > 0:
    for file in fileList:
        print file
else:
    print "Selected %s" % file_dialog.getExistingDirectory(win, "", "/")

