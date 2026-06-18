<?php

class BookingModel extends Model
{
    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO bookings (field_id, customer_name, customer_email, customer_phone, booking_date, start_time, duration_hours, total_price, status, payment_status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', 'waiting')
        ");
        $stmt->execute([
            $data['field_id'],
            $data['customer_name'],
            $data['customer_email'],
            $data['customer_phone'],
            $data['booking_date'],
            $data['start_time'],
            $data['duration_hours'],
            $data['total_price'],
        ]);
        return $this->db->lastInsertId();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT b.*, f.name as field_name, f.sport, f.price_per_hour
            FROM bookings b
            JOIN fields f ON b.field_id = f.id
            WHERE b.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findByEmail($email, $page = 1, $perPage = 20)
    {
        $offset = ($page - 1) * $perPage;
        $stmt = $this->db->prepare("
            SELECT b.*, f.name as field_name, f.sport
            FROM bookings b
            JOIN fields f ON b.field_id = f.id
            WHERE b.customer_email = ?
            ORDER BY b.booking_date DESC, b.start_time DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$email, $perPage, $offset]);
        return $stmt->fetchAll();
    }

    public function countByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM bookings WHERE customer_email = ?");
        $stmt->execute([$email]);
        return $stmt->fetchColumn();
    }

    public function findPendingByEmail($email)
    {
        $stmt = $this->db->prepare("
            SELECT b.*, f.name as field_name, f.sport
            FROM bookings b
            JOIN fields f ON b.field_id = f.id
            WHERE b.customer_email = ? AND b.payment_status IN ('waiting', 'pending_validation')
            ORDER BY b.created_at DESC LIMIT 1
        ");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function slotIsBooked($fieldId, $date, $time, $duration, $excludeId = null)
    {
        $stmt = $this->db->prepare("
            SELECT start_time, duration_hours FROM bookings
            WHERE field_id = ?
            AND booking_date = ?
            AND status != 'cancelled'
        ");
        $params = [$fieldId, $date];

        if ($excludeId) {
            $stmt = $this->db->prepare("
                SELECT start_time, duration_hours FROM bookings
                WHERE field_id = ?
                AND booking_date = ?
                AND status != 'cancelled'
                AND id != ?
            ");
            $params[] = $excludeId;
        }

        $stmt->execute($params);
        $booked = $stmt->fetchAll();

        $newStart = strtotime($time);
        $newEnd = strtotime("+{$duration} hours", $newStart);

        foreach ($booked as $b) {
            $bStart = strtotime($b['start_time']);
            $bEnd = strtotime("+{$b['duration_hours']} hours", $bStart);

            if ($newStart < $bEnd && $newEnd > $bStart) {
                return true;
            }
        }

        return false;
    }

    public function updatePaymentProof($bookingId, $filename)
    {
        $stmt = $this->db->prepare("UPDATE bookings SET payment_proof = ?, payment_status = 'pending_validation' WHERE id = ?");
        return $stmt->execute([$filename, $bookingId]);
    }

    public function updateStatus($bookingId, $status, $paymentStatus = null)
    {
        $sql = "UPDATE bookings SET status = ?";
        $params = [$status];

        if ($paymentStatus !== null) {
            $sql .= ", payment_status = ?";
            $params[] = $paymentStatus;
        }

        $sql .= " WHERE id = ?";
        $params[] = $bookingId;

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function all($page = 1, $perPage = 20, $filters = [])
    {
        $offset = ($page - 1) * $perPage;
        $sql = "
            SELECT b.*, f.name as field_name, f.sport
            FROM bookings b
            JOIN fields f ON b.field_id = f.id
            WHERE 1=1
        ";
        $countSql = "SELECT COUNT(*) FROM bookings b WHERE 1=1";
        $params = [];

        if (!empty($filters['status'])) {
            $sql .= " AND b.status = ?";
            $countSql .= " AND b.status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['payment_status'])) {
            $sql .= " AND b.payment_status = ?";
            $countSql .= " AND b.payment_status = ?";
            $params[] = $filters['payment_status'];
        }

        if (!empty($filters['field_id'])) {
            $sql .= " AND b.field_id = ?";
            $countSql .= " AND b.field_id = ?";
            $params[] = $filters['field_id'];
        }

        if (!empty($filters['date_from'])) {
            $sql .= " AND b.booking_date >= ?";
            $countSql .= " AND b.booking_date >= ?";
            $params[] = $filters['date_from'];
        }

        if (!empty($filters['date_to'])) {
            $sql .= " AND b.booking_date <= ?";
            $countSql .= " AND b.booking_date <= ?";
            $params[] = $filters['date_to'];
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (b.customer_name LIKE ? OR b.customer_email LIKE ? OR b.customer_phone LIKE ?)";
            $countSql .= " AND (b.customer_name LIKE ? OR b.customer_email LIKE ? OR b.customer_phone LIKE ?)";
            $s = '%' . $filters['search'] . '%';
            $params[] = $s;
            $params[] = $s;
            $params[] = $s;
        }

        $countParams = $params;
        $stmt = $this->db->prepare($countSql);
        $stmt->execute($countParams);
        $total = $stmt->fetchColumn();

        $sql .= " ORDER BY b.booking_date DESC, b.start_time DESC LIMIT ? OFFSET ?";
        $params[] = $perPage;
        $params[] = $offset;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $data = $stmt->fetchAll();

        return [$data, $total];
    }

    public function getStats()
    {
        $stats = [];

        $stmt = $this->db->query("SELECT COUNT(*) FROM bookings");
        $stats['total'] = $stmt->fetchColumn();

        $stmt = $this->db->query("SELECT COUNT(*) FROM bookings WHERE status = 'confirmed'");
        $stats['confirmed'] = $stmt->fetchColumn();

        $stmt = $this->db->query("SELECT COUNT(*) FROM bookings WHERE status = 'pending'");
        $stats['pending'] = $stmt->fetchColumn();

        $stmt = $this->db->query("SELECT COUNT(*) FROM bookings WHERE status = 'cancelled'");
        $stats['cancelled'] = $stmt->fetchColumn();

        $stmt = $this->db->query("SELECT COUNT(*) FROM bookings WHERE payment_status = 'pending_validation'");
        $stats['pending_validation'] = $stmt->fetchColumn();

        $stmt = $this->db->query("SELECT COALESCE(SUM(total_price), 0) FROM bookings WHERE status = 'confirmed'");
        $stats['revenue'] = (float)$stmt->fetchColumn();

        $monthStart = date('Y-m-01');
        $monthEnd = date('Y-m-t');
        $stmt = $this->db->prepare("SELECT COALESCE(SUM(total_price), 0) FROM bookings WHERE status = 'confirmed' AND booking_date >= ? AND booking_date <= ?");
        $stmt->execute([$monthStart, $monthEnd]);
        $stats['revenue_month'] = (float)$stmt->fetchColumn();

        $today = date('Y-m-d');
        $stmt = $this->db->prepare("SELECT COALESCE(SUM(total_price), 0) FROM bookings WHERE status = 'confirmed' AND booking_date = ?");
        $stmt->execute([$today]);
        $stats['revenue_today'] = (float)$stmt->fetchColumn();

        return $stats;
    }

    public function getRevenueByMonth($year = null)
    {
        $year = $year ?: date('Y');
        $start = "$year-01-01";
        $end = "$year-12-31";
        $stmt = $this->db->prepare("
            SELECT booking_date, total_price
            FROM bookings
            WHERE status = 'confirmed'
            AND booking_date >= ? AND booking_date <= ?
            ORDER BY booking_date
        ");
        $stmt->execute([$start, $end]);
        $rows = $stmt->fetchAll();

        $byMonth = [];
        foreach ($rows as $r) {
            $m = (int)date('m', strtotime($r['booking_date']));
            if (!isset($byMonth[$m])) $byMonth[$m] = 0;
            $byMonth[$m] += (float)$r['total_price'];
        }

        $result = [];
        for ($m = 1; $m <= 12; $m++) {
            $result[] = ['month' => $m, 'total' => $byMonth[$m] ?? 0];
        }

        return $result;
    }

    public function getBookingsByField()
    {
        $stmt = $this->db->query("
            SELECT f.name, COUNT(b.id) as total
            FROM fields f
            LEFT JOIN bookings b ON b.field_id = f.id AND b.status != 'cancelled'
            GROUP BY f.id
            ORDER BY total DESC
        ");
        return $stmt->fetchAll();
    }
}
