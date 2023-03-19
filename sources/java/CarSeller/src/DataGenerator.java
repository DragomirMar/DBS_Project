import java.io.BufferedReader;
import java.io.FileReader;
import java.util.*;
import java.util.Random;

public class DataGenerator {

    private Random rand = new Random();
    private List<String> first_names = new ArrayList<>();
    private List<String> surnames = new ArrayList<>();
    public List<String> addresses = new ArrayList<String>();
    private ArrayList<ArrayList<String>> cars = new ArrayList<>();
    public ArrayList<ArrayList<String>> citiesAndCountries = new ArrayList<ArrayList<String>>();

    public DataGenerator() {
        fillCitiesAndCountriesList();
        fillAddressList();
        fillListWithNames();
        fillListWithCars();
    }

    private void fillCitiesAndCountriesList() {
        String line = "";
        try {
            BufferedReader br = new BufferedReader(new FileReader("/Users/drago/UNI/5.Semester/Datenbanksysteme/Project - Car Seller/Milestone 4/java/resources/cities_countries.csv"));
            String headers = br.readLine();
            while ((line = br.readLine()) != null) {
                String[] parameters = line.split(",");
                ArrayList<String> pair = new ArrayList<>();
                pair.add(parameters[0]);
                pair.add(parameters[1]);
                citiesAndCountries.add(pair);
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    private void fillAddressList() {
        String line;
        try {
            BufferedReader br = new BufferedReader(new FileReader("/Users/drago/UNI/5.Semester/Datenbanksysteme/Project - Car Seller/Milestone 4/java/resources/austrian-streets.csv"));
            String headers = br.readLine();
            while ((line = br.readLine()) != null) {
                addresses.add(line + " " + (rand.nextInt(40) + 1));
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    private void fillListWithNames() {
        String line;
        try {
            BufferedReader br = new BufferedReader(new FileReader("/Users/drago/UNI/5.Semester/Datenbanksysteme/Project - Car Seller/Milestone 4/java/resources/name_list.csv"));
            while ((line = br.readLine()) != null) {
                String[] parameters = line.split(",");
                first_names.add(parameters[0]);
                surnames.add(parameters[1]);
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    private void fillListWithCars() {
        String line;
        try {
            BufferedReader br = new BufferedReader(new FileReader("/Users/drago/UNI/5.Semester/Datenbanksysteme/Project - Car Seller/Milestone 4/java/resources/cars.csv"));
            String headers = br.readLine();
            while ((line = br.readLine()) != null) {
                String[] parameters = line.split(",");
                ArrayList<String> pair = new ArrayList<>();
                pair.add(parameters[1]);
                pair.add(parameters[2]);
                cars.add(pair);
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    String getRandomAddress() {
        return addresses.get(rand.nextInt(addresses.size()));
    }

    Integer getCitiesAndCountriesSize() {
        return citiesAndCountries.size();
    }

    String getCountry(int i) {
        return citiesAndCountries.get(i).get(1);
    }

    String getCity(int i) {
        return citiesAndCountries.get(i).get(0);
    }

    Integer getCarsSize() {
        return cars.size();
    }

    String getMarke(int i) {
        return cars.get(i).get(0);
    }

    String getModell(int i) {
        return cars.get(i).get(1);
    }

    String getFirstName(int i) {
        return first_names.get(i);
    }

    Integer getFirstNameSize() {
        return first_names.size();
    }

    String getSurname(int i) {
        return surnames.get(i);
    }

    Integer getSurnameSize() {
        return surnames.size();
    }

}