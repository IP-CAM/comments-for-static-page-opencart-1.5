<?php 
class ControllerInformationComments extends Controller {
	public function index() {  
		$this->language->load('information/information');

		$this->load->model('catalog/information');

		$this->data['breadcrumbs'] = array();
		
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => false
		);

		if (isset($this->request->get['information_id'])) {
			$information_id = (int)$this->request->get['information_id'];
		} else {
			$information_id = 0;
		}

		$information_info = $this->model_catalog_information->getInformation($information_id);

		if ($information_info) {
			$this->document->setTitle($information_info['title']);

			$this->data['breadcrumbs'][] = array(
				'text'      => $information_info['title'],
				'href'      => $this->url->link('information/information', 'information_id=' .  $information_id),      		
				'separator' => $this->language->get('text_separator')
			);

			$this->data['heading_title'] = $information_info['title'];


			$this->data['description'] = html_entity_decode($information_info['description'], ENT_QUOTES, 'UTF-8');

			$this->data['continue'] = $this->url->link('common/home');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/comments.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/information/comments.tpl';
			} else {
				$this->template = 'default/template/information/comments.tpl';
			}

			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'
			);

			$this->language->load('product/product');
			$this->data['text_write'] = $this->language->get('text_write');
			$this->data['text_note'] = $this->language->get('text_note');
			$this->data['text_share'] = $this->language->get('text_share');
			$this->data['text_wait'] = $this->language->get('text_wait');
			$this->data['text_tags'] = $this->language->get('text_tags');
			$this->data['entry_name'] = $this->language->get('entry_name');
			$this->data['entry_review'] = $this->language->get('entry_review');
			$this->data['entry_rating'] = $this->language->get('entry_rating');
			$this->data['entry_good'] = $this->language->get('entry_good');
			$this->data['entry_bad'] = $this->language->get('entry_bad');
			$this->data['entry_captcha'] = $this->language->get('entry_captcha');
			$this->data['button_cart'] = $this->language->get('button_cart');
			$this->data['button_wishlist'] = $this->language->get('button_wishlist');
			$this->data['button_compare'] = $this->language->get('button_compare');
			$this->data['button_upload'] = $this->language->get('button_upload');
			$this->data['button_continue'] = $this->language->get('button_continue');
			
			$this->data['tab_review'] = $this->review();
			
			$this->response->setOutput($this->render());
		} else {
			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_error'),
				'href'      => $this->url->link('information/information', 'information_id=' . $information_id),
				'separator' => $this->language->get('text_separator')
			);

			$this->document->setTitle($this->language->get('text_error'));

			$this->data['heading_title'] = $this->language->get('text_error');

			$this->data['text_error'] = $this->language->get('text_error');


			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . '/1.1 404 Not Found');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
			} else {
				$this->template = 'default/template/error/not_found.tpl';
			}

			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'
			);

			$this->response->setOutput($this->render());
		}
	}
	

	public function review() {
		$this->language->load('product/product');

		$this->load->model('catalog/review');

		$this->data['text_on'] = $this->language->get('text_on');
		$this->data['text_no_reviews'] = $this->language->get('text_no_reviews');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$this->data['reviews'] = array();

		$review_total = isset($this->request->get['product_id']) ? $this->model_catalog_review->getTotalReviewsByProductId($this->request->get['product_id']) : 10;
		
		$product_id = isset($this->request->get['product_id']) ? $this->request->get['product_id'] : null;
		
		$results = $this->model_catalog_review->getReviewsByProductId($product_id, ($page - 1) * 5, 5);
		
		foreach ($results as $result) {
			$this->data['reviews'][] = array(
				'author'     => $result['author'],
				'text'       => $result['text'],
				'rating'     => (int)$result['rating'],
				'reviews'    => sprintf($this->language->get('text_reviews'), (int)$review_total),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}
		return $this->data['reviews'];
		
		/*
		$pagination = new Pagination();
		$pagination->total = $review_total;
		$pagination->page = $page;
		$pagination->limit = 5;
		$product_id = isset($this->request->get['product_id']) ? $this->request->get['product_id'] : null;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('information/comments/review', 'product_id=' . $product_id . '&page={page}');

		$this->data['pagination'] = $pagination->render();

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/review.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/product/review.tpl';
		} else {
			$this->template = 'default/template/product/review.tpl';
		}

		$this->response->setOutput($this->render());
		*/
	}
	
	public function write() {
		$this->language->load('product/product');

		$this->load->model('catalog/review');

		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 25)) {
				$json['error'] = $this->language->get('error_name');
			}

			if ((utf8_strlen($this->request->post['text']) < 25) || (utf8_strlen($this->request->post['text']) > 1000)) {
				$json['error'] = $this->language->get('error_text');
			}

			if (empty($this->request->post['rating'])) {
				$json['error'] = $this->language->get('error_rating');
			}

			if (empty($this->session->data['captcha']) || ($this->session->data['captcha'] != $this->request->post['captcha'])) {
				$json['error'] = $this->language->get('error_captcha');
			}

			if (!isset($json['error'])) {
				$this->model_catalog_review->addReview($this->request->get['product_id'], $this->request->post);

				$json['success'] = $this->language->get('text_success');
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	public function captcha() {
		$this->load->library('captcha');

		$captcha = new Captcha();

		$this->session->data['captcha'] = $captcha->getCode();

		$captcha->showImage();
	}
}
?>