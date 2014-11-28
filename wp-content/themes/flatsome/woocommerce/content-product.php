<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

global $product, $woocommerce_loop, $flatsome_opt;


$attachment_ids = $product->get_gallery_attachment_ids();

// Ensure visibilty
if ( ! $product->is_visible() )
	return;

// Get avability
$post_id = $post->ID;
$stock_status = get_post_meta($post_id, '_stock_status',true) == 'outofstock';

// run add to cart variation script
if($product->is_type( array( 'variable', 'grouped') )) wp_enqueue_script('wc-add-to-cart-variation');

?>

<li class="product-small <?php if($stock_status == "1") { ?>out-of-stock<?php }?> <?php echo $flatsome_opt['grid_style']; ?>">
<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>
<div class="inner-wrap">
<a href="<?php the_permalink(); ?>">
      <div class="product-image hover_<?php echo $flatsome_opt['product_hover']; ?>">
      	<?php if ( has_post_thumbnail()){ ?>
         <div class="front-image"><?php echo get_the_post_thumbnail( $post->ID, 'shop_catalog') ?></div>
					<?php if($flatsome_opt['product_hover'] == "fade_in_back" || !isset($flatsome_opt['product_hover'])) { ?>
					<?php
						if ( $attachment_ids ) {
							$loop = 0;				
							foreach ( $attachment_ids as $attachment_id ) {
								$image_link = wp_get_attachment_url( $attachment_id );
								if ( ! $image_link )
									continue;
								$loop++;
								printf( '<div class="back-image back">%s</div>', wp_get_attachment_image( $attachment_id, 'shop_catalog' ) );
								if ($loop == 1) break;
							}
						} else {
						?>
                        <div class="back-image"><?php echo get_the_post_thumbnail( $post->ID, 'shop_catalog') ?></div>
                        <?php
						}
					?>
					<?php } else { ?>
					    <div class="back-image"><?php echo get_the_post_thumbnail( $post->ID, 'shop_catalog') ?></div>
					<?php } ?>
		<?php } else { echo '<img src="'.wc_placeholder_img_src().'"/>';} ?>

		 <?php if(!$flatsome_opt['disable_quick_view']){ ?>
          <div class="quick-view" data-prod="<?php echo $post->ID; ?>">+ <?php _e('Quick View','flatsome'); ?></div>
	   	 <?php } ?>

	   	 <?php if($stock_status == "1") { ?><div class="out-of-stock-label"><?php _e( 'Out of stock', 'woocommerce' ); ?></div><?php }?>
		<?php if($flatsome_opt['add_to_cart_icon'] == "show") :
			$link = array(
				'url'   => '',
				'label' => '',
				'class' => ''
			);

			$handler = apply_filters( 'woocommerce_add_to_cart_handler', $product->product_type, $product );

			switch ( $handler ) {
				case "variable" :
					$link['url'] 	= apply_filters( 'variable_add_to_cart_url', get_permalink( $product->id ) );
					$link['label'] 	= apply_filters( 'variable_add_to_cart_text', __( 'Select options', 'woocommerce' ) );
				break;
				case "grouped" :
					$link['url'] 	= apply_filters( 'grouped_add_to_cart_url', get_permalink( $product->id ) );
					$link['label'] 	= apply_filters( 'grouped_add_to_cart_text', __( 'View options', 'woocommerce' ) );
				break;
				case "external" :
					$link['url'] 	= apply_filters( 'external_add_to_cart_url', get_permalink( $product->id ) );
					$link['label'] 	= apply_filters( 'external_add_to_cart_text', __( 'Read More', 'woocommerce' ) );
				break;
				default :
					if ( $product->is_purchasable() ) {
						$link['url'] 	= apply_filters( 'add_to_cart_url', esc_url( $product->add_to_cart_url() ) );
						$link['label'] 	= apply_filters( 'add_to_cart_text', __( 'Add to cart', 'woocommerce' ) );
						$link['class']  = apply_filters( 'add_to_cart_class', 'add_to_cart_button' );
					} else {
						$link['url'] 	= apply_filters( 'not_purchasable_url', get_permalink( $product->id ) );
						$link['label'] 	= apply_filters( 'not_purchasable_text', __( 'Read More', 'woocommerce' ) );
					}
				break;
			}
			echo apply_filters( 'woocommerce_loop_add_to_cart_link', sprintf('<div href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" class="%s product_type_%s tip-top add-to-cart-grid" title="%s"><div class="cart-icon">
				<strong><span class="icon-inner"></span></strong>
	          	<span class="cart-icon-handle"></span>
	    </div></div>', esc_url( $link['url'] ), esc_attr( $product->id ), esc_attr( $product->get_sku() ), esc_attr( $link['class'] ), esc_attr( $product->product_type ), esc_html( $link['label'] ) ), $product, $link );
		?>
		<?php endif; ?>
      </div><!-- end product-image -->

	<?php 
	// GRID STYLE 1
	if(!isset($flatsome_opt['grid_style']) || $flatsome_opt['grid_style'] == "grid1"){ ?>

      <div class="info text-center">
      	<?php $product_cats = strip_tags($product->get_categories('|', '', '')); ?>
          <h5 class="category"><?php list($firstpart) = explode('|', $product_cats); echo $firstpart; ?></h5>
          <div class="tx-div small"></div>
          <p class="name"><?php the_title(); ?></p>
          <?php do_action( 'woocommerce_after_shop_loop_item_title' ); ?>
	       <?php if ( in_array( 'yith-woocommerce-wishlist/init.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) { ?>
	                   <?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?>
			<?php } ?>
      </div><!-- end info -->

     <?php } 
     // GRID STYLE 2
     else if($flatsome_opt['grid_style'] == "grid2") { ?> 

      <div class="info style-<?php echo $flatsome_opt['grid_style']; ?>">
          <p class="name"><?php the_title(); ?></p>
          <?php do_action( 'woocommerce_after_shop_loop_item_title' ); ?>
           <?php if ( in_array( 'yith-woocommerce-wishlist/init.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) { ?>
                       <?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?>
 		   <?php } ?>
      </div><!-- end info -->

     <?php }
     // GRID STYLE 3
     else if($flatsome_opt['grid_style'] == "grid3") { ?> 
		
	 <div class="info style-<?php echo $flatsome_opt['grid_style']; ?>">
	    <table>
			<tr>
				<td>
			  <?php $product_cats = strip_tags($product->get_categories('|', '', '')); ?>
	          <p class="name"><?php the_title(); ?></p>
	          <h5 class="category"><?php list($firstpart) = explode('|', $product_cats); echo $firstpart; ?></h5>

	           <?php if ( in_array( 'yith-woocommerce-wishlist/init.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) { ?>
	                       <?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?>
	 		   <?php } ?></td>
				<td><?php do_action( 'woocommerce_after_shop_loop_item_title' ); ?>
				</td>
			</tr>
		 </table>
      </div><!-- end info -->	

      <?php }
     // GRID STYLE 4
     else if($flatsome_opt['grid_style'] == "grid4") { ?> 

	 <div class="info style-grid2">
	 	       <p class="name"><?php the_title(); ?></p>
	 	       	<small style="font-size:75%"><?php echo apply_filters( 'woocommerce_short_description', $post->post_excerpt ); ?></small>
	           <?php if ( in_array( 'yith-woocommerce-wishlist/init.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) { ?>
	                       <?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?>
	 		   <?php } ?>
				<?php do_action( 'woocommerce_after_shop_loop_item_title' ); ?>
      </div><!-- end info -->	

      <?php }
     // GRID STYLE 4
     else if($flatsome_opt['grid_style'] == "grid5") { ?> 
	<?php if ( in_array( 'yith-woocommerce-wishlist/init.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) { ?>
               <?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?>
	<?php } ?>
	
	<?php } ?>


</a>      	

<?php woocommerce_get_template( 'loop/sale-flash.php' ); ?>
</div> <!-- .inner-wrap -->
</li><!-- li.product-small -->