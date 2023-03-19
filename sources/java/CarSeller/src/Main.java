import java.util.*;

public class Main {
    public static void main(String[] args) {
        DatabaseHelper helper = new DatabaseHelper();
        DataGenerator dgen = new DataGenerator();
        Random rand = new Random();


        //Filiale insertion
        for (int i = 0; i < 200; ++i) {
            int getter = rand.nextInt(dgen.getCitiesAndCountriesSize());
            helper.insertIntoFiliale(dgen.getCity(getter), dgen.getCountry(getter), dgen.getRandomAddress());
        }

        //Autowerkstatt insertion
        List<Integer> fid = helper.getFid();
        for (int i = 0; i < 350; ++i) {
            int workerCount = rand.nextInt(6) + 1;
            long j = 6679220592L + i;
            String telefonnummer = "+43" + j;
            int random = rand.nextInt(fid.size());
            helper.insertIntoAutowerkstatt(fid.get(random), telefonnummer, workerCount);
        }

        //Leasing insertion
        for (int i = 0; i < 1000; ++i) {
            int dauer = rand.nextInt(10) + 1;
            double cents = rand.nextInt(100) / 100.00;
            double preis = rand.nextInt(200000 - 30000) + 30000 + cents;
            helper.insertIntoLeasing(dauer, preis);
        }

        //Auto insertion
        List<Integer> lnr = helper.getLeasingNR();
        for (int i = 0; i <= 1000; ++i) {
            int random = rand.nextInt(lnr.size());
            int getter = rand.nextInt(dgen.getCarsSize());
            helper.insertIntoAuto(dgen.getMarke(getter), dgen.getModell(getter), lnr.get(random));
        }

        //Elektroauto insertion
        List<Integer> aid = helper.getAutoId();
        List<Integer> used = new ArrayList<>(); // used random numbers for car id
        for (int i = 0; i <= 100; ++i) {
            int random = rand.nextInt(aid.size());
            while (used.contains(random)) {
                random = rand.nextInt(aid.size());
            }
            used.add(random);
            int reichweite = rand.nextInt(600 - 400) + 400;
            double kwh = (rand.nextInt(10000 - 4000) + 4000) / 100.00;
            helper.insertIntoElektroauto(aid.get(random), reichweite, kwh);
        }

        //SUV insertion
        for (int i = 0; i <= 200; ++i) {
            int random = rand.nextInt(aid.size());
            while (used.contains(random)) { // if a number that has been already used occurs, use random function until unused number arrives so that there will not be unique constraint violation
                random = rand.nextInt(aid.size());
            }
            used.add(random);
            double motorgroesse = (rand.nextInt(1250 - 190) + 190) / 100.00;
            double verbrauch = (rand.nextInt(2000 - 500) + 500) / 100.00;
            helper.insertIntoSUV(aid.get(random), motorgroesse, verbrauch);
        }

        //Mitarbeiter insertion
        for (int i = 0; i < fid.size(); ++i) { // workers without bosses
            int randomName = rand.nextInt(dgen.getFirstNameSize());
            int randomSurname = rand.nextInt(dgen.getSurnameSize());
            helper.insertIntoMitarbeiter(dgen.getFirstName(randomName), dgen.getSurname(randomSurname), fid.get(i), null); // in every location/filiale will be one boss
        }


        {
            Map<Integer, Integer> filialAndMitarbeiter = helper.getFidMid();
            for (int i = 0; i < fid.size(); ++i) {
                for (int j = 0; j < 5; ++j) {
                    int randomName = rand.nextInt(dgen.getFirstNameSize());
                    int randomSurname = rand.nextInt(dgen.getSurnameSize());
                    helper.insertIntoMitarbeiter(dgen.getFirstName(randomName), dgen.getSurname(randomSurname), fid.get(i), filialAndMitarbeiter.get(i));
                }
            }
        }

        //hat insert
        Set<Integer> numbers = new HashSet<>();
        for (int i = 0; i < helper.getFid().size(); ++i) {
            numbers.clear();
            for (int j = 0; j <= 30; ++j) {
                int random = rand.nextInt(aid.size());
                while (numbers.contains(random)) {
                    random = rand.nextInt(aid.size());
                }
                numbers.add(random);
                helper.insertIntoHat(fid.get(i), aid.get(random));
            }
        }


        //Verkauft insert
        List<Integer> mid = helper.getMitarbeiterId();
        Set<Integer> usedNumbers = new HashSet<>();
        for (int i = 0; i < 500; ++i) {
            Double preis = (rand.nextInt(200000 - 20000) + 20000) * 100 / 100.00;
            int randomMitarbeiter = rand.nextInt(mid.size());
            int randomAuto = rand.nextInt(aid.size());
            while (usedNumbers.contains(randomAuto)) {
                randomAuto = rand.nextInt(aid.size());
            }
            usedNumbers.add(randomAuto);
            String date = (rand.nextInt(2022 - 2012) + 2012) + "-" + (rand.nextInt(12) + 1) + "-" + (rand.nextInt(28) + 1);
            helper.insertIntoVerkauft(mid.get(randomMitarbeiter), aid.get(randomAuto), preis, date);
        }

    }
}
