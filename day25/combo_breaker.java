import java.io.BufferedReader;
import java.io.FileReader;
import java.io.IOException;
import java.util.*;

public class combo_breaker {

    public final static ArrayList<String> values = new ArrayList<>();
    public final static int MAGIC_NUMBER = 20201227;

    public static long solve_final_part() {
        int loopSize = 0;
        int value = 1;
        int card = Integer.parseInt(values.get(0));
        int door = Integer.parseInt(values.get(1));
        while (value != card) {
            loopSize++;
            value = (value * 7) % MAGIC_NUMBER;
        }
        long result = 1;
        for (int i = 0; i < loopSize; i++) {
            result *= door;
            result %= MAGIC_NUMBER;
        }
        return result;
    }

    public static void main(String[] args) {
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
        System.out.println(solve_final_part());
    }

}
