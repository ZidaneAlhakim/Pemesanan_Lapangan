<?php

class PaymentModel extends Model
{
    public function getPendingPayments()
    {
        $stmt = $this->db->query("
            SELECT b.*, f.name as field_name, f.sport
            FROM bookings b
            JOIN fields f ON b.field_id = f.id
            WHERE b.payment_status = 'pending_validation'
            ORDER BY b.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    public function findByBookingId($bookingId)
    {
        $stmt = $this->db->prepare("
            SELECT b.*, f.name as field_name, f.sport
            FROM bookings b
            JOIN fields f ON b.field_id = f.id
            WHERE b.id = ?
        ");
        $stmt->execute([$bookingId]);
        return $stmt->fetch();
    }

    public function approve($bookingId)
    {
        return $this->updateStatus($bookingId, 'confirmed', 'paid');
    }

    public function reject($bookingId)
    {
        $stmt = $this->db->prepare("SELECT payment_proof FROM bookings WHERE id = ?");
        $stmt->execute([$bookingId]);
        $booking = $stmt->fetch();

        if ($booking && $booking['payment_proof']) {
            storage_delete($booking['payment_proof']);
        }

        return $this->updateStatus($bookingId, 'pending', 'waiting');
    }

    private function updateStatus($bookingId, $status, $paymentStatus)
    {
        $stmt = $this->db->prepare("UPDATE bookings SET status = ?, payment_status = ? WHERE id = ?");
        return $stmt->execute([$status, $paymentStatus, $bookingId]);
    }

    public function getRecentPayments($limit = 10)
    {
        $stmt = $this->db->prepare("
            SELECT b.*, f.name as field_name
            FROM bookings b
            JOIN fields f ON b.field_id = f.id
            WHERE b.payment_proof IS NOT NULL
            ORDER BY b.created_at DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
}
