import java.awt.*;
import java.util.*;

/**
 * This class is the culmination of all of these other classes. It is an extension
 * of {@link TextArea} and when the {@link #processProgram} method is called the
 * contents of of the {@link textArea} sre processed and events are thrown to
 * any registered listeners.
 *
 * @author Will Holcomb
 */
public class VALIIParser extends java.awt.TextArea
{
	/**
	 * The listener for any events generated
	 */
	protected MovementRequestListener listener;

	/**
	 * By default a {@link TextArea} of height 30 and width 20 is created
	 * with only vertical scroll bars and no text.
	 */
	public VALIIParser()
	{
		super("", 20, 30, TextArea.SCROLLBARS_VERTICAL_ONLY);
	}

	/**
	 * Creates a {@link TextArea} of height <code>rows</code> and width
	 * <code>cols</code> and only vertical scrool bars.
	 *
	 * @param rows the number of rows
	 * @param cols the number of columns
	 */
	public VALIIParser(int rows, int cols)
	{
		super("", rows, cols, TextArea.SCROLLBARS_VERTICAL_ONLY);
	}

	/**
	 * Very simply this method does the processing of the text and generates the
	 * appropriate events. The algorithm at this point is extremely simple:
	 * <br><br>
	 * The recognized keywords are:
	 * <ul>
	 *	<li>rotate</li>
	 *	<li>moveto</li>
	 *	<li>reset</li>
	 *	<li>toggleaxis</li>
	 *	<li>pickup</li>
	 *	<li>release</l>
	 *	<li>clawchange</li>
	 * </ul>
	 * <br><br>
	 * All that his function is this:
	 * <code>
	 * <ul>
	 *	<li>
	 *	 tokenize the entire program using {@link StringTokenizer} with '\n'
	 *	 as the delimiter thereby processing the program line by line
	 *	</li>
	 *	<li>while there are strings remaining in the tokenizer</li>
	 *	<ul>
	 *		<li>remove whitespace from the begninning and end of the string</li>
	 *		<li>change the string to lowercase</li>
	 *		<li>check to see if the string begins with any of the keywords</li>
	 *		<li>if the string begins with one of the keywords</li>
	 *		<ul>
	 *			<li>
	 *			 intialize the appropriate event with the remainder of the
	 *			 string other than the keyword
	 *			</li>
	 *			<li>
	 *			 throw the event; that is send it to
	 *			 {@link MovementRequestListener#movementRequested movementRequested}
	 *			 in the registered listener (which may in fact be more than one
	 *			 listener through the use of {@link MovementRequestEventMulticaster}.
	 *			</li>
	 *		</ul>
	 *		<li>
	 *		 otherwise print an error messge to {@link System#out} (this will become a
	 *		 throw of an{@link InvalidProgramElement}
	 *		</li>
	 *	</ul>
	 * </ul>
	 * </code>
	 * <br><br>
	 * The method of the processing makes it so that each line of the program is one command 
	 * (tokenizing according to '\n') and it is case insensitive (calling {@link String#toLowercase}
	 * before checking for any keywords.
	 * <br><br>
	 */
	public void processProgram()
	{
		if(listener == null)
			return;

		StringTokenizer byLine = new StringTokenizer(getText(), "\n");
		String keyword;
		String currentString;
		int lineCount = 0;
		MovementRequestEvent currentRequest = new MovementRotateRequest(this);
		boolean identified = false;

		while(byLine.hasMoreElements())
		{
			lineCount++;
			identified = false;
			currentString = byLine.nextToken();
			currentString = currentString.trim();
			currentString = currentString.toLowerCase();

			keyword = new String("rotate");

			if(!identified && currentString.startsWith(keyword))
			{
				identified = true;

				try
				{
					currentRequest = new MovementRotateRequest(this,
					 currentString.substring(keyword.length()));
				}
				catch(NumberFormatException e)
				{
					System.out.println(lineCount + ": " + "\"" + e.toString() + "\" is not a valid angle measure");
				}
			}

			keyword = new String("moveto");

			if(!identified && currentString.startsWith(keyword))
			{
				identified = true;

				try
				{
					currentRequest = new MovementMovetoRequest(this,
					 currentString.substring(keyword.length()));
				}
				catch(NumberFormatException e)
				{
					System.out.println(lineCount + ": " + "\"" + e.toString() + "\" is not a valid angle measure");
				}
			}

			keyword = new String("pickup");

			if(!identified && currentString.startsWith(keyword))
			{
				identified = true;

				try
				{
					currentRequest = new MovementPickupRequest(this,
					 currentString.substring(keyword.length()));
				}
				catch(NumberFormatException e)
				{
					System.out.println(lineCount + ": " + "\"" + e.toString() + "\" is not a valid angle measure");
				}
			}

			keyword = new String("release");

			if(!identified && currentString.startsWith(keyword))
			{
				identified = true;

				try
				{
					currentRequest = new MovementReleaseRequest(this,
					 currentString.substring(keyword.length()));
				}
				catch(NumberFormatException e)
				{
					System.out.println(lineCount + ": " + "\"" + e.toString() + "\" is not a valid angle measure");
				}
			}

			keyword = new String("clawchange");

			if(!identified && currentString.startsWith(keyword))
			{
				identified = true;

				try
				{
					currentRequest = new MovementClawchangeRequest(this,
					 currentString.substring(keyword.length()));
				}
				catch(NumberFormatException e)
				{
					System.out.println(lineCount + ": " + "\"" + e.toString() + "\" is not a valid angle measure");
				}
			}

			keyword = new String("reset");

			if(!identified && currentString.startsWith(keyword))
			{
				identified = true;

				try
				{
					currentRequest = new MovementResetRequest(this,
					 currentString.substring(keyword.length()));
				}
				catch(NumberFormatException e)
				{
					System.out.println(lineCount + ": " + "\"" + e.toString() + "\" is not a valid angle measure");
				}
			}

			keyword = new String("toggleaxis");

			if(!identified && currentString.startsWith(keyword))
			{
				identified = true;

				try
				{
					currentRequest = new MovementToggleaxisRequest(this,
					 currentString.substring(keyword.length()));
				}
				catch(NumberFormatException e)
				{
					System.out.println(lineCount + ": " + "\"" + e.toString() + "\" is not a valid angle measure");
				}
			}

			keyword = new String("assign");

			if(!identified && currentString.startsWith(keyword))
			{
				identified = true;
				currentString = currentString.substring(keyword.length());
				currentString = currentString.trim();

				Variable.dereferencer().assign(currentString.substring(0,
				 currentString.indexOf(' ')), new ArithmeticEquation(
				 currentString.substring(currentString.indexOf(' ') + 1)).solution());
			}

			if(!identified)
				System.out.println(lineCount + ": " + "\"" + currentString + "\" unknown command");
			else
				listener.movementRequested(currentRequest);
		}
	}

	/**
	 * Clears the contents of the text box.
	 */
	public void clear()
	{
		setText("");
	}

	/**
	 * Adds a listener to the events generated by {@link #processProgram}.
	 *
	 * @param l a listener
	 */
	public synchronized void addMovementRequestListener(MovementRequestListener l)
	{
		listener = MovementRequestEventMulticaster.add(listener, l);
	}

	/**
	 * Removes a listener to the events generated by {@link #processProgram}.
	 *
	 * @param l a listener
	 */
	public synchronized void removeMovementRequestListener(MovementRequestListener l)
	{
		listener = MovementRequestEventMulticaster.remove(listener, l);
	}
}