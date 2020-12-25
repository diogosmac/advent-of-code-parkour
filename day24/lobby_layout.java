import java.io.BufferedReader;
import java.io.FileReader;
import java.io.IOException;
import java.util.*;

public class lobby_layout {

    public final static ArrayList<String> values = new ArrayList<>();
    public final static Map<String, List<Integer>> hexagons = new HashMap<>();
    public final static Map<List<Integer>, Boolean> tiles = new HashMap<>();
    public final static List<List<String>> directions = new ArrayList<>();

    public static int solve_part_one() {
        for (String line : values) {
            line = line.replace("e", "e ").replace("w", "w ");
            directions.add(Arrays.asList(line.split(" ")));
        }
        for (List<String> d : directions) {
            List<Integer> c = new ArrayList(Arrays.asList(0, 0));
            for (String m : d) {
                c.set(0, c.get(0) + hexagons.get(m).get(0));
                c.set(1, c.get(1) + hexagons.get(m).get(1));
            }
            tiles.put(c, !tiles.getOrDefault(c, false));
        }
        int result = 0;
        for (Boolean v : tiles.values()) {
            if (v) result++;
        }
        return result;
    }

    public static int solve_part_two() {
        Set<List<Integer>> black = new HashSet<>();
        for (List<Integer> pos : tiles.keySet()) {
            if (tiles.get(pos)) {
                black.add(pos);
            }
        }
        for (int i = 0; i < 100; i++) {
            Set<List<Integer>> all = new HashSet<>();
            Set<List<Integer>> toRemove = new HashSet<>();
            Set<List<Integer>> toAdd = new HashSet<>();
            for (List<Integer> c : black) {
                int sum = 0;
                for (List<Integer> dir : hexagons.values()) {
                    int x = c.get(0) + dir.get(0);
                    int y = c.get(1) + dir.get(1);
                    all.add(Arrays.asList(x, y));
                    if (black.contains(Arrays.asList(x, y))) {
                        sum++;
                    }
                }
                if (sum < 1 || sum > 2) {
                    toRemove.add(c);
                }
            }
            for (List<Integer> c : all) {
                int sum = 0;
                for (List<Integer> dir : hexagons.values()) {
                    int x = c.get(0) + dir.get(0);
                    int y = c.get(1) + dir.get(1);
                    if (black.contains(Arrays.asList(x, y))) {
                        sum++;
                    }
                }
                if (sum == 2) {
                    toAdd.add(c);
                }
            }
            black.removeAll(toRemove);
            black.addAll(toAdd);
        }
        return black.size();
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
        hexagons.put("nw", Arrays.asList(-1, 1));
        hexagons.put("sw", Arrays.asList(-1,-1));
        hexagons.put("ne", Arrays.asList( 1, 1));
        hexagons.put("se", Arrays.asList( 1,-1));
        hexagons.put("e" , Arrays.asList( 2, 0));
        hexagons.put("w" , Arrays.asList(-2, 0));
        System.out.println(solve_part_one());
        System.out.println(solve_part_two());
    }
}
