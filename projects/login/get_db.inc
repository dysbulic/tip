<?php
if(isset($_GET['db'])) {
  $db = get_db($_GET['db']);
  if($db) {
    $_SESSION['db'] = $_GET['db'];
    dbx_close($db);
  }
}

if(!isset($_SESSION['db'])) {
?>
    <p>First you need to choose a <acronym title="DataBase Management System">DBMS</acronym> that you would like to use:</p>
<form id="dbtype" action="<?php print $_SERVER['SCRIPT_NAME'] ?>">
      <select name="db">
<?php
$dbtypes = array('PostgreSQL' => 'pgsql', 'MySQL' => 'mysql', 'SQLite' => 'sqlite');
foreach($dbtypes as $name => $id) {
  print '<option value="' . $id . '">' . $name . '</option>' . "\n";
}
?>
      </select>
      <input type="submit" value="Go" />
    </form>
<?php
}
?>
