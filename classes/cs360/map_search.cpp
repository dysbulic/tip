#include <iostream>
#include <iomanip>
#include <vector>
#include <algorithm>
using namespace std;

/**
 * Map Search Algorithms
 * Author: Will Holcomb <wholcomb@gmail.com>
 * Date: October 2007
 * Class: CS360 - Artificial Intelligence
 *
 * Given a map with a specified start and end point, use a variety of
 * uninformed search methods to find a path.
 */

/**
 * A SearchState stores information about a particular cell on the map.
 */
class SearchState {
public:
  int row, col;
  float distance;
  const SearchState* parent;

  SearchState(int col = 0, int row = 0, float distance = 0) : row(row), col(col), distance(distance) { parent = NULL; }
  //~SearchState() { cout << "Destroying : " << *this << "\n"; }

  void set_parent(const SearchState& parent) { this->parent = &parent; }

  bool operator < (const SearchState& a) const { return a.distance < this->distance; }

  bool operator == (const SearchState& a) const { return a.row == this->row && a.col == this->col; }

  friend ostream& operator << (ostream& os, const SearchState& state) {
    os << "<" << state.col << "," << state.row << ">|" << state.distance << "|";
    return os;
  }
};

/**
 * The MapSpace keeps track of three things:
 *  - The order that cells are visited for a particular algorithm
 *  - The cells that are inaccessible
 *  - Cells that are accessible and unsearched bordering a given cell
 */
class MapSpace {
private:
  SearchState start;
  SearchState goal;
  int visit_count;
public:
  const static int AVAIL = 0;
  const static int UNAVAIL = -1;
  const static int START = -2;
  const static int GOAL = -3;
  const static int ENQUEUE = -4;

  vector<vector<int> > map;

  MapSpace(int width, int height) : map(width, vector<int>(height)), visit_count(0) { }

  int area() { return this->map.size() * this->map[0].size(); }

  void mark_unavail(int col, int row) { this->map[col][row] = UNAVAIL; }

  void set_start(int col, int row) { start = SearchState(col, row); map[col][row] = START; }

  const SearchState& get_start() { return start; }

  void set_goal(int col, int row) { goal = SearchState(col, row); map[col][row] = GOAL; }

  const SearchState& get_goal() { return goal; }

  void visit(const SearchState& cell) {
    if(map[cell.col][cell.row] == AVAIL || map[cell.col][cell.row] == ENQUEUE)
      map[cell.col][cell.row] = ++visit_count;
  }

  void clear_visits() {
    for(int row = 0, col; row < map.size(); row++) {
      for(col = 0; col < map[row].size(); col++) {
        if(map[col][row] > AVAIL || map[col][row] == ENQUEUE) map[col][row] = AVAIL;
      }
    }
    visit_count = 0;
  }

  friend ostream& operator << (ostream& os, const MapSpace& map) {
    for(int row = 0, col; row < map.map.size(); row++) {
      os << '|';
      for(col = 0; col < map.map[row].size(); col++) {
        os << setw(2);
        switch(map.map[col][row]) {
        case UNAVAIL: os << "XX"; break;
        case START: os << "SS"; break;
        case GOAL: os << "GG"; break;
        case ENQUEUE:
        default:
          if(map.map[col][row] > 0) {
            os << setfill('0') << map.map[col][row] << setfill(' ');
          } else {
            os << ' ';
          }
        }
        os << '|';
      }
      os << '\n';
    }
    return os;
  }
};

class QueueManager {
private:
  MapSpace map;
  vector<SearchState> unsearched_cells;
public:
  QueueManager(MapSpace& base_map) : map(base_map) {
    unsearched_cells.push_back(map.get_start());
  }

  SearchState get_space(int row, int col) { return SearchState(row, col); }

  inline MapSpace& get_map() { return map; }

  SearchState pop() {
    stable_sort(unsearched_cells.begin(), unsearched_cells.end());

    cout << "Open: ";
    for(vector<SearchState>::iterator it = unsearched_cells.begin(); it != unsearched_cells.end(); it++) {
      cout << *it << " ";
    }
    cout << "\n";

    SearchState space = unsearched_cells.back();
    unsearched_cells.erase(unsearched_cells.end());
    vector<SearchState> neighbors = open_neighbors(space);
    for(vector<SearchState>::iterator it = neighbors.begin(); it != neighbors.end(); it++) {
      unsearched_cells.push_back(*it);
    }
    return space;
  }

  bool empty() { return unsearched_cells.empty(); }

  bool is_goal(const SearchState& state) { return state == map.get_goal(); }

  vector<SearchState>& search_list() { return unsearched_cells; }

