<?php 

function show_more_user_data($user) { ?>
    <br>
    <h3><?php _e("Informations supplémentaires", "blank"); ?></h3>

    <table class="form-table">
        <tr>
            <th><label for="address"><?php _e("Addresse"); ?></label></th>
            <td>
                <input type="text" name="address" id="address" value="<?php echo esc_attr( get_the_author_meta( 'user_address', $user->ID ) ); ?>" class="regular-text" /><br />
            </td>
        </tr>
        <tr>
            <th><label for="phone"><?php _e("Téléphone"); ?></label></th>
            <td>
                <input type="text" name="user_phone" id="phone" value="<?php echo esc_attr( get_the_author_meta( 'user_phone', $user->ID ) ); ?>" class="regular-text" /><br />
            </td>
        </tr>
    </table>
<?php 
}

?>