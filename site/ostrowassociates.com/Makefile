FIFO = "mysql.commands.fifo"

clean:
	$(RM) *~

resetdb: drupal.mysql
	mysql -u drupal -p drupal < $<
