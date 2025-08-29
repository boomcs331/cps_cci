<?php

class MaterialTransactions extends Model
{
    public function generateTransactionNo($type)
    {
        $prefix = ($type === 'IN') ? 'IN' : 'OUT';
        $date = date('Ymd');
        $stmt = $this->db->prepare("SELECT LPAD(COALESCE(MAX(CAST(SUBSTRING_INDEX(transaction_no, '-', -1) AS UNSIGNED)),0)+1, 4, '0') as seq FROM material_transactions WHERE transaction_no LIKE :p");
        $like = $prefix . '-' . $date . '%';
        $stmt->execute([':p' => $like]);
        $seq = $stmt->fetchColumn();
        return $prefix . '-' . $date . '-' . $seq;
    }

    public function createHeader($transactionNo, $type, $date, $remark = null, $createdBy = null)
    {
        $sql = "INSERT INTO material_transactions (transaction_no, transaction_type, date_transaction, remark, created_by) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$transactionNo, $type, $date, $remark, $createdBy]);
        return $this->db->lastInsertId();
    }

    public function createDetail($transactionNo, $productId, $qtyPieces, $qrCode = null)
    {
        $sql = "INSERT INTO material_transaction_details (transaction_no, product_id, qty_pieces, packing_per_pack, packs_derived, remainder_pieces, qr_code) VALUES (?, ?, ?, 0, 0, 0, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$transactionNo, $productId, $qtyPieces, $qrCode]);
    }

    public function getRecentDetails($limit = 50)
    {
        $sql = "SELECT d.id, d.transaction_no, t.transaction_type, t.date_transaction, d.product_id, m.product_name, d.qty_pieces
                FROM material_transaction_details d
                JOIN material_transactions t ON t.transaction_no COLLATE utf8mb4_unicode_ci = d.transaction_no COLLATE utf8mb4_unicode_ci
                LEFT JOIN material m ON m.product_id COLLATE utf8mb4_unicode_ci = d.product_id COLLATE utf8mb4_unicode_ci
                ORDER BY d.id DESC
                LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStocks()
    {
        $sql = "SELECT s.product_id, m.product_name, s.balance_pieces
                FROM material_stock s
                LEFT JOIN material m ON m.product_id = s.product_id
                ORDER BY s.product_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>

