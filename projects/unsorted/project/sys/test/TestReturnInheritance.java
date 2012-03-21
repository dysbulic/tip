/**
 * Tests whether the return type of an overridden method
 * may be a subclass of the parent type. Seems like it
 * should be allowed to since the subclass has an isa
 * relationship to the paernt, but I am pretty sure a
 * compile is failing because it isn't allowed to.
 */
public class TestReturnInheritance
{
    public static void main(String[] args)
    {
        Circle circle = new Circle(2);
        System.out.println("Cirle area: " + circle.getArea());
    }
}

abstract class Shape
{
    public abstract Number getArea();        
}

class Circle extends Shape
{
    Double area;

    public Circle(double radius)
    {
        area = new Double(Math.PI * radius * radius);
    }
    
    // This won't compile -- incompatible return type
    //public Double getArea()

    // This will
    public Number getArea()
    {
        return area;
    }
}
