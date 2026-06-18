<?php

class RatingModel extends Model
{
    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO ratings (booking_id, rating, review) VALUES (?, ?, ?)");
        $stmt->execute([$data['booking_id'], $data['rating'], $data['review'] ?? '']);
        return $this->db->lastInsertId();
    }

    public function findByBookingId($bookingId)
    {
        $stmt = $this->db->prepare("SELECT * FROM ratings WHERE booking_id = ?");
        $stmt->execute([$bookingId]);
        return $stmt->fetch();
    }

    public function hasRated($bookingId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM ratings WHERE booking_id = ?");
        $stmt->execute([$bookingId]);
        return $stmt->fetchColumn() > 0;
    }

    public function getByFieldId($fieldId)
    {
        $stmt = $this->db->prepare("
            SELECT r.*, b.customer_name
            FROM ratings r
            JOIN bookings b ON r.booking_id = b.id
            WHERE b.field_id = ?
            ORDER BY r.created_at DESC
        ");
        $stmt->execute([$fieldId]);
        return $stmt->fetchAll();
    }

    public function getAverageRating($fieldId = null)
    {
        $sql = "SELECT AVG(r.rating) as avg_rating, COUNT(r.id) as total_ratings";
        if ($fieldId) {
            $sql .= " FROM ratings r JOIN bookings b ON r.booking_id = b.id WHERE b.field_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$fieldId]);
        } else {
            $sql .= " FROM ratings r";
            $stmt = $this->db->query($sql);
        }
        return $stmt->fetch();
    }

    public function getAll($page = 1, $perPage = 20)
    {
        $offset = ($page - 1) * $perPage;
        $stmt = $this->db->prepare("
            SELECT r.*, b.customer_name, b.customer_email, f.name as field_name
            FROM ratings r
            JOIN bookings b ON r.booking_id = b.id
            JOIN fields f ON b.field_id = f.id
            ORDER BY r.created_at DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$perPage, $offset]);
        return $stmt->fetchAll();
    }

    public function countAll()
    {
        return $this->db->query("SELECT COUNT(*) FROM ratings")->fetchColumn();
    }
}
