import java.io.BufferedReader;
import java.io.FileReader;
import java.io.IOException;
import java.util.*;

public class crab_cups {

    public final static ArrayList<String> values = new ArrayList<>();

    public static String solve_part_one() {
        List<Integer> cups = new ArrayList<>();
        for (int i = 0; i < values.get(0).length(); i++) {
            cups.add(Character.getNumericValue(values.get(0).charAt(i)));
        }
        Integer current = cups.get(0);
        for (int turns = 0; turns < 100; turns++) {
            Integer currIndex = cups.indexOf(current);
            List<Integer> removed = new ArrayList<>();
            for (int i = 1; i < 4; i++) {
                removed.add(cups.get((currIndex + i) % cups.size()));
            }
            for (Integer cup : removed) {
                cups.remove(cup);
            }

            Integer destination = current - 1;
            if (destination < Collections.min(cups)) {
                destination = Collections.max(cups);
            }
            if (removed.contains(destination)) {
                while (true) {
                    destination--;
                    if (cups.contains(destination)) {
                        break;
                    }
                    if (destination < Collections.min(cups)) {
                        destination = Collections.max(cups);
                        break;
                    }
                }
            }
            Integer destIndex = cups.indexOf(destination);
            for (int i = 0; i < 3; i++) {
                cups.add(destIndex + 1, removed.remove(removed.size() - 1));
            }
            current = cups.get((cups.indexOf(current) + 1) % cups.size());
        }
        Integer oneIndex = cups.indexOf(1);
        Integer index = oneIndex + 1;
        String out = "";
        while (index != oneIndex) {
            out += cups.get(index++);
            index %= cups.size();
        }
        return out;
    }

    public static long solve_part_two() {
        List<Integer> orderedCups = new ArrayList<>();
        for (int i = 0; i < values.get(0).length(); i++) {
            orderedCups.add(Character.getNumericValue(values.get(0).charAt(i)));
        }
        Map<Integer, Integer> cups = new HashMap<>();
        for (int i = 0; i < orderedCups.size() - 1; i++) {
            cups.put(orderedCups.get(i), orderedCups.get(i+1));
        }
        cups.put(orderedCups.get(orderedCups.size() - 1), Collections.max(cups.keySet()) + 1);
        for (int i = Collections.max(cups.keySet()) + 1; i < 1000000; i++) {
            cups.put(i, i + 1);
        }
        cups.put(1000000, orderedCups.get(0));
        Integer current = orderedCups.get(0);
        for (int turns = 0; turns < 10000000; turns++) {
            List<Integer> removed = new ArrayList<>();
            Integer toRemove = current;
            for (int i = 0; i < 3; i++) {
                toRemove = cups.get(toRemove);
                removed.add(toRemove);
            }
            cups.put(current, cups.get(toRemove));
            Integer destination = current - 1;
            List<Integer> minCircle = new ArrayList<>();
            for (Integer i = 1; i < 4; i++) {
                if (!removed.contains(i)) {
                    minCircle.add(i);
                }
            }
            List<Integer> maxCircle = new ArrayList<>();
            for (Integer i = 999997; i < 1000001; i++) {
                if (!removed.contains(i)) {
                    maxCircle.add(i);
                }
            }
            if (destination < minCircle.get(0)) {
                destination = maxCircle.get(maxCircle.size() - 1);
            } else if (removed.contains(destination)) {
                while (true) {
                    destination--;
                    if (!removed.contains(destination)) {
                        break;
                    }
                    if (destination < minCircle.get(0)) {
                        destination = maxCircle.get(maxCircle.size() - 1);
                        break;
                    }
                }
            }
            Integer currAdjacent = cups.get(destination);
            cups.put(destination, removed.get(0));
            cups.put(removed.get(1), removed.get(2));
            cups.put(removed.get(2), currAdjacent);
            current = cups.get(current);
        }
        Integer first = cups.get(1);
        return Long.valueOf(first) * Long.valueOf(cups.get(first));
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
