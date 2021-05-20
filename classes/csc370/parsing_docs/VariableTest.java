/**
 * This is a driver program to test the {@link Variable} class.
 *
 * @author Will Holcomb
 */
public class VariableTest
{
	public static void main(String[] args)
	{
		Variable v = new Variable(new String("x"), 5);
		System.out.println(v.name + " has a valueOf " + v.valueOf());
		System.out.println("x" + " has a valueOf " + Variable.dereferencer().dereference("x"));
		System.out.println("X" + " has a valueOf " + Variable.dereferencer().dereference("X"));

		System.out.println("Marking table:");
		Variable.dereferencer().mark();

		v = new Variable(new String("alpha"), 5);
		System.out.println("Variable.dereferencer().exists(\"alpha\") is "
		 + Variable.dereferencer().exists("alpha"));
		System.out.println(v.name + " has a valueOf " + v.valueOf());

		System.out.println("Demarking table:");
		Variable.dereferencer().demark();

		System.out.println("Variable.dereferencer().exists(\"alpha\") is "
		 + Variable.dereferencer().exists("alpha"));

		System.out.println(v.name + " has a valueOf " + v.valueOf());
		System.out.println("x" + " has a valueOf " + Variable.dereferencer().dereference("x"));	
	}
}