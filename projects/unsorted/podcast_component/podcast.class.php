<?Php
include_once('comment.class.php');

class Podcast {
  public static $props = array('id', 'air_date', 'title', 'artist', 'artist_url', 'graphic', 'album', 'show_url', 'label', 'label_url', 'description', 'channel', 'channelAbbr', 'comments');
  public static $maxRating = 5;
  var $vals;
  var $dirty = array();
  var $extraProps = array('channel', 'channelAbbr');
  var $perpage = 10;

  function __construct() {
    $this->vals = array();
  }

  public function __get($key) {
    global $database, $table_prefix;
    switch($key) {
    case 'channelAbbr':
      if(!array_key_exists('channelAbbr', $this->vals)) {
        $sql = sprintf('select channel_abbr from #__podcast_channel where podcast_id = "%s"',
                       mysql_real_escape_string($this->vals['id']));
        $database->setQuery($sql);
        $this->vals['channelAbbr'] = $database->loadResult();
      }
      break;
    case 'channel':
      if(!array_key_exists('channel', $this->vals)) {
        $sql = sprintf('select name from #__channels where abbreviation = "%s"',
                       mysql_real_escape_string($this->channelAbbr));
        $database->setQuery($sql);
        $this->vals['channel'] = $database->loadResult();
      }
      break;
    case 'comments':
      if(!array_key_exists('comments', $this->vals)) {
        $this->vals['comments'] = Comment::loadComments($this->vals['id']);
      }
      break;
    case 'rating':
      if(!array_key_exists('rating', $this->vals)) {
        $this->vals['rating'] = $this->loadRating();
      }
      break;
    }
    if(array_key_exists($key, $this->vals)) {
      return $this->vals[$key];
    }
    return undefined;
  }

  public function __set($key, $val) {
    if($this->vals[$key] != $val) {
      $this->vals[$key] = $val;
      $this->dirty[$key] = true;
    }
  }

  /**
   * Loads a list of podcasts for a certain channel.
   */
  function loadPodcasts($params) {
    global $database, $table_prefix;
    $casts = array();
    $sql = ('select #__podcasts.*, #__artists.name as artist, #__artists.url as artist_url,' .
            ' #__labels.name as label, #__labels.url as label_url' .
            ' from #__podcasts left join #__artists on #__podcasts.artist_id = #__artists.id' .
            ' left join #__labels on #__labels.id = #__podcasts.label_id');
    $skip = 0;

    if(array_key_exists('id', $params)) {
      $sql = sprintf('%s where #__podcasts.id = "%s"', $sql, mysql_real_escape_string($params['id']));
    } else {
      if(array_key_exists('channel', $params)) {
        $sql = sprintf('%s left join #__podcast_channel on #__podcast_channel.podcast_id = #__podcasts.id' .
                       ' where #__podcast_channel.channel_abbr = "%s"',
                       $sql, mysql_real_escape_string($params['channel']));
      }
      if(array_key_exists('date', $params) && $params['date'] != undefined) {
        $sql = sprintf('%s %s air_date <= "%s"',
                       $sql, ($params['channel'] != undefined ? 'and' : 'where'),
                       mysql_real_escape_string($params['date']));
      }
      if(array_key_exists('pageno', $params)) {
        $skip = ($params['pageno'] - 1) * $params['perpage'];
      }
    }
    $database->setQuery($sql, $skip, $params['perpage']);
    foreach($database->loadObjectList() as $cast) {
      $podcast = new Podcast();
      $podcast->mergeClean($cast);
      if(array_key_exists('channel', $params)) $podcast->channelAbbr = $params['channel'];
      array_push($casts, $podcast);
    }
    return $casts;
  }

  function loadRating() {
    global $database, $my;
    if($my->id != 0) {
      // if a user is logged in and has rated a podcast, use that rating
      $sql = sprintf('select rating from #__ratings where podcast_id = "%d" and user_id = "%d"',
                     mysql_real_escape_string($params['id']), mysql_real_escape_string($my->id));
      $database->setQuery($sql);
      $this->vals['rating'] = $database->loadResult();
      $this->vals['rating'] = $this->vals['rating'] == null ? undefined : $this->vals['rating'];
    }
    if($this->vals['rating'] == null) {
      $sql = sprintf('select avg(rating) from #__ratings where podcast_id = %d',
                     mysql_real_escape_string($this->vals['id']));
      $database->setQuery($sql);
      $this->vals['rating'] = $database->loadResult();
      $this->vals['rating'] = $this->vals['rating'] == null ? undefined : $this->vals['rating'];
    }
    return $this->vals['rating'];
  }

