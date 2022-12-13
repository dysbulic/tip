import javax.swing.*;
import java.awt.*;
import java.awt.event.*;


public class EasyGridBag extends GridBagLayout
{
   private GridBagConstraints c;
   private int rows;
   private int columns;

   public EasyGridBag(int numRows, int numColumns)
   {
      c=new GridBagConstraints();

      int[] rows=new int[numRows];
      int[] columns=new int[numColumns];
      double[] rowWeights=new double[numRows];
      double[] columnWeights=new double[numColumns];

      for (int x=0;x<numRows;x++)
      {
         rows[x]=1;
         rowWeights[x]=1.0;
      }

      for (int x=0;x<numColumns;x++)
      {
         columns[x]=1;
         columnWeights[x]=1.0;
      }

      this.rowHeights=rows;
      this.columnWidths=columns;
      this.rowWeights=rowWeights;
      this.columnWeights=columnWeights;

      this.rows=numRows;
      this.columns=numColumns;

   }

   public void fillCell(int row, int column, Component comp, Container cont )
   {
      if (cont!=null && row>=1 && row<=rows && column>=1 && column<=columns)
      {
         c.gridy=row-1;
         c.gridx=column-1;
         c.anchor=GridBagConstraints.CENTER;
         c.fill=GridBagConstraints.NONE;
         c.gridheight=1;
         c.gridwidth=1;
         c.insets=new Insets(0,0,0,0);  //top,left,bottom,right
         cont.add(comp,c);
      }
   }

   public void fillCell(int row, int column, int alignment, Component comp, Container cont )
   {
      if (cont!=null && row>=1 && row<=rows && column>=1 && column<=columns)
      {
         c.gridy=row-1;
         c.gridx=column-1;
         c.anchor=alignment;
         c.fill=GridBagConstraints.NONE;
         c.gridheight=1;
         c.gridwidth=1;
         c.insets=new Insets(0,0,0,0);  //top,left,bottom,right
         cont.add(comp,c);
      }
   }

   public void fillCell(int row, int column, int height, int width, int fill, Component comp, Container cont )
   {
      if (cont!=null && row>=1 && row<=rows && column>=1 && column<=columns)
      {
         c.gridy=row-1;
         c.gridx=column-1;
         c.anchor=GridBagConstraints.CENTER;
         c.fill=fill;
         c.gridheight=height;
         c.gridwidth=width;
         c.insets=new Insets(0,0,0,0);  //top,left,bottom,right
         cont.add(comp,c);
      }
   }

   public void fillCell(int row, int column, int top, int left, int bottom, int right, int alignment, Component comp, Container cont)
   {
      if (row>=1 && row<=rows && column>=1 && column<=columns)
      {
         c.gridy=row-1;
         c.gridx=column-1;
         c.anchor=alignment;
         c.fill=GridBagConstraints.NONE;
         c.gridheight=1;
         c.gridwidth=1;
         c.insets=new Insets(top,left,bottom,right);  //top,left,bottom,right
         cont.add(comp,c);
      }
      
   }

   public void setRowWeight(int row, double weight)
   {
      rowWeights[row-1]=weight;
   }

   public void setColumnWeight(int column, double weight)
   {
      columnWeights[column-1]=weight;
   }


  
}