/**
 * The <code>Variable</code> class allows the user to represent a mathematical variable.
 * A variable has a name and a value. For example, X + 3 * X = Y is a mathematical equation.
 * The characters X and Y are mathematical variables which represent numeric values.
 * Similarly, density = mass / volume is a mathematical equation with the variables density,
 * mass and volume. Because in the equation Z = (X + 3) * X the two X's are not in fact
 * different variables, but represent different references to the same value, each <code>
 * Variable</code> object does not maintain its own value. Rather there is a static member
 * {@link VariableDereferencer VariableDereferencer} <code>de</code> which maintains a list of all
 * variable names and maintains a value for each.
 *
 * @author Will Holcomb
 */
public class Variable
{
	/**
	 * An object which stores the values of the variables.This in an implementer of
	 * {@link VariableDereferencer VariableDereferencer} which stores the values
	 * for all of the variables.
	 */
	protected static VariableDereferencer d;

	/**
	 * The name of the variable in the dereferencer.
	 */
	public String name;

	/**
	 * A default constructor. By default a variable exists only as a reference.
	 * It has no name and no value.
	 */
	public Variable()
	{
		this(null);
	}

	/**
	 * Instantiates a variable with just a name. If a variable is instantiated without
	 * a value specified its value is to set to 0.
	 * 
	 * @param s a string representing the name of the variable
	 */
 	public Variable(String s)
	{
		name = s;

		if(d == null)
			setDereferencer();

		if(name != null)
			d.add(s);
	}

	/**
	 * This contructor allows a variable to be given an intial value. If a variable has
	 * already been created with the same name then that value will be overwritten in the
	 * dereferncer. There may only be one variable with a given name.
	 * 
	 * @param s a string representing the name of the variable
	 * @param value an initial value for the variable
	 */
	public Variable(String s, int value)
	{
		name = s;

		if(d == null)
			setDereferencer();

		if(name != null)
			d.assign(name, value);
	}

	/**
	 * Returns the current value of the variable. If the variable has no name or if there
	 * is no dereferencer set then it returns 0. This will eventually be replaced with a
	 * {@link VariableNotFoundException VariableNotFoundException}.
	 *
	 * @return value of the current variable
	 */
	public int valueOf()
	{
		if(d != null && name != null)
			return d.dereference(name);
		else
			return 0;
	}

	/**
	 * Returns the same as {@link #valueOf}. Added to make the {@link Variable}
	 * useable in a similar contect to {@link Integer}.
	 *
	 * @return {@link #valueOf}
	 */
	public int intValue()
	{
		return valueOf();
	}

	/**
	 * Returns the hash code from {@link java.lang.String#hashCode() String} for the name
	 * of the current <code>Variable</code>.
	 * 
	 * @see java.lang.String#hashCode()
	 * @return interger useful for implementing a hash table
	 */
	public int hashCode()
	{
		return name.hashCode();
	}

	/**
	 * Assigns a value to the reference in the {@link VariableDereferencer VariableDereferencer}
	 * with the same name as the current name. If the name is not found in the dereferencer
	 * then a new {@link VariableReference VariableReference} is added and new value assigned.
	 *
	 * @param i value to be assigned
	 */
	public void setValue(int i)
	{
		if(d != null && name != null)
			d.assign(name, i);
	}

	/**
	 * This sets the object which is responsible for storing the values of the variables
	 * and returning them. {@link VariableDereferencer VariableDereferencer} is an interface
	 * and any object may implement it. By default the variable dereferencer is set to be a
	 * {@link VariableTable VariableTable}.
	 */
	public static void setDereferencer()
	{
		d = new VariableTable();
	}

	/**
	 * This sets the object which is responsible for storing the values of the variables
	 * and returning them. {@link VariableDereferencer VariableDereferencer} is an interface
	 * and any object may implement it. 
	 *
	 * @param de class implementing {@link VariableDereferencer VariableDereferencer} to store variable values
	 */
	public static void setDereferencer(VariableDereferencer de)
	{
		d = de;
	}

	/**
	 * A {@link Variable Variable} operates along the same lines as an integer variable in C. If
	 * the value is 0 then it is considered <code>false</code>, otherwise it is considered
	 * <code>true</code>.
	 * 
	 * @return the boolean representation of the current value
	 */
	public boolean isTrue()
	{
		return valueOf() != 0;
	}

	/**
	 * A {@link Variable Variable} operates along the same lines as an integer variable in C. If
	 * the value is 0 then it is considered <code>false</code>, otherwise it is considered
	 * <code>true</code>.
	 * 
	 * @return the inverse of the boolean representation of the current value
	 */
	public boolean isFalse()
	{
		return valueOf() == 0;
	}

	/**
	 * Returns a {@link java.lang.String string} containing the name of the variable followed by its value.
	 *
	 * @return String of the form: <i>name</i>: [<i>value</i>]
	 */
	public String toString()
	{
		return (name + ": [" + valueOf() + "]");
	}

	/**
	 * @return the current {@link VariableDereferencer VariableDereferencer}
	 */
	public static VariableDereferencer dereferencer()
	{
		if(d == null)
			setDereferencer();

		return d;
	}
}