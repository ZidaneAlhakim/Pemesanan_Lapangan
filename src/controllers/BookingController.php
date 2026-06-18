<?php

class BookingController extends Controller
{
    public function create()
    {
        $fieldId = $_GET['id'] ?? null;
        $date = $_GET['date'] ?? '';
        $time = $_GET['time'] ?? '';

        if (!$fieldId || !$date || !$time) {
            flash('error', 'Parameter booking tidak lengkap.');
            $this->redirect('home');
            return;
        }

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) || !preg_match('/^\d{2}:\d{2}$/', $time)) {
            flash('error', 'Format tanggal atau jam tidak valid.');
            $this->redirect('home');
            return;
        }

        $model = new FieldModel();
        $field = $model->find($fieldId);

        if (!$field) {
            flash('error', 'Lapangan tidak ditemukan.');
            $this->redirect('home');
            return;
        }

        $this->view('public/form', [
            'field' => $field,
            'date' => $date,
            'time' => $time,
        ]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('home');
            return;
        }

        require_once __DIR__ . '/../helpers/validation.php';
        $errors = validateBooking($_POST);

        if (!empty($errors)) {
            $_SESSION['old'] = $_POST;
            flash('error', implode('<br>', $errors));
            $this->redirect('booking?id=' . ($_POST['field_id'] ?? '') . '&date=' . ($_POST['booking_date'] ?? '') . '&time=' . ($_POST['start_time'] ?? ''));
            return;
        }

        $model = new FieldModel();
        $field = $model->find($_POST['field_id']);

        if (!$field) {
            flash('error', 'Lapangan tidak ditemukan.');
            $this->redirect('home');
            return;
        }

        $totalPrice = $field['price_per_hour'] * intval($_POST['duration_hours']);

        $bookingModel = new BookingModel();

        $isBooked = $bookingModel->slotIsBooked(
            $_POST['field_id'],
            $_POST['booking_date'],
            $_POST['start_time'],
            intval($_POST['duration_hours'])
        );

        if ($isBooked) {
            flash('error', 'Slot sudah dipesan oleh orang lain. Silakan pilih jam lain.');
            $this->redirect('schedule?id=' . $_POST['field_id'] . '&date=' . $_POST['booking_date']);
            return;
        }

        $bookingId = $bookingModel->create([
            'field_id' => $_POST['field_id'],
            'customer_name' => $_POST['customer_name'],
            'customer_email' => $_POST['customer_email'],
            'customer_phone' => $_POST['customer_phone'],
            'booking_date' => $_POST['booking_date'],
            'start_time' => $_POST['start_time'],
            'duration_hours' => intval($_POST['duration_hours']),
            'total_price' => $totalPrice,
            'notes' => $_POST['notes'] ?? '',
        ]);

        csrf_regenerate();
        $_SESSION['last_booking_id'] = $bookingId;
        unset($_SESSION['old']);

        flash('success', 'Booking berhasil dibuat! Silakan lakukan pembayaran.');
        $this->redirect('summary');
    }

    public function summary()
    {
        $bookingId = $_SESSION['last_booking_id'] ?? null;

        if (!$bookingId) {
            flash('error', 'Tidak ada booking terakhir.');
            $this->redirect('home');
            return;
        }

        $model = new BookingModel();
        $booking = $model->find($bookingId);

        if (!$booking) {
            flash('error', 'Booking tidak ditemukan.');
            $this->redirect('home');
            return;
        }

        $this->view('public/summary', ['booking' => $booking]);
    }

    public function history()
    {
        $email = $_GET['email'] ?? '';

        $bookings = [];
        $totalPages = 1;
        $currentPage = max(1, intval($_GET['page'] ?? 1));

        if (!empty($email)) {
            $model = new BookingModel();
            $bookings = $model->findByEmail($email, $currentPage);
            $total = $model->countByEmail($email);
            $perPage = 20;
            $totalPages = max(1, ceil($total / $perPage));
        }

        $this->view('public/history', [
            'bookings' => $bookings,
            'email' => $email,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
        ]);
    }
}
