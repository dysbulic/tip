/**
 * Author: Will Holcomb <wholcomb@gmail.com>
 * Date: 9 February 2007
 *
 * A standard deck of playing cards consists of 52 cards, defined as 4
 * suits of 13 card types (Ace, 2-King). Design code to manage and
 * store a deck, identify card suit and type, and implement a routine
 * to shuffle the deck.
 */
#include <iostream>
#include <cstdlib>
#include <ctime>
using namespace std;

enum Suit { HEART, CLUB, SPADE, DIAMOND };
enum Rank { JACK = 9, QUEEN, KING, ACE };

inline enum Suit &operator++(Suit &suit) {
  return suit = Suit(suit + 1 % 4);
}

class Card {
public:
  Suit suit;
  int value; // 0 = two through 12 = ace

  Card() {
    this->suit = SPADE;
    this->value = ACE;
  }

  Card(Suit suit, int value) {
    this->suit = suit;
    this->value = value;
  }

  friend ostream& operator <<(ostream &os, const Card &card);
};

ostream& operator <<(ostream& os, Card &card) {
  if(card.value < 9) {
    os << card.value + 2;
  } else {
    switch(card.value) {
    case JACK: os << "jack"; break;
    case QUEEN: os << "queen"; break;
    case KING: os << "king"; break;
    case ACE: os << "ace"; break;
    }
  }
  os << " of ";
  switch(card.suit) {
  case HEART: os << "hearts"; break;
  case SPADE: os << "spades"; break;
  case DIAMOND: os << "diamonds"; break;
  case CLUB: os << "clubs"; break;
  }
  return os;
}

class Deck {
public:
  Card* cards;
  const static int SIZE = 52;

  Deck() {
    cards = new Card[SIZE];
    int index = 0;
    for(Suit suit = HEART; suit <= DIAMOND; ++suit) {
      for(int value = 0; value < ACE; value++, index++) {
        cards[index].suit = suit;
        cards[index].value = value;
      }
    }
  }

  void standard_shuffle() {
    random_shuffle(cards, cards + SIZE);
  }

  void shuffle() {
    int i = SIZE, j;
    Card temp;
    if(i > 0) {
      while(--i > 0) {
        j = (int)(rand() % (i + 1));
        temp = cards[i];
        cards[i] = cards[j];
        cards[j] = temp;
      }
    }
  }
};

int main() {
  Deck* deck = new Deck();
  srand(time(NULL));
  deck->standard_shuffle();
  //deck->shuffle();
  for(int i = 0; i < deck->SIZE; i++) {
    cout << deck->cards[i] << "\n";
  }
  return 0;
}
