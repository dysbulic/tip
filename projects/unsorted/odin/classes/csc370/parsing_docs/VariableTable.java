/**
 * The {@link VariableTable} class acts in conjunction with the {@link Variable}
 * class. For an introduction as to the nature of this relationship see the
 * introduction to {@link Variable}.
 * <br><br>
 */ 
public class VariableTable implements VariableDereferencer
{
	/**
	 * The current maximum number of elements possible
	 */
	protected int capacity = 0;

	/**
	 * The current number of elements
	 */
	protected int size = 0;

	/**
	 * The elements contained in the table
	 */
	protected VariableReference [] variables;

	/**
	 * The current maximum number of marks possible
	 *
	 * @see #mark
	 */
	protected int marksCapacity = 0;

	/**
	 * The current number of marks
	 *
	 * @see #mark
	 */
	protected int marksSize = 0;

	/**
	 * The set of marks in the table
	 *
	 * @see #mark
	 */
	int[] marks;

	/**
	 * Creates a new empty table with a capacity of 20 elements
	 */
	public VariableTable()
	{
		this(20);
	}

	/**
	 * Creates a new empty table with a capacity of i elements.
	 * If i is 0 then the capacity is set to 1.
	 */
	public VariableTable(int i)
	{
		capacity = i;

		if(capacity == 0)
			capacity++;

		variables = new VariableReference[capacity];

		marksCapacity = 5;
		marks = new int [marksCapacity];
	}

	/**
	 * Adds a {@link VariableReference} to the table. If there is not room in the table for
	 * the symbol then (in the fashion of {@link java.util.Vector}) the capacity is
	 * doubled thereby making space.
	 *
	 * @param o reference to be added
	 */
	public void add(VariableReference v)
	{
		if(size == capacity)
		{
			VariableReference[] tempTable = new VariableReference[capacity * 2];
			for(int i = 0; i < size; i++)
				tempTable[i] = variables[i];
			variables = tempTable;
			capacity *= 2;
		}

		variables[size] = v;
		size++;
	}

	/**
	 * If <code>s</code> isn't already in the table, a new {@link VariableReference}
	 * with a name <code>s</code> and value 0 is added.
	 *
	 * @param s name of a variable to add
	 */
	public void add(String s)
	{
		if(!isInTable(s))
			add(new VariableReference(s, 0));
	}

	/**
	 * Assigns <code>value</code> to the reference of <code>name</code>. If there is no
	 * reference of <code>name</code> in the table then a new one is added with a value of
	 * <code>value</code>.
	 *
	 * @param name the name of the variable
	 * @param value the value to set <code>name</code> equal to
	 */
	public void assign(String name, int value)
	{
		boolean assigned = false;

		for(int i = 0; i < size && !assigned; i++)
			if(variables[i].name.equalsIgnoreCase(name))
			{
				variables[i].value = value;
				assigned = true;
			}

		if(!assigned)
			add(new VariableReference(name, value));
	}

	/**
	 * Checks to see if there is a variable with name <code>s</code> in the table
	 *
	 * @return <code>true</code> if <code>s</code> is in the table
	 */
	public boolean isInTable(String s)
	{
		boolean found = false;

		for(int i = 0; i < size && !found; i++)
			if(variables[i].name.equalsIgnoreCase(s))
				found = true;

		return found;
	}

	/**
	 * Same as {@link #isInTable}
	 *
	 * @return the same as {@link #isInTable}
	 */
	public boolean exists(String s)
	{
		return isInTable(s);
	}

	/**
	 * Returns the value for the variable with name of <code>name</code>.
	 * If <code>name</code> is not in the table then a
	 * {@link NotATableElementExcepetion} will be thrown.
	 *
	 * @param name variable name to derefernce
	 */
	public int dereference(String name)
	{
		for(int i = 0; i < size; i++)
			if(variables[i].name.equalsIgnoreCase(name))
				return variables[i].value;

		return 0;
	}

	/**
	 * Places a mark in the table. Marking is a process which allows for the localization
	 * of variables. When a point in the processing is reached where you wish to localize
	 * variables place a mark in the table and then when the table is {@link #demark demarked}
	 * all variables put in since the mark are removed from the table.
	 */ 
	public void mark()
	{
		if(marksSize == marksCapacity)
		{
			int[] tempMarks = new int[marksCapacity * 2];
			for(int i = 0; i < marksSize; i++)
				tempMarks[i] = marks[i];
			marks = tempMarks;
			marksCapacity *= 2;
		}

		marks[marksSize] = size;

		System.out.println("Mark set at " + size);
		marksSize++;
	}

	/**
	 * Removes all variables from the table added since the last {@link #mark}.
	 * If there are no marks then the talbe is cleared.
	 */
	public void demark()
	{
		if(marksSize <= 0)
			size = 0;
		else
		{
			size = marks[marksSize - 1];
			marksSize--;
		}
	}
}