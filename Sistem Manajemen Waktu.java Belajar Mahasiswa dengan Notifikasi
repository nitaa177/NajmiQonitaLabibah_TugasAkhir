import javax.swing.*;
import javax.swing.table.*;
import java.awt.*;
import java.io.*;
import java.time.LocalTime;
import java.time.format.DateTimeFormatter;
import java.util.*;
import java.util.Timer;
import javax.sound.sampled.*;

public class InteractiveStudyManagerV2 extends JFrame {
    private JTable table;
    private DefaultTableModel model;
    private java.util.List<String[]> schedules;
    private Map<String, Color> subjectColors; // warna per mata pelajaran

    public InteractiveStudyManagerV2() {
        setTitle("Manajemen Waktu Belajar Interaktif - Versi Keren");
        setSize(600, 400);
        setDefaultCloseOperation(JFrame.HIDE_ON_CLOSE); // biar tray tetap aktif
        setLayout(new BorderLayout());

        schedules = new ArrayList<>();
        subjectColors = new HashMap<>();
        model = new DefaultTableModel(new Object[]{"Waktu", "Mata Pelajaran", "Status"}, 0) {
            public boolean isCellEditable(int row, int column) {
                return column == 2;
            }
        };

        table = new JTable(model) {
            public Component prepareRenderer(TableCellRenderer renderer, int row, int column) {
                Component c = super.prepareRenderer(renderer, row, column);
                String subject = (String) getValueAt(row, 1);
                String status = (String) getValueAt(row, 2);

                // Set warna berdasarkan mata pelajaran
                c.setBackground(subjectColors.computeIfAbsent(subject, k -> new Color(
                        (int)(Math.random()*256),
                        (int)(Math.random()*256),
                        (int)(Math.random()*256)
                )));

                // Override warna jika status Selesai
                if ("Selesai".equals(status)) {
                    c.setBackground(Color.PINK);
                }

                return c;
            }
        };

        String[] statusOptions = {"Belum", "Selesai"};
        table.getColumnModel().getColumn(2).setCellEditor(new DefaultCellEditor(new JComboBox<>(statusOptions)));

        add(new JScrollPane(table), BorderLayout.CENTER);

        JPanel inputPanel = new JPanel();
        JTextField timeField = new JTextField(5);
        JTextField subjectField = new JTextField(10);
        JButton addButton = new JButton("Tambah");
        inputPanel.add(new JLabel("HH:mm:"));
        inputPanel.add(timeField);
        inputPanel.add(new JLabel("Mata Pelajaran:"));
        inputPanel.add(subjectField);
        inputPanel.add(addButton);
        add(inputPanel, BorderLayout.SOUTH);

        loadSchedules();

        addButton.addActionListener(e -> {
            String time = timeField.getText();
            String subject = subjectField.getText();
            if (!time.matches("\\d{2}:\\d{2}")) {
                JOptionPane.showMessageDialog(this, "Format waktu harus HH:mm");
                return;
            }
            schedules.add(new String[]{time, subject, "Belum"});
            model.addRow(new Object[]{time, subject, "Belum"});
            saveSchedules();
            timeField.setText("");
            subjectField.setText("");
        });

        setupSystemTray();

        Timer timer = new Timer();
        timer.scheduleAtFixedRate(new TimerTask() {
            public void run() {
                checkSchedules();
            }
        }, 0, 60000);
    }

    private void checkSchedules() {
        LocalTime now = LocalTime.now();
        String current = now.format(DateTimeFormatter.ofPattern("HH:mm"));
        for (int i = 0; i < model.getRowCount(); i++) {
            String time = (String) model.getValueAt(i, 0);
            String status = (String) model.getValueAt(i, 2);
            if (time.equals(current) && "Belum".equals(status)) {
                String subject = (String) model.getValueAt(i, 1);
                showNotification("Waktunya Belajar!", "Mata Pelajaran: " + subject);
                playSoundForSubject(subject);
            }
        }
    }

    private void setupSystemTray() {
        if (SystemTray.isSupported()) {
            try {
                SystemTray tray = SystemTray.getSystemTray();
                TrayIcon trayIcon = new TrayIcon(Toolkit.getDefaultToolkit().createImage(""), "Belajar");
                trayIcon.setImageAutoSize(true);
                tray.add(trayIcon);
            } catch (Exception e) {
                e.printStackTrace();
            }
        }
    }

    private void showNotification(String title, String message) {
        if (SystemTray.isSupported()) {
            try {
                SystemTray tray = SystemTray.getSystemTray();
                TrayIcon trayIcon = new TrayIcon(Toolkit.getDefaultToolkit().createImage(""), "Belajar");
                trayIcon.setImageAutoSize(true);
                tray.add(trayIcon);
                trayIcon.displayMessage(title, message, TrayIcon.MessageType.INFO);
                tray.remove(trayIcon);
            } catch (Exception e) {
                e.printStackTrace();
            }
        } else {
            JOptionPane.showMessageDialog(this, message);
        }
    }

    private void playSoundForSubject(String subject) {
        try {
            // Gunakan nama file berbeda tiap mata pelajaran (contoh: Matematika.wav)
            File soundFile = new File(subject + ".wav");
            if (!soundFile.exists()) {
                soundFile = new File("notification.wav"); // default
            }
            AudioInputStream audioIn = AudioSystem.getAudioInputStream(soundFile);
            Clip clip = AudioSystem.getClip();
            clip.open(audioIn);
            clip.start();
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    private void saveSchedules() {
        try (PrintWriter pw = new PrintWriter(new File("schedules.txt"))) {
            for (int i = 0; i < model.getRowCount(); i++) {
                String time = (String) model.getValueAt(i, 0);
                String subject = (String) model.getValueAt(i, 1);
                String status = (String) model.getValueAt(i, 2);
                pw.println(time + ";" + subject + ";" + status);
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    private void loadSchedules() {
        File file = new File("schedules.txt");
        if (file.exists()) {
            try (Scanner sc = new Scanner(file)) {
                while (sc.hasNextLine()) {
                    String[] s = sc.nextLine().split(";");
                    schedules.add(s);
                    model.addRow(s);
                }
            } catch (Exception e) {
                e.printStackTrace();
            }
        }
    }

    public static void main(String[] args) {
        SwingUtilities.invokeLater(() -> new InteractiveStudyManagerV2().setVisible(true));
    }
}
