<?php
class Comment {
  public static $props = array('id', 'podcast_id', 'user_id', 'date', 'subject', 'orig_body', 'body', 'priority');
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
    case 'user':
      if(!array_key_exists('user', $this->vals)) {
        $this->vals['user'] = new mosUser($database);
        $this->vals['user']->load($this->vals['user_id']);
      }
      break;
    }
    if(array_key_exists($key, $this->vals)) {
      return $this->vals[$key];
    }
    return undefined;
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

  public function loadComments($podcast_id) {
    global $database;
    $sql = sprintf('select * from #__comments where podcast_id = "%s" order by priority, date',
                   mysql_real_escape_string($podcast_id));
    $database->setQuery($sql);
    $comments = array();
    $commentLists = $database->loadObjectList();
    if($comments != null) {
      foreach($commentList as $comm) {
        $comment - new Comment();
        $comment->mergeClean($comm);
        //if(array_key_exists('channel', $params)) $podcast->channelAbbr = $params['channel'];
        array_push($comments, $comm);
      }
    }
    return $comments;
  }
}