  function setRating($rating) {
    global $database, $my;
    if($rating != undefined) {
      $rating = max(min(Podcast::$maxRating, $rating), 1);
      $sql = sprintf('insert into #__ratings (podcast_id, user_id, rating) values (%d, %s, %d)',
                     $this->vals['id'], $my->id == 0 ? 'null' : $my->id, $rating);
      print $sql;
      $database->setQuery($sql);
      $database->query();
    }
  }

  /**
   * Loads a list of the most recent of each channel
   */
  function loadCurrentCasts($date) {
    global $database, $table_prefix;
    $casts = array();
    $sql = 'select distinct channel_abbr from #__podcast_channel left join #__channels on abbreviation = channel_abbr order by sort_order';
    $database->setQuery($sql);
    foreach($database->loadObjectList() as $abbr) {
      $castList = self::loadPodcasts(array('channel' => $abbr->channel_abbr, 'perpage' => 1, 'date' => $date));
      if(count($castList) > 0) array_push($casts, $castList[0]);
    }
    return $casts;
  }

  function loadCurrentCast($channel, $date) {
    $castList = self::loadPodcasts(array('channel' => $channel, 'perpage' => 1, 'date' => $date));
    if(count($castList) > 0) return $castList[0];
    else return undefined;
  }

  function loadCast($id) {
    $castList = self::loadPodcasts(array('id' => $id, 'perpage' => 1));
    if(count($castList) > 0) return $castList[0];
    else return undefined;
  }

  function save() {
    global $database;
    // If the id is set, the data was loaded from the db and this is an update
    if(array_key_exists('id', $this->vals)) {
      $sql = 'update #__podcasts set';
      $changeCount = 0;
      for($i = 0; $i < count($this->props); $i++) {
        $prop = $this->props[$i];
        if($this->dirty[$prop] && array_search($prop, $this->extraProps) === false) {
          $sql .= sprintf('%s %s = "%s"', $changeCount > 0 ? ',' : '', $prop, mysql_real_escape_string($this->$prop));
          $changeCount++;
        }
      }
      $sql .= sprintf(' where id = "%d"', $this->id);
      if($changeCount == 0) return;
    } else {
      $fields = "";
      $values = "";
      for($i = 0; $i < count($this->props); $i++) {
        $prop = $this->props[$i];
        if(array_search($prop, $this->extraProps) === false) {
          $fields .= sprintf('%s "%s"', ($fields != '' ? ', ' : ''), mysql_real_escape_string($prop));
          $values .= sprintf('%s "%s"', ($values != '' ? ', ' : ''), mysql_real_escape_string($this->vals[$prop]));
        }
      }
      $sql = sprintf('insert into #__podcasts (%s) values (%s)', $fields, $values);
    }
    print $sql;
    $database->setQuery($sql);
    //$database->query();
  }

  function updateFromRequest($request) {
    for($i = 0; $i < count($this->props); $i++) {
      $prop = $this->props[$i];
      $val = mosGetParam($request, $prop, undefined);
      if($val != undefined) $this->$prop = $val;
    }
  }

  /**
   * Merge without dirtying any variables marking them to be stored
   */
  function mergeClean($from) {
    foreach($from as $key => $prop) {
      if(array_search($key, self::$props) !== false) {
        $this->vals[$key] = $prop;      
      }
    }
  }

  function maxPages($channel) {
    global $database;
    $sql = 'select count(*) from #__podcasts';
    if($channel != undefined) {
      $sql = sprintf('%s left join #__podcast_channel on #__podcast_channel.podcast_id = #__podcasts.id' .
                     ' where #__podcast_channel.channel_abbr = "%s"',
                     $sql, mysql_real_escape_string($channel));
    }
    $database->setQuery($sql);
    return $database->loadResult();
  }
}
?>
