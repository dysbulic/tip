#ifndef TABLE_H
#define TABLE_H

/**
 * Table.h developed by Will Holcomb
 * April 27, 1999 - 
 *
 * Project number five for CSc 340
 **/

#include <iostream.h>
#include "NewString.h"

class Table {
private:
  Table(const Table& rhs) {}
  
protected:
  class Word {
  public:
    NewString entry;
    int count;
    
    Word() {
      entry = "";
      count = 0;
    }

    Word(const NewString& n) {
      entry = NewString(n);
      count = 0;
    }
  };

  int size;
  int capacity;
  Word* entries;
  
  bool add(Word& w);

public:
  Table(int n = 1) {
    capacity = n;
    if(capacity == 0)
      capacity++;
    
    entries = new Word[capacity];
    size = 0;
  }

  ~Table(void) {
    delete [] entries;
  }

  int countFor(const NewString& n) {
    int count = 0;
    
    for(int i = 0; i < size && count == 0; i++)
      if(entries[i].entry == n)
        count = entries[i].count;
    
    return count;
  }
  
  int indexOf(const NewString& n) {
    bool found = false;
    int index = 0;

    for(index = 0; index < size && !found; index++)
      if(entries[index].entry == n)
        found = true;
    
    if(found)
      return index - 1;
    else
      return -1;
  }

  /**
   * Does a bubble sort according first to the count and then according to
   * the alphabetical order.
   */
  void sort() {
    int i = 0;
    int j = 0;
    Word holding;
    
    for(i = 0; i < size; i++)
      for(j = size - 1; j > i; j--)
        if(entries[j].count > entries[i].count
           || (entries[j].count == entries[i].count
               && entries[j].entry < entries[i].entry)) {
          holding = entries[i];
          entries[i] = entries[j];
          entries[j] = holding;
        }
  }

  bool insert(const NewString& n) {
    bool sucessful = true;
    
    if(countFor(n) == 0)
      sucessful = add(Word(n));
    else
      entries[indexOf(n)].count++;
    
    return sucessful;
  }

  void report() {
    sort();
    for(int i = 0; i < size; i++)
      cout << entries[i].entry << " " << entries[i].count << endl;
  }
};
#endif
