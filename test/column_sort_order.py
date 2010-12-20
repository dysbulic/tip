#!/usr/bin/env python

# Attempting to control the sort order within a QTreeView

from PyQt4 import QtCore, QtGui
import sys

class TestTreeModel(QtCore.QAbstractItemModel):
    def __init__(self):
        QtCore.QAbstractItemModel.__init__(self)
        self.data = xrange(20)
        self.columnNames = ["Name", "Attributes", "Value"]
        
    def columnCount(self, parent):
        return 3

    def rowCount(self, parent):
        rowCount = 0
        if not parent.isValid():
            rowCount = len(self.data)
        print "Row Count [%s,%s]: %s" % (parent.row(), parent.column(), rowCount)
        return rowCount

    def headerData(self, section, orientation, role):
        if orientation == QtCore.Qt.Horizontal and role == QtCore.Qt.DisplayRole and section < len(self.columnNames):
                return QtCore.QVariant(self.tr(self.columnNames[section]))
        return QtCore.QVariant()
    
    def data(self, index, role):
        if not index.isValid() or role != QtCore.Qt.DisplayRole:
            return QtCore.QVariant()

        print "Data: %s: %s" % (index.row(), index.column())
        #return QtCore.QVariant(self.data[index.row()] * index.column())

    def index(self, row, column, parent):
        if row < 0 or column < 0 or row >= self.rowCount(parent) or column >= self.columnCount(parent):
            return QtCore.QModelIndex()
        print "Index: %s: %s" % (row, column)
        return self.createIndex(row, column, row)

    def parent(self, child):
        if not child.isValid():
            return QtCore.QModelIndex()
        return QtCore.QModelIndex()
        #return self.createIndex(parentItem.row(), 0, parentItem)

    def flags(self, index):
        if not index.isValid():
            return QtCore.Qt.ItemIsEnabled
        return QtCore.Qt.ItemIsEnabled | QtCore.Qt.ItemIsSelectable

    def sort(self, column, order):
        pass
        
app = QtGui.QApplication(sys.argv)

model = TestTreeModel()

for string in model.mimeTypes():
    print string

tree = QtGui.QTreeView()
tree.setModel(model)
tree.setSortingEnabled(True)

tree.setWindowTitle(tree.tr("Directory View"))
tree.resize(640, 480)
tree.show()

sys.exit(app.exec_())
