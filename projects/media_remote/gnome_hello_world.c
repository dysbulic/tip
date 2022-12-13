/*
 * A simple Gnome program, outside of GNOME tree, not using i18n
 * buttons.c
 */
/* the very basic gnome include */
#include <gnome.h>

/* a callback for the buttons */
static void
button_clicked(GtkWidget *button, gpointer data)
{
  /* the string to print is passed though the data field
     (which is a void *) */
  char *string = data;
  /* print a string on the standard output */
  g_print(string);
}

/* called when the user closes the window */
static gint
delete_event(GtkWidget *widget, GdkEvent *event, gpointer data)
{
  /* signal the main loop to quit */
  gtk_main_quit();
  /* return FALSE to continue closing the window */
  return FALSE;
}

int
main(int argc, char *argv[])
{
  GtkWidget *app;
  GtkWidget *button;
  GtkWidget *hbox;

  /* Initialize GNOME, this is very similar to gtk_init */
  gnome_init ("buttons-basic-example", "0.1", argc, argv);
        
  /* Create a Gnome app widget, which sets up a basic window
     for your application */
  app = gnome_app_new ("buttons-basic-example",
		       "Basic GNOME Application");

  /* bind "delete_event", which is the event we get when
           the user closes the window with the window manager,
           to gtk_main_quit, which is a function that causes
           the gtk_main loop to exit, and consequently to quit
           the application */
  gtk_signal_connect (GTK_OBJECT (app), "delete_event",
		      GTK_SIGNAL_FUNC (delete_event),
		      NULL);

  /* create a horizontal box for the buttons and add it
     into the app widget */
  hbox = gtk_hbox_new (FALSE,5);
  gnome_app_set_contents (GNOME_APP (app), hbox);

  /* make a button and add it into the horizontal box,
     and bind the clicked event to call button_clicked */
  button = gtk_button_new_with_label("Button 1");
  gtk_box_pack_start (GTK_BOX(hbox), button, FALSE, FALSE, 0);
  gtk_signal_connect (GTK_OBJECT (button), "clicked",
		      GTK_SIGNAL_FUNC (button_clicked),
		      "Button 1\n");

  /* and another button */
  button = gtk_button_new_with_label("Button 2");
  gtk_box_pack_start (GTK_BOX(hbox), button, FALSE, FALSE, 0);
  gtk_signal_connect (GTK_OBJECT (button), "clicked",
		      GTK_SIGNAL_FUNC (button_clicked),
		      "Button 2\n");
        
  /* show everything inside this app widget and the app
     widget itself */
  gtk_widget_show_all(app);
        
  /* enter the main loop */
  gtk_main ();
        
  return 0;
}
