/*
 * A simple Gnome program, outside of GNOME tree, not using i18n
 * uiinfo.c
 */
/* the very basic gnome include */
#include <gnome.h>

/* a callback for the buttons */
static void
a_callback(GtkWidget *button, gpointer data)
{
  /*just print a string so that we know we got there*/
  g_print("Inside Callback\n");
}

GnomeUIInfo file_menu[] = {
  GNOMEUIINFO_MENU_EXIT_ITEM(gtk_main_quit,NULL),
        GNOMEUIINFO_END
};

GnomeUIInfo some_menu[] = {
  GNOMEUIINFO_ITEM_NONE("_Menuitem","Just a menuitem",
			a_callback),
  GNOMEUIINFO_SEPARATOR,
  GNOMEUIINFO_ITEM_NONE("M_enuitem2","Just a menuitem",
			a_callback),
        GNOMEUIINFO_END
};

GnomeUIInfo menubar[] = {
  GNOMEUIINFO_MENU_FILE_TREE(file_menu),
  GNOMEUIINFO_SUBTREE("_Some menu",some_menu),
        GNOMEUIINFO_END
};

GnomeUIInfo toolbar[] = {
  GNOMEUIINFO_ITEM_STOCK("Exit","Exit the application",
			 gtk_main_quit,
			 GNOME_STOCK_PIXMAP_EXIT),
        GNOMEUIINFO_END
};

int
main(int argc, char *argv[])
{
  GtkWidget *app;
  GtkWidget *button;
  GtkWidget *hbox;
  GtkWidget *label;

  /* Initialize GNOME, this is very similar to gtk_init */
  gnome_init ("menu-basic-example", "0.1", argc, argv);
        
  /* Create a Gnome app widget, which sets up a basic
     window for your application */
  app = gnome_app_new ("menu-basic-example",
		       "Basic GNOME Application");

  /* bind "delete_event", which is the event we get when
           the user closes the window with the window manager,
           to gtk_main_quit, which is a function that causes
           the gtk_main loop to exit, and consequently to quit
           the application */
  gtk_signal_connect (GTK_OBJECT (app), "delete_event",
		      GTK_SIGNAL_FUNC (gtk_main_quit),
		      NULL);

  /*make a label as the contents*/
  label = gtk_label_new("BLAH BLAH BLAH BLAH BLAH");

  /*add the label as contents of the window*/
  gnome_app_set_contents (GNOME_APP (app), label);

  /*create the menus for the application*/
  gnome_app_create_menus (GNOME_APP (app), menubar);

  /*create the tool-bar for the application*/
  gnome_app_create_toolbar (GNOME_APP (app), toolbar);

  /* show everything inside this app widget and the app
     widget itself */
  gtk_widget_show_all(app);
        
  /* enter the main loop */
  gtk_main ();
        
  return 0;
}
