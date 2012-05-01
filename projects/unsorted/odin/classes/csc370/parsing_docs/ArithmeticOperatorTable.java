/**
 * Class which represents all of the operators that may be allowed in a simple
 * arithmetic equation. + ({@link AdditionOperator AdditionOperator}),
 * * ({@link MultiplicationOperator MultiplicationOperator}),
 * / ({@link DivisionOperator DivisionOperator}),
 * - ({@link SubtractionOperator SubtractionOperator}), and
 * - ({@link NegationOperator NegationOperator})).
 * <br><br>
 * Also to demonstrate the capacity of multiple character delimiters in
 * {@link AdaptedStringTokenizer AdaptedStringTokenizer} the operator
 * {@link SineOperator SineOperator} is also included.
 * 
 * @author Will Holcomb
 */
public class ArithmeticOperatorTable extends OperatorTable
{
	/**
	 * Constructs a new {@link OperatorTable OperatorTable} with the
	 * appropriate entries.
	 */
	public ArithmeticOperatorTable()
	{
		super(6);
		add(new AdditionOperator());
		add(new SubtractionOperator());
		add(new MultiplicationOperator());
		add(new DivisionOperator());
		add(new NegationOperator());
		add(new SineOperator());
	}
}