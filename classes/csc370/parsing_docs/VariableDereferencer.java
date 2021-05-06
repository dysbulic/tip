/**
 * Interface implemented by any object which wishes to be able to store variable
 * values. This class may work in conjunction with {@link Variable} to maintain a
 * single list of all the variable names and values.
 * <br><br>
 * The basic concept is that the implementing class keeps a list of variable names
 * and associated values and gives the user access to those values.
 * 
 * @author Will Holcomb
 */
public interface VariableDereferencer
{
	/**
	 * The class implementing this should be able to return the value
	 * associated with <code>name</code>.
	 *
	 * @param name variable to return the value of
	 */
	public abstract int dereference(String name);

	/**
	 * Should allow the user to assign the value of <code>value</code>
	 * to the variable of <code>name</name>.
	 *
	 * @param name variable to assign to
	 * @param value value to be assigned to <code>name</code>
	 */
	public abstract void assign(String name, int value);

	/**
	 * Add a variable with name <code>name</code> to the dereferencer.
	 * The implementer should be wary of having multiple variables with
	 * the same name becasue of potential lost data.
	 *
	 * @param name variable to add
	 */
	public abstract void add(String name);

	/**
	 * Adds a mark to the table. If the table is later {@link #demark demarked}
	 * all variables added since the last mark should be removed.
	 */
	public abstract void mark();

	/**
	 * Remove all variables added to the table since the last {@link #mark}
	 */
	public abstract void demark();

	/**
	 * @param s variable name to check for existence
	 * @return whether a variable with name <code>s</code> is in the
	 * dereferencer
	 */
	public abstract boolean exists(String s);
}