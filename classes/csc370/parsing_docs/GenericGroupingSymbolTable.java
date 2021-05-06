/**
 * Creates a new {@link GroupingSymbolTable} containing the symbols '(', ')',
 * '[', ']', '{' and '}'.
 *
 * @author Will Holcomb
 */
public class GenericGroupingSymbolTable extends GroupingSymbolTable
{
	/**
	 * Creates a new {@link GroupingSymbolTable} containing the symbols '(', ')',
	 * '[', ']', '{' and '}'.
	 */
	public GenericGroupingSymbolTable()
	{
		super(6);

		add(new GroupingSymbol("(", ")", true));
		add(new GroupingSymbol("(", ")", false));
		add(new GroupingSymbol("{", "}", true));
		add(new GroupingSymbol("{", "}", false));
		add(new GroupingSymbol("[", "]", true));
		add(new GroupingSymbol("[", "]", false));
	}
}