  virtual vector<SearchState> open_neighbors(const SearchState& state) {
    vector<SearchState> neighbors;
    int p[][4] = {{state.col, state.row - 1},  // up
                  {state.col - 1, state.row},  // left
                  {state.col + 1, state.row},  // right
                  {state.col, state.row + 1}}; // down
    // The sort function causes the list to be drawn from the end, so this needs to be reversed
    for(int i = 3; i >= 0; i--) {
      if(p[i][0] >= 0 && p[i][0] < map.map.size() &&
         p[i][1] >= 0 && p[i][1] < map.map[p[i][0]].size() &&
         (map.map[p[i][0]][p[i][1]] == MapSpace::AVAIL || map.map[p[i][0]][p[i][1]] == MapSpace::GOAL
          || map.map[p[i][0]][p[i][1]] == MapSpace::ENQUEUE)) {
        if(map.map[p[i][0]][p[i][1]] == MapSpace::AVAIL) map.map[p[i][0]][p[i][1]] = MapSpace::ENQUEUE;
        neighbors.push_back(get_space(p[i][0], p[i][1]));
        neighbors.back().set_parent(state);
      }
    }
    map.visit(state);
    return neighbors;
  }
};

SearchState heuristic_search(QueueManager& queue) {
  SearchState current;
  do {
    current = queue.pop();
  } while(!queue.empty() && !queue.is_goal(current));
  if(queue.is_goal(current)) {
    /*
    cout << "Found: " << current << "\n";
    while(current.parent != NULL) {
      cout << current << ":" << current.parent << "\n";
      current = *(current.parent);
    }
    */
  } else {
    cout << "Not Found: " << current << "\n";
  }
  return current;
}

/**
 * For a depth first search, newly expanded nodes should be added to
 * the front of the queue. This is accomplished by setting the sort
 * indices of the children to less than the index coming in (which is
 * always the lowest current index).
 */
class DepthFirstManager: public QueueManager {
public:
  DepthFirstManager(MapSpace map) : QueueManager(map) { }
  vector<SearchState> open_neighbors(const SearchState& state) {
    vector<SearchState> neighbors = QueueManager::open_neighbors(state);
    for(vector<SearchState>::iterator it = neighbors.begin(); it != neighbors.end(); it++) {
      it->distance = state.distance - 1;
      vector<SearchState>::iterator elm = find(search_list().begin(), search_list().end(), *it);
      if(elm != search_list().end()) search_list().erase(elm);
    }
    return neighbors;
  }
};

/**
 * The distance is th "Manhattan distance" taken if one goes all the
 * way to the proper row then all the way to the proper column.
 */
class GreedyManager: public QueueManager {
public:
  GreedyManager(MapSpace& map) : QueueManager(map) { }
  vector<SearchState> open_neighbors(const SearchState& state) {
    vector<SearchState> neighbors = QueueManager::open_neighbors(state);
    float count = state.distance - neighbors.size();
    for(vector<SearchState>::iterator it = neighbors.begin(); it != neighbors.end(); it++, count++) {
      it->distance = abs(it->row - get_map().get_goal().row) + abs(it->col - get_map().get_goal().col);
      vector<SearchState>::iterator elm = find(search_list().begin(), search_list().end(), *it);
      if(elm != search_list().end()) search_list().erase(elm);
    }
    return neighbors;
  }
};

/**
 */
class AStarManager: public QueueManager {
public:
  AStarManager(MapSpace& map) : QueueManager(map) { }
  vector<SearchState> open_neighbors(const SearchState& state) {
    vector<SearchState> neighbors = QueueManager::open_neighbors(state);
    float count = state.distance - neighbors.size();
    for(vector<SearchState>::iterator it = neighbors.begin(); it != neighbors.end(); it++, count++) {
      it->distance = abs(it->row - get_map().get_goal().row) + abs(it->col - get_map().get_goal().col);
    }
    return neighbors;
  }
};

int main () {
  MapSpace map(8, 8);

  for(int i = 2; i < 7; i++) map.mark_unavail(i, 3);
  map.mark_unavail(1, 4);
  map.mark_unavail(5, 4);
  map.mark_unavail(2, 5);
  map.mark_unavail(6, 5);
  map.set_goal(2, 2);
  map.set_start(3, 5);

  DepthFirstManager df_manager(map);

  cout << "Initial Map:\n";
  cout << df_manager.get_map() << "\n";

  heuristic_search(df_manager);
  cout << "Depth-First Search:\n";
  cout << df_manager.get_map() << "\n";
  map.clear_visits();

  GreedyManager gr_manager(map);
  heuristic_search(gr_manager);
  cout << "Greedy Search:\n";
  cout << gr_manager.get_map() << "\n";

  AStarManager as_manager(map);
  heuristic_search(as_manager);
  cout << "A* Search:\n";
  cout << as_manager.get_map() << "\n";

  return 0;
}
