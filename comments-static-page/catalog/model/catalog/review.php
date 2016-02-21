<?php
class ModelCatalogReview extends Model {		
	public function addReview($product_id, $data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "review SET author = '" . $this->db->escape($data['name']) . "', customer_id = '" . (int)$this->customer->getId() . "', product_id = '" . (int)$product_id . "', text = '" . $this->db->escape($data['text']) . "', rating = '" . (int)$data['rating'] . "', date_added = NOW()");
	}
		
	public function getReviewsByProductId($product_id = null, $start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}
		
		if ($limit < 1) {
			$limit = 20;
		}
		
		$select = isset($product_id) ?
			"
			r.review_id,
			r.author,
			r.rating,
			r.text,
			p.product_id,
			pd.name,
			p.price,
			p.image,
			r.date_added"
		: 
			'
			r.review_id,
			r.author,
			r.rating,
			r.text,
			r.date_added';
		
		$from = isset($product_id) ?
			DB_PREFIX . "review r
			LEFT OUTER JOIN " . DB_PREFIX . "product p
			ON (r.product_id = p.product_id)
			LEFT OUTER JOIN " . DB_PREFIX . "product_description pd
			ON (p.product_id = pd.product_id)"
		: DB_PREFIX . 'review r';
		
		$where = isset($product_id) ?
			"p.product_id = '" . (int)$product_id . "'
			AND p.date_available <= NOW()
			AND p.status = '1'
			AND r.status = '1'
			AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'" : '
			r.product_id = 0';
		
		$sql = "
			SELECT " . $select . "
			FROM " . $from . "
			WHERE " . $where . "
			ORDER BY r.date_added DESC
			LIMIT " . (int)$start . "," . (int)$limit;
		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function getTotalReviewsByProductId($product_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND p.date_available <= NOW() AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		return $query->row['total'];
	}
}
?>