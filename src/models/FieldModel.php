<?php

class FieldModel extends Model
{
    public function getAll($filters = [])
    {
        $sql = "SELECT * FROM fields WHERE is_active = 1";
        $params = [];

        if (!empty($filters['sport'])) {
            $sql .= " AND sport = ?";
            $params[] = $filters['sport'];
        }

        $sql .= " ORDER BY name ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getAllAdmin()
    {
        $stmt = $this->db->query("SELECT f.*,
            (SELECT COUNT(*) FROM bookings WHERE field_id = f.id) as total_bookings
            FROM fields f ORDER BY f.name ASC");
        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM fields WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getSports()
    {
        $stmt = $this->db->query("SELECT DISTINCT sport FROM fields WHERE is_active = 1 ORDER BY sport");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO fields (name, sport, capacity, price_per_hour, description, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['name'],
            $data['sport'],
            $data['capacity'],
            $data['price_per_hour'],
            $data['description'] ?? '',
            $data['image'] ?? null,
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data)
    {
        $sql = "UPDATE fields SET name = ?, sport = ?, capacity = ?, price_per_hour = ?, description = ?";
        $params = [$data['name'], $data['sport'], $data['capacity'], $data['price_per_hour'], $data['description'] ?? ''];

        if (array_key_exists('image', $data)) {
            $sql .= ", image = ?";
            $params[] = $data['image'];
        }

        if (isset($data['is_active'])) {
            $sql .= ", is_active = ?";
            $params[] = $data['is_active'];
        }

        $sql .= " WHERE id = ?";
        $params[] = $id;

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("UPDATE fields SET is_active = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getHourlyAvailability($fieldId, $date)
    {
        $field = $this->find($fieldId);
        if (!$field) return [];

        $bookedSlots = $this->getBookedSlots($fieldId, $date);
        $slots = [];

        for ($hour = 8; $hour <= 21; $hour++) {
            $time = sprintf('%02d:00', $hour);
            $isBooked = false;

            foreach ($bookedSlots as $booked) {
                $bookedStart = strtotime($booked['start_time']);
                $bookedEnd = strtotime("+{$booked['duration_hours']} hours", $bookedStart);
                $slotStart = strtotime($time);
                $slotEnd = strtotime('+1 hour', $slotStart);

                if ($slotStart < $bookedEnd && $slotEnd > $bookedStart) {
                    $isBooked = true;
                    break;
                }
            }

            $slots[] = [
                'time' => $time,
                'available' => !$isBooked,
            ];
        }

        return $slots;
    }

    protected function getBookedSlots($fieldId, $date)
    {
        $stmt = $this->db->prepare("
            SELECT start_time, duration_hours FROM bookings
            WHERE field_id = ?
            AND booking_date = ?
            AND status != 'cancelled'
            ORDER BY start_time
        ");
        $stmt->execute([$fieldId, $date]);
        return $stmt->fetchAll();
    }
}
