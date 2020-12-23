import java.io.BufferedReader;
import java.io.FileReader;
import java.io.IOException;
import java.util.*;
import java.util.Map.Entry;

public class allergen_assessment {

    public final static List<String> values = new ArrayList<>();
    public final static Map<String, String> allergenToIngredient = new TreeMap<>();

    public static List<String> intersection(List<String> a, List<String> b) {
        Set<String> s1 = new HashSet<String>(a);
        Set<String> s2 = new HashSet<String>(b);
        s1.retainAll(s2);
        return new ArrayList<>(s1);
    }

    public static int solve_part_one() {
        HashMap<String, List<String>> candidates = new HashMap<>();
        Set<String> allergens = new HashSet<>();
        Set<String> ingredients = new HashSet<>();
        Set<String> allergenicIngredients = new HashSet<>();
        List<List<String>> ingredientLines = new ArrayList<>();
        for (String line : values) {
            String[] parts = line.split(" \\(contains ");
            List<String> lineIngredients = Arrays.asList(parts[0].split(" "));
            for (String i : lineIngredients) {
                ingredients.add(i);
            }
            List<String> lineAllergens = Arrays.asList(parts[1].replace(")", "").split(", "));
            for (String a : lineAllergens) {
                allergens.add(a);
            }
            ingredientLines.add(lineIngredients);

            for (String allergen : lineAllergens) {
                if (!candidates.containsKey(allergen)) {
                    candidates.put(allergen, lineIngredients);
                } else {
                    List<String> current = candidates.get(allergen);
                    List<String> newIngredients = intersection(current, lineIngredients);
                    candidates.put(allergen, newIngredients);
                }
            }
        }
        while (allergenToIngredient.size() != allergens.size()) {
            for (Entry<String, List<String>> candidate : candidates.entrySet()) {
                String candidateAllergen = candidate.getKey();
                List<String> candidateIngredients = candidate.getValue();
                candidateIngredients.removeAll(allergenicIngredients);
                if (candidateIngredients.size() == 1) {
                    String ingredient = candidateIngredients.get(0);
                    allergenToIngredient.put(candidateAllergen, ingredient);
                    allergenicIngredients.add(ingredient);
                }
            }
        }
        Set<String> nonAllergenics = ingredients;
        nonAllergenics.removeAll(allergenicIngredients);
        int result = 0;
        for (List<String> line : ingredientLines) {
            Set<String> l = new HashSet<>(line);
            l.retainAll(nonAllergenics);
            result += l.size();
        }
        return result;
    }

    public static String solve_part_two() {
        List<String> sortedIngredients = new ArrayList<>();
        for (Entry<String, String> entry : allergenToIngredient.entrySet()) {
            sortedIngredients.add(entry.getValue());
        }
        return String.join(",", sortedIngredients);
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
