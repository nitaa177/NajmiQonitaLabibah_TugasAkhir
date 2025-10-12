import java.util.*;

class ScheduleManager {
    public List<String> events = new ArrayList<>();  // Daftar event: "Nama Acara - Waktu - Durasi"

    // Instance method non-return type (void) dengan parameter: Menambahkan event ke jadwal
    public void addEvent(String title, int time, int duration) {
        String eventEntry = title + "-" + time + "-" + duration;
        events.add(eventEntry);
        System.out.println("Event '" + title + "' pada jam " + time + " (durasi " + duration + " menit) ditambahkan!");
    }

    // Instance method non-return type (void) tanpa parameter: Menampilkan seluruh jadwal
    public void displayEvents() {
        if (events.isEmpty()) {
            System.out.println("Jadwal kosong!");
        } else {
            System.out.println("\nDaftar Jadwal Kesibukan:");
            for (int i = 0; i < events.size(); i++) {  // Perulangan for
                System.out.println((i + 1) + ". " + events.get(i));
            }
        }
    }
}

public class TugasLaprak_Kel35 {
    // Static method (function) dengan return type dan parameter: Hitung event berdasarkan waktu tertentu
    private static int countEventsByTime(ScheduleManager scheduler, int time) {
        int count = 0;
        for (String entry : scheduler.events) {  // Perulangan for-each
            String[] parts = entry.split("-");
            if (parts.length == 3 && Integer.parseInt(parts[1]) == time) {  // Pengkondisian if
                count++;
            }
        }
        return count;  // Return type
    }

    // Static method (function) dengan return type tanpa parameter
    private static int totalEvents(ScheduleManager scheduler) {
        return scheduler.events.size();  // Return type
    }

    // Static method non-return type (void) tanpa parameter=
    public static void printMenu() {
        System.out.println("\n=== PENGATUR JADWAL KESIBUKAN Kel 35 ===");
        System.out.println("1. Tambah Event");
        System.out.println("2. Tampilkan Jadwal");
        System.out.println("3. Jumlah kegiatan pada Jam Tertentu");
        System.out.println("4. Total Event");
        System.out.println("5. Keluar");
    }

    // Static method dengan return type untuk validasi input waktu (perulangan while + pengkondisian)
    public static int validateTimeInput(Scanner sc) {
        while (true) {
            System.out.print("Jam (0-23): ");
            try {
                int time = Integer.parseInt(sc.nextLine());
                if (time >= 0 && time <= 23) {
                    return time;
                } else {
                    System.out.println("Jam harus antara 0-23!");
                }
            } catch (NumberFormatException e) {
                System.out.println("Input harus angka!");
            }
        }
    }

    // Static method non-return type (void): Menu utama (integrasi dengan while + if-else)
    public static void mainMenu() {
        Scanner sc = new Scanner(System.in);
        ScheduleManager scheduler = new ScheduleManager();

        label:
        while (true) {  // Perulangan while
            printMenu();
            System.out.print("Pilih (1-5): ");
            String choice = sc.nextLine();

            switch (choice) {
                case "1":
                    System.out.print("Judul event: ");
                    String title = sc.nextLine();
                    int time = validateTimeInput(sc);
                    System.out.print("Durasi (menit, >0): ");
                    int duration = 0;
                    while (duration <= 0) {  // Perulangan while untuk validasi durasi
                        try {
                            duration = Integer.parseInt(sc.nextLine());
                            if (duration <= 0) {
                                System.out.println("Durasi harus >0!");
                            }
                        } catch (NumberFormatException e) {
                            System.out.println("Input harus angka!");
                            duration = 0;
                        }
                    }
                    scheduler.addEvent(title, time, duration);  // Instance method void dengan parameter


                    break;
                case "2":
                    scheduler.displayEvents();  // Instance method void tanpa parameter


                    break;
                case "3":
                    int searchTime = validateTimeInput(sc);
                    int count = countEventsByTime(scheduler, searchTime);  // Static return dengan parameter

                    System.out.println("Event pada jam " + searchTime + ": " + count);

                    break;
                case "4":
                    int total = totalEvents(scheduler);  // Static return tanpa parameter

                    System.out.println("Total event: " + total);

                    break;
                case "5":
                    System.out.println("Jadwal selesai!");
                    break label;

                default:
                    System.out.println("Pilihan salah!");
                    break;
            }
        }
        sc.close();
    }

    public static void main(String[] args) {
        mainMenu();
    }
}
