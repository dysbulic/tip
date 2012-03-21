#include <gtk/gtk.h>

/* You can compile the above program with gcc using:
 *  gcc gtk_basic.c -o gtk_basic $(pkg-config --cflags --libs gtk+-2.0)
 */

int main(int argc, char *argv[])
{
  GtkWidget *window;
  
  gtk_init (&argc, &argv);
  
  window = gtk_window_new (GTK_WINDOW_TOPLEVEL);
  gtk_widget_show  (window);
  
  gtk_main ();
  
  return 0;
}
