public class CharacterTableTest {
    public static void main(String [] args) {
        CharacterTable t;
        CharacterCountTable cc;

        for(int i = 0; i < args.length; i++) {
            t = new CharacterTable(args[i]);
            t.dump();

            for(int j = 0; j <= args[i].length(); j++) {
                cc = t.countsFor(j);

                System.out.println("Counts for index " + j);
                cc.dump();
            }
        }
    }
}
