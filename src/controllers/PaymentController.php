<?php

class PaymentController extends Controller
{
    public function upload()
    {
        $model = new BookingModel();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!csrf_verify()) {
                flash('error', 'Session expired. Silakan coba lagi.');
                $this->redirect('payment' . ($_GET['id'] ? '?id=' . $_GET['id'] : ''));
                return;
            }

            $bookingId = $_POST['booking_id'] ?? $_GET['id'] ?? null;

            if (!$bookingId) {
                flash('error', 'ID booking tidak valid.');
                $this->redirect('payment');
                return;
            }

            $booking = $model->find($bookingId);

            if (!$booking) {
                flash('error', 'Booking tidak ditemukan.');
                $this->redirect('payment');
                return;
            }

            if ($booking['payment_status'] === 'paid') {
                flash('error', 'Pembayaran sudah dikonfirmasi.');
                $this->redirect('summary');
                return;
            }

            if ($booking['status'] === 'cancelled') {
                flash('error', 'Booking sudah dibatalkan.');
                $this->redirect('history?email=' . urlencode($booking['customer_email']));
                return;
            }

            require_once __DIR__ . '/../helpers/validation.php';
            $errors = validatePayment($_FILES['payment_proof'] ?? []);

            if (!empty($errors)) {
                flash('error', implode('<br>', $errors));
                $this->redirect('payment?id=' . $bookingId);
                return;
            }

            if ($booking['payment_proof']) {
                storage_delete($booking['payment_proof']);
            }

            $filename = storage_put($_FILES['payment_proof'], 'payments');
            if (!$filename) {
                flash('error', 'Gagal menyimpan file. Coba lagi.');
                $this->redirect('payment?id=' . $bookingId);
                return;
            }

            $model->updatePaymentProof($bookingId, $filename);
            csrf_regenerate();

            flash('success', 'Bukti pembayaran berhasil diupload! Menunggu verifikasi admin.');
            $this->redirect('summary');
            return;
        }

        $booking = null;
        $bookingId = $_GET['id'] ?? null;
        $email = $_GET['email'] ?? '';

        if ($bookingId) {
            $booking = $model->find($bookingId);
        } elseif ($email) {
            $booking = $model->findPendingByEmail($email);
        }

        $this->view('public/upload', [
            'booking' => $booking,
            'email' => $email,
        ]);
    }
}
