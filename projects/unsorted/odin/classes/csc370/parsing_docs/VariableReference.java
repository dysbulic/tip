/**
 * The primary purpose of this class is to serve as an entry in {@link VariableTable}.
 *
 * @author Will Holcomb
 */
public class VariableReference
{
	/**
	 * The name of the variable
	 */
	public String name;

	/**
	 * The value of the variable
	 */
	public int value;

	/**
	 * Calls {@link #VariableReference(String, int)} with <code>s</code> and
	 * 0. Thereby making the default value for a {@link VariableReference} 0.
	 *
	 * @param s <code>name</code> for the reference
	 */
	public VariableReference(String s)
	{
		this(s, 0);
	}

	/**
	 * @param s <code>name</code> for the reference
	 * @param i <code>value</code> for the reference
	 */
	public VariableReference(String s, int i)
	{
		name = s;
		value = i;
	}
}