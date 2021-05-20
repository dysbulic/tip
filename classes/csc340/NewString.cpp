/**
 * NewString.cpp developed by Will Holcomb
 * March 30, 1999 - 
 *
 * Project number three for CSc 340
 **/

#include "NewString.h"
#include <iostream.h>
#include <cctype>

int NewString :: defaultCapacity = 15;

/*****
 * Constructors begun
 *****/

NewString :: NewString(char* str) {
  int i = 0;
  
  if(str == NULL)	{
    string = new char[defaultCapacity + 1];
    
    for(i = 0; i <= defaultCapacity; i++)
      string[i] = '\0';

    size = 0;
    capacity = defaultCapacity;
  } else {
    while(*(str + i) != '\0')
      i++;
    
    size = i;
    capacity = size;
    string = new char[capacity + 1];
    
    for(i = 0; i < size; i++)
      string[i] = str[i];
    
    string[i] = '\0';
  }
}

NewString :: NewString(char c) {
  string = new char[defaultCapacity + 1];
  
  for(int i = 1; i <= defaultCapacity; i++)
    string[i] = '\0';
  
  capacity = defaultCapacity;
  
  if(c == '\0') {
    size = 0;
  } else {
    size = 1;
    string[0] = c;
  }
}

NewString :: NewString(const NewString& rhs) {
  size = rhs.size;
  capacity = rhs.capacity;
  string = new char[capacity + 1];
  int i = 0;
  
  for(i = 0; i < size; i++)
    string[i] = rhs.string[i];
}

NewString :: ~NewString(void) {
  delete [] string;
}

/*****
 * Constructors finished
 *****/

/*****
 * Assignment operators begun
 *****/

NewString& NewString :: operator=(const NewString& rhs) {
  int i = 0;
  
  if(this == &rhs) {
    return *this;
  } else if(capacity < rhs.capacity) {
    delete [] string;
    string = new char[rhs.capacity + 1];
    capacity = rhs.capacity;
  }

  char* newString = new char[rhs.size + 1];
  
  for(i = 0; i <= rhs.size; i++)
    newString[i] = rhs.string[i];
  
  size = rhs.size;
  
  for(i = 0; i <= size; i++)
    string[i] = newString[i];
  
  delete [] newString;
  
  return *this;
}

NewString& NewString :: operator+=(const NewString& rhs) {
  *this = *this + rhs;
  
  return *this;
}

/*****
 * Assignment operators finished
 *****/

/*****
 * Equality operators begun
 *****/

bool operator==(const NewString& lhs, const NewString& rhs) {
  if(lhs.size != rhs.size) {
    return false;
  } else {
    for(int i = 0; i < lhs.size; i++)
      if(lhs.string[i] != rhs.string[i])
        return false;
    
    return true;
  }
}

bool operator!=(const NewString& lhs, const NewString& rhs) {
  return(!(lhs == rhs));
}

bool operator<(const NewString& lhs, const NewString& rhs) {
  int min = ((lhs.size < rhs.size) ? lhs.size : rhs.size);
  int i = 0;
  
  if(lhs == rhs) {
    return false;
  } else {
    while(i < min && toupper(lhs.string[i]) == toupper(rhs.string[i]))
      i++;
    
    if(i == min)
      return(i == lhs.size);
    else
      return((int)toupper(lhs.string[i]) < (int)toupper(rhs.string[i]));
  }
}

bool operator<=(const NewString& lhs, const NewString& rhs) {
  if(lhs < rhs || rhs == lhs)
    return true;
  else
    return false;
}

bool operator>(const NewString& lhs, const NewString& rhs) {
  int min = ((lhs.size < rhs.size) ? lhs.size : rhs.size);
  int i = 0;
  
  if(lhs == rhs) {
    return false;
  } else {
    while(i < min && toupper(lhs.string[i]) == toupper(rhs.string[i]))
      i++;
    
    if(i == min)
      return(i == rhs.size);
    else
      return((int)toupper(lhs.string[i]) > (int)toupper(rhs.string[i]));
  }
}

bool operator>=(const NewString& lhs, const NewString& rhs) {
  if(lhs > rhs || rhs == lhs)
    return true;
  else
    return false;
}

/*****
 * Equality operators finished
 *****/

/*****
 * Unique member functions begun
 *****/

int NewString :: hashString(void) const {
  int hashValue = 0;
  
  for(int i = 0; i < size; i++)
    hashValue += (int)string[i];
  
  return hashValue;
}

char* NewString :: insert(int index, char toInsert) {
  if(!(index >= 0 && index <= size)) {
    return NULL;
  } else {
    int i = 0;
    
    if(capacity < size + 1) {
      NewString temp = *this;
      
      delete [] string;
      
      string = new char[size + 2];
      capacity = size + 1;
      
      for(i = 0; i < size; i++)
        string[i] = temp.string[i];
    }

    for(i = size - 1; i >= index; i--)
      string [i + 1] = string [i];
    
    string[index] = toInsert;
    size++;
    string[size] = '\0';
    
    return &string[index];
  }
}

char* NewString :: remove(int index) {
  if(!(index >= 0 && index < size)) {
    return NULL;
  } else {
    for(int i = index; i < size; i++)
      string[i] = string[i + 1];
    
    size--;
    string[size] = '\0';
    
    if(index >= size)
      return &string[size - 1];
    else
      return &string[index];
  }
}

int NewString :: find(char toFind) const {
  for(int i = 0; i < size; i++)
    if(string[i] == toFind)
      return i;
  
  return -1;
}

void NewString :: trim(void) {
  int start_index = 0;
  int end_index = size - 1;
  int i = 0;
  

  while(string[start_index] != '\0'
        && isWhitespace(string[start_index]))
    start_index++;
  
  while(end_index > start_index
        && isWhitespace(string[end_index]))
    end_index--;
  
  if(start_index != 0)
    for(i = 0; i <= end_index - start_index; i++)
      string[i] = string[start_index + i];
  
  size = end_index - start_index + 1;
  string[size] = '\0';
  
  return;
}

void NewString :: compress(void) {
  int i = 1;
  int new_index = 1;
  
  while(string[i] != '\0') {
    if(isWhitespace(string[i])) {
      if(!isWhitespace(string[i - 1])) {
        string[new_index] = ' ';
        new_index++;
      }
    } else {
      string[new_index] = string[i];
      new_index++;
    }
    i++;
  }
	
  size = new_index;
  string[size] = '\0';

  return;
}

void NewString :: remove(char c) {
  int i = 0;
  int new_index = 0;
  
  if(find(c) != -1) {
    while(i < size) {
      if(string[i] != c) {
        string[new_index] = string[i];
        new_index++;
      }
      i++;
    }
    
    size = new_index;
    string[size] = '\0';
  }
  
  return;
}

/*****
 * Unique member functions finished
 *****/

/*****
 * Printing operators begun
 *****/

ostream& operator<<(ostream& os, const NewString& str) {
  int i = 0;

  for(i = 0; i < str.size && str.string[i] != '\0'; i++)
    os << str.string[i];
  
  return os;
}

istream& operator>>(istream& is,  NewString& rhs) {
  const int bufferSize = 20;

  char buffer[bufferSize + 1];
  int index = 0;
  
  rhs.clear();
  
  buffer[0] = '\0';
  while(is.get(buffer[0])
        && rhs.isWhitespace(buffer[0]))
    ;

  index = 1;
  
  while(is.get(buffer[index])
        && !rhs.isDelimiter(buffer[index]))
    if(iendex >= bufferSize - 1) {
      buffer[bufferSize] = '\0';
      rhs += buffer;
      index = 0;
    } else {
      index++;
    }

  buffer[index] = '\0';
  rhs += buffer;
  
  return is;
}

/*****
 * Printing operators finished
 *****/

/*****
 * Arithmetic operators begun
 *****/

NewString operator+(const NewString& lhs, const NewString& rhs) {
  if(lhs.size == 0) {
    return rhs;
  } else if(rhs.size == 0) {
    return lhs;
  } else {
    int i = 0;
    int neededSize = lhs.size + rhs.size;
    char* outString = new char[neededSize + 1];
    
    for(i = 0; i < lhs.size; i++)
      outString[i] = lhs.string[i];
    
    int offset = lhs.size;
    
    for(i = 0; i < rhs.size; i++)
      outString[offset + i] = rhs.string[i];
    
    outString[offset + i] = '\0';
    
    NewString returnNewString = NewString(outString);
    
    delete [] outString;
    
    return returnNewString;
  }
}

/*****
 * Arithmetic operators finished
 *****/
