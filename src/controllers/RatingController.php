<?php

class RatingController extends Controller
{
    public function form()
    {
        $bookingId = $_GET['booking_id'] ?? null;
        $booking = null;

        if ($bookingId) {
            $model = new BookingModel();
            $booking = $model->find($bookingId);
        }

        $this->view('public/rating', [
            'booking' => $booking,
        ]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('rating');
            return;
        }

        if (!csrf_verify()) {
            flash('error', 'Session expired. Silakan coba lagi.');
            $this->redirect('rating');
            return;
        }

        require_once __DIR__ . '/../helpers/validation.php';
        $errors = validateRating($_POST);

        if (!empty($errors)) {
            flash('error', implode('<br>', $errors));
            $this->redirect('rating?booking_id=' . ($_POST['booking_id'] ?? ''));
            return;
        }

        $bookingModel = new BookingModel();
        $booking = $bookingModel->find($_POST['booking_id']);

        if (!$booking) {
            flash('error', 'Booking tidak ditemukan.');
            $this->redirect('rating');
            return;
        }

        if ($booking['status'] !== 'confirmed') {
            flash('error', 'Hanya booking yang sudah dikonfirmasi yang bisa diberi rating.');
            $this->redirect('rating?booking_id=' . $_POST['booking_id']);
            return;
        }

        $ratingModel = new RatingModel();
        if ($ratingModel->hasRated($_POST['booking_id'])) {
            flash('error', 'Booking ini sudah pernah diberi rating.');
            $this->redirect('history?email=' . urlencode($booking['customer_email']));
            return;
        }

        $ratingModel->create([
            'booking_id' => $_POST['booking_id'],
            'rating' => intval($_POST['rating']),
            'review' => $_POST['review'] ?? '',
        ]);

        csrf_regenerate();
        flash('success', 'Terima kasih! Rating Anda telah disimpan.');
        $this->redirect('history?email=' . urlencode($booking['customer_email']));
    }
}
