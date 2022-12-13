import com.sun.labs.aura.util.io.OutFileGenerator;
import com.sun.labs.aura.util.io.PairPermutationJoiner;
import com.sun.labs.aura.util.io.SplitKeyedInputStream;
import com.sun.labs.aura.util.io.KeyedOutputStream;
import com.sun.labs.aura.util.io.SplitKeyedOutputStream;
import com.sun.labs.aura.util.io.Combiner;
import com.sun.labs.aura.util.io.Record;
import com.sun.labs.aura.util.io.RecordSet;
import com.sun.labs.aura.util.io.CartesianProductMerger;
import java.util.List;
import java.lang.reflect.Array;
import java.util.ArrayList;
import java.io.File;
import java.io.FileReader;
import java.io.FileInputStream;
import java.io.RandomAccessFile;
import java.io.OutputStreamWriter;
import java.io.IOException;

public class TestCombiner {
    public static void main(String... args) throws IOException {
	if(args.length < 3) {
	    throw new IllegalArgumentException("Usage: " + TestCombiner.class.getName() + " <base filename> <extension> <data directory>");
	}

	OutFileGenerator inFiles = new OutFileGenerator(args[0], args[1], args[2]);
	OutFileGenerator outFiles = new OutFileGenerator("ProductComponents.", args[1], args[2]);
	List<RecordSet> recordSets = new ArrayList<RecordSet>(args.length);

	for(String filename : new File(outFiles.dataDir).list()) {
	    if(inFiles.getFileId(filename) != null) {
		RandomAccessFile input = new RandomAccessFile(outFiles.dataDir + filename, "r");
		SplitKeyedInputStream source = new SplitKeyedInputStream(input, "\t", 1);
		recordSets.add(new RecordSet(source, inFiles.getFileId(filename)));
	    }
	}

	PairPermutationJoiner joiner = new PairPermutationJoiner(new DotProductComponentsMerger(), outFiles);
	joiner.permutePairs(recordSets);
    }

    public static void dumpSets(List<RecordSet> recordSets, KeyedOutputStream output)
	throws IOException {

	if(output == null) {
	    OutputStreamWriter writer = new OutputStreamWriter(System.out);
	    output = new SplitKeyedOutputStream<String, List<String>>(writer);
	}

	for(RecordSet<Object, Object> records : recordSets) {
	    for(Record record : records) {
		output.write(record);
	    }
	}
    }
}

class DotProductComponentsMerger<K, V> extends CartesianProductMerger<K, V> {
    public void merge(KeyedOutputStream output, Record<K, V>... records) throws IOException {
	List<String>[] values = (List<String>[])Array.newInstance(List.class, records.length);
	for(int index = 0; index < records.length; index++) {
	    values[index] = (List<String>)records[index].getValue();
	}
	int product = Integer.parseInt(values[0].get(2)) * Integer.parseInt(values[1].get(2));
	output.write(values[0].get(0) + "." + values[1].get(0), product);
    }
}
