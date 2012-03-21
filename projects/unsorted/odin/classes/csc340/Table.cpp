#include "Table.h"
#include "NewString.h"
#include <iostream.h>
#include <fstream.h>
#include <stdlib.h>

bool Table::add(Word& w) {
  bool failed = false;
  int index = 0;

  if(size == capacity) {
    Word* holding = new Word[size];
    if(holding != NULL)
      for(index = 0; index < size; index++)
        holding[index] = entries[index];
    else
      failed = true;
    
    if(!failed) {
      delete [] entries;
      entries = new Word[size * 2];
      
      if(entries == NULL) {
        entries = holding;
        failed = true;
      } else {
        for(index = 0; index < size; index++)
          entries[index] = holding[index];
        
        capacity = size * 2;
        delete [] holding;
      }
    }
  }

  if(!failed) {
    entries[size] = w;
    entries[size].count++;
    size++;
  }

  return !failed;
}

void processFile(const char* filename);

void main(int argc, char** argv) {
  ifstream inFile;
  cin = inFile.rdbuf();
  
  for(int i = 1; i < argc; i++) {
    inFile.open(argv[i]);
    if(inFile)
      processFile(argv[i]);
    inFile.close();
  }
}

void processFile(const char* filename) {
  NewString n;
  Table t;
  
  cout << "Processing file: " << filename << endl;
  cin.clear();
  while(cin >> n && !n.isNull())
    if(!t.insert(n)) {
      cerr << "Out of memeory" << endl;
      exit(0);
    }
  
  t.report();
  cout << endl;
}
