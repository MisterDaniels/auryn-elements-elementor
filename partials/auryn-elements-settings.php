<?php

/**
 * Provide a admin area view for the plugin
 *
 * @link       https://www.auryn.com.br
 * @since      0.0.1
 *
 * @package    AurynElements
 * @subpackage AurynElements/partials
 */

?>
<div class="wrap">
    <div id="icon-themes" class="icon32"></div>  
    <h1><?php echo __('Auryn Elements Settings', 'auryn-elements'); ?></h1>
    <p><?php echo __('Company configuration to Auryn Elements', 'auryn-elements'); ?></p>
    <?php settings_errors(); ?>  
    <form method="POST" action="options.php">  
        <?php 
            settings_fields( 'general_settings' );
            do_settings_sections( 'general_section' ); 
        ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">
                    <?php echo __('Company domain', 'auryn-elements') ?>:   
                </th>
                <td>
                    <input type="text" name="company_domain" 
                        value="<?php echo get_option( 'company_domain' ); ?>" />
                    <p class="description">
                        <?php echo __('Must be only the host domain', 'auryn-elements') ?>: 
                        <strong>
                            <?php echo __('shop.auryn.com.br', 'auryn-elements') ?>
                        </strong>
                    </p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                        <?php echo __('Is Auryn principal Wordpress', 'auryn-elements') ?>:
                    </th>
                    <td>
                        <input type="checkbox" name="is_principal_wordpress" 
                            value="1" <?php checked('1', get_option( 'is_principal_wordpress' )); ?> />
                            <p class="description">
                                <?php echo __('If is the wordpress that serves to all clients', 'auryn-elements') ?>
                            </p>
                    </td>
                </th>
            </tr>
        </table>
        <?php submit_button(); ?>  
    </form> 
</div>