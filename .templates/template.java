import java.io.BufferedReader;
import java.io.FileReader;
import java.io.IOException;
import java.util.*;

public class template {

    public final static ArrayList<String> values = new ArrayList<>();

    public static int solve_part_one() {
        return 0;
    }

    public static int solve_part_two() {
        return 0;
    }

    public static void main (String[] args) {
        BufferedReader reader;
        try {
            reader = new BufferedReader(new FileReader("input.txt"));
            String line;
            while ((line = reader.readLine()) != null) {
                values.add(line);
            }
            reader.close();
        } catch (IOException e) {
            e.printStackTrace();
            return;
        }
        System.out.println(solve_part_one());
        System.out.println(solve_part_two());
    }

}
