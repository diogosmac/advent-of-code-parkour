import java.io.BufferedReader;
import java.io.FileReader;
import java.io.IOException;
import java.util.*;

public class crab_combat {

    private static class Winner {
        private Integer key;
        private Deque<Integer> value;
        Winner(Integer key, Deque<Integer> value) {
            this.key = key; this.value = value;
        }
        public Integer getKey() { return this.key; }
        public Deque<Integer> getValue() { return this.value; }
    }

    public final static ArrayList<String> values = new ArrayList<>();
    public final static List<Integer> deck1 = new ArrayList<>();
    public final static List<Integer> deck2 = new ArrayList<>();

    public static Deque<Integer> combat(Deque<Integer> first, Deque<Integer> second) {
        while ((first.size() != 0) && (second.size() != 0)) {
            Integer firstCard = first.pop();
            Integer secondCard = second.pop();
            if (firstCard > secondCard) {
                first.addLast(firstCard);
                first.addLast(secondCard);
            } else if (firstCard < secondCard) {
                second.addLast(secondCard);
                second.addLast(firstCard);
            } else {
                System.out.println("We're not supposed to have equal cards!!");
                System.exit(1);
            }
        }
        return (first.size() != 0 ? first : second);
    }

    public static int count_score(Deque<Integer> winner) {
        int score = 0;
        for (int i = 1; winner.size() > 0; i++) {
            score += winner.removeLast() * i;
        }
        return score;
    }

    public static int solve_part_one() {
        Deque<Integer> first = new LinkedList<>(deck1);
        Deque<Integer> second = new LinkedList<>(deck2);
        Deque<Integer> winner = combat(first, second);
        return count_score(winner);
    }

    public static List<Integer> build_round(Deque<Integer> first, Deque<Integer> second) {
        List<Integer> round = new ArrayList<>(first);
        round.add(Integer.MAX_VALUE); // fancy separator
        round.addAll(second);
        return round;
    }

    public static Deque<Integer> get_first_n(Deque<Integer> deque, Integer n) {
        Deque<Integer> ret = new LinkedList<>();
        Iterator<Integer> it = deque.iterator();
        for (int i = 0; i < n; i++) {
            if (!it.hasNext()) {
                return ret;
            }
            ret.add(it.next());
        }
        return ret;
    }

    public static Winner recursive_combat(Deque<Integer> first, Deque<Integer> second) {
        Set<List<Integer>> roundHistory = new HashSet<>();
        while ((first.size() != 0) && (second.size() != 0)) {
            List<Integer> round = build_round(first, second);
            if (roundHistory.contains(round)) {
                return new Winner(1, first);
            }
            roundHistory.add(round);
            Integer firstCard = first.pop();
            Integer secondCard = second.pop();
            if ((first.size() >= firstCard) && (second.size() >= secondCard)) {
                Winner win = recursive_combat(get_first_n(first, firstCard), get_first_n(second, secondCard));
                if (win.getKey() == 1) {
                    first.addLast(firstCard);
                    first.addLast(secondCard);
                } else if (win.getKey() == 2) {
                    second.addLast(secondCard);
                    second.addLast(firstCard);
                } else {
                    System.out.println("**Someone** has to win!!");
                    System.exit(2);
                }
            } else {
                if (firstCard > secondCard) {
                    first.addLast(firstCard);
                    first.addLast(secondCard);
                } else if (firstCard < secondCard) {
                    second.addLast(secondCard);
                    second.addLast(firstCard);
                } else {
                    System.out.println("We're not supposed to have equal cards!!");
                    System.exit(1);
                }
            }
        }
        return (first.size() != 0 ? new Winner(1, first) : new Winner(2, second));
    }

    public static int solve_part_two() {
        Deque<Integer> first = new LinkedList<>(deck1);
        Deque<Integer> second = new LinkedList<>(deck2);
        Deque<Integer> winner = recursive_combat(first, second).getValue();
        return count_score(winner);
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
        int i = 1;
        String line;
        while (!(line = values.get(i++)).equals("")) {
            deck1.add(Integer.parseInt(line));
        }
        i++;
        while (!(line = values.get(i++)).equals("")) {
            deck2.add(Integer.parseInt(line));
        }
        System.out.println(solve_part_one());
        System.out.println(solve_part_two());
    }

}
