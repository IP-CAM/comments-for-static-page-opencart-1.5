<?php echo $header; ?>
<div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
</div>
<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><h1 class="heading-title"><?php echo $heading_title; ?></h1><?php echo $content_top; ?>
  <?php echo $description; ?>
  

  
	<?php foreach ($tab_review as $k => $review) { ?>
		<div class="review-list" itemprop="review" itemscope itemtype="http://schema.org/Review">
			<div class="author">
				<b itemprop="author"><?php echo $review['author']; ?></b>
				<?php echo $text_on; ?>
				<span itemprop="datePublished"><?php echo $review['date_added']; ?></span>
			</div>
			<div class="rating" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
				<img width="83" height="15" src="catalog/view/theme/default/image/stars-<?php echo $review['rating'] . '.png'; ?>" alt="<?php echo $review['rating']; ?>" title="<?php echo $review['rating']; ?> "/>
				<meta itemprop="worstRating" content="1"> ( <span itemprop="ratingValue"><?php echo $review['rating']; ?></span> / <span itemprop="bestRating">5</span> )
			</div>
		  <div class="text" itemprop="description"><?php echo $review['text']; ?></div>
		</div>
	<?php } ?>
  
  

  <div id="tab-review" class="tab-content">
    <div id="review"><?php echo $this->journal2->settings->get('product_reviews'); ?></div>
    <h2 id="review-title"><?php echo $text_write; ?></h2>
    <b><?php echo $entry_name; ?></b><br />
    <input type="text" name="name" value="" />
    <br />
    <br />
    <b><?php echo $entry_review; ?></b>
    <textarea name="text" cols="40" rows="8"></textarea>
    <span><?php echo $text_note; ?></span><br />
    <br />
    <b><?php echo $entry_rating; ?></b> <span><?php echo $entry_bad; ?></span>&nbsp;
    <input type="radio" name="rating" value="1" />
    &nbsp;
    <input type="radio" name="rating" value="2" />
    &nbsp;
    <input type="radio" name="rating" value="3" />
    &nbsp;
    <input type="radio" name="rating" value="4" />
    &nbsp;
    <input type="radio" name="rating" value="5" />
    &nbsp;<span><?php echo $entry_good; ?></span><br />
    <br />
    <b><?php echo $entry_captcha; ?></b><br />
    <input type="text" name="captcha" value="" />
    <br />
    <img src="index.php?route=product/product/captcha" alt="" id="captcha" /><br />
    <br />
    <div class="buttons">
      <div class="right"><a id="button-review" class="button"><?php echo $button_continue; ?></div>
    </div>
  </div>
  
  
      <?php if (isset($review_status) && $review_status) { ?>
      <div class="review" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
        <meta itemprop="ratingValue" content="<?php echo $rating; ?>" />
        <meta itemprop="reviewCount" content="<?php echo $this->journal2->settings->get('product_num_reviews'); ?>" />
        <meta itemprop="bestRating" content="5" />
        <meta itemprop="worstRating" content="1" />
        <div><img width="83" height="15" src="catalog/view/theme/default/image/stars-<?php echo $rating; ?>.png" alt="<?php echo $reviews; ?>" />&nbsp;&nbsp;<a onclick="$('a[href=\'#tab-review\']').trigger('click');"><?php echo $reviews; ?></a>&nbsp;&nbsp;&bull;&nbsp;&nbsp;<a onclick="$('a[href=\'#tab-review\']').trigger('click');"><?php echo $text_write; ?></a></div>
        <div class="share"><!-- AddThis Button BEGIN -->
          <!--<div class="addthis_default_style"><a class="addthis_button_compact"><?php echo $text_share; ?></a> <a class="addthis_button_email"></a><a class="addthis_button_print"></a> <a class="addthis_button_facebook"></a> <a class="addthis_button_twitter"></a></div>-->
          <!--<script type="text/javascript" src="//s7.addthis.com/js/250/addthis_widget.js"></script>-->
          <!-- AddThis Button END -->
        </div>
      </div>
      <?php } ?>
  
  
  
  
  
<script>
$('#button-review').bind('click', function() {
	$.ajax({
		url: 'index.php?route=information/comments/write&product_id=<?php echo $information_id; ?>',
		type: 'post',
		dataType: 'json',
		data: 'name=' + encodeURIComponent($('input[name=\'name\']').val()) + '&text=' + encodeURIComponent($('textarea[name=\'text\']').val()) + '&rating=' + encodeURIComponent($('input[name=\'rating\']:checked').val() ? $('input[name=\'rating\']:checked').val() : '') + '&captcha=' + encodeURIComponent($('input[name=\'captcha\']').val()),
		beforeSend: function() {
			$('.success, .warning').remove();
			$('#button-review').attr('disabled', true);
			$('#review-title').after('<div class="attention"><img src="catalog/view/theme/default/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
		},
		complete: function() {
			$('#button-review').attr('disabled', false);
			$('.attention').remove();
		},
		success: function(data) {
			if (data['error']) {
				$('#review-title').after('<div class="warning">' + data['error'] + '</div>');
			}

			if (data['success']) {
				$('#review-title').after('<div class="success">' + data['success'] + '</div>');

				$('input[name=\'name\']').val('');
				$('textarea[name=\'text\']').val('');
				$('input[name=\'rating\']:checked').attr('checked', '');
				$('input[name=\'captcha\']').val('');
			}
		}
	});
});
</script>
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	if ($.browser.msie && $.browser.version == 6) {
		$('.date, .datetime, .time').bgIframe();
	}

	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
	$('.datetime').datetimepicker({
		dateFormat: 'yy-mm-dd',
		timeFormat: 'h:m'
	});
	$('.time').timepicker({timeFormat: 'h:m'});
});
  
$('#review .pagination a').live('click', function() {
	$('#review').fadeOut('slow');

	$('#review').load(this.href);

	$('#review').fadeIn('slow');

	return false;
});

$('#review').load('index.php?route=product/product/review&product_id=<?php echo $product_id; ?>');
</script>
  
  
  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>




