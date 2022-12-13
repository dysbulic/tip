import java.awt.*;
import javax.swing.*;
import java.awt.event.*;

public class RenderFrame extends JFrame
{
   private Scene scene;
   private JPanel pnl;
   private ControlFrame controls;
   private Container cont;

   public RenderFrame(int width, int height)
   {
      super("Fun with 3D!");
      setSize(width,height);

      pnl=new JPanel();

      cont=getContentPane();
      cont.setLayout(new BorderLayout());
      cont.add(pnl,BorderLayout.CENTER);

      //put this window towards lower right
      Dimension screenSize=getToolkit().getScreenSize();
      int screenWidth=screenSize.width;
      int screenHeight=screenSize.height;
      this.setLocation(screenWidth-width,screenHeight-height);

      scene=new Scene();

      controls=new ControlFrame(scene,this,pnl);
      controls.addWindowListener(new WindowAdapter() { public void windowClosing(WindowEvent event) { System.exit(0);} });

      addWindowListener(new WindowAdapter() { public void windowClosing(WindowEvent event) { System.exit(0);} });
      show();

      controls.show();
      
   }

   public void paint(Graphics g)
   {

      Graphics gPanel=pnl.getGraphics();
      int width=pnl.getSize().width;
      int height=pnl.getSize().height;
      gPanel.setColor(java.awt.Color.black);
      gPanel.fillRect(0,0,width,height);

      //render the scene
      scene.render(gPanel, width, height);
   }

   public static void main (String[] args)  
   {
      int xPixels=640;
      int yPixels=480;
      SimpleDialogs.useSystemStyle();
      RenderFrame frame=new RenderFrame(xPixels,yPixels);
   }

}