<?php
require_once('db.php');

class UserNotFoundException extends Exception { }
class DuplicateUserException extends Exception { }

class User {
  protected $name;
  protected $email;
  protected $username;
  protected $pass_sha1;
  protected $is_new = true;
  
  function __construct($username = '',
		       $name = '',
		       $email = '',
		       $pass_sha1 = '') {
    $this->username = $username;
    $this->name = $name;
    $this->email = $email;
    $this->pass_sha1 = $pass_sha1;
  }
  
  public function setName($name) {
    $this->name = $name;
  }

  public function getName() {
    return $this->name;
  }

  public function setUsername($username) {
    $this->username = $username;
  }

  public function getUsername() {
    return $this->username;
  }

  public function setEmail($email) {
    $this->email = $email;
  }

  public function getEmail() {
    return $this->email;
  }

  public function setPasswordHash($pass_sha1) {
    $this->pass_sha1 = $pass_sha1;
  }

  public function getPasswordHash() {
    return $this->pass_sha1;
  }

  public function setIsNew($is_new) {
    $this->is_new = $is_new;
  }

  public function isNew() {
    return $this->is_new;
  }

  static function fromUsername($username) {
    $result = mysql_magic('select username, name, email, pass_sha1 from users where username = ? limit 1', $username);
    if(!$result) {
      throw new UserNotFoundException('No user found for username: ' . $username);
    }
    $user = new User($result['username'],
		     $result['name'],
		     $result['email'],
		     $result['pass_sha1']);
    $user->setIsNew(false);
    return $user;
  }

  public function save() {
    if($this->is_new) {
      try {
        $user = User::fromUsername($this->username);
        throw new DuplicateUserException("Duplicate Username: " . $this->username);
      } catch(UserNotFoundException $e) {
        $result = mysql_magic('insert into users (username, name, email, pass_sha1) values (?, ?, ?, ?)',
	                      $this->username, $this->name, $this->email, $this->pass_sha1);
      
        $this->setIsNew(false);
      }
    } else {
      $result = mysql_magic('update users set name = ?, email = ?, pass_sha1 = ? where username = ?',
			    $this->name, $this->email, $this->pass_sha1, $this->username);
    }
  }

  public function __toString() {
    return $this->username;
  }
}

/*
header("Content-type: text/plain");

try {
  $user = new User();
  $user->setUsername('test');
  $user->setName('Test User');
  $user->setEmail('test@test.com');
  $user->setPasswordHash(sha1('test'));
  $user->save();

  print "T: " . $user->getName() . "\n";

  $user = User::fromUsername('test');

  print "T: $user\n";

  $user->setName('Testy McTesterson');
  $user->save();

  print "T: " . $user->getName() . "\n";
} catch(Exception $e) {
  print $e->getMessage();
}

try {
  $user = User::fromUsername('tester');
  print $user;
} catch(Exception $e) {
  print $e->getMessage();
}
*/
