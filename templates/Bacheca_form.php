<div class="bacheca-form-container">
    <?php if ( $attributes['show_title'] ) : ?>
        <h2><?php _e( 'Bacheca Form', 'bacheca' ); ?></h2>
    <?php endif; ?>
    
    <?php
        comment_form( array(
            'fields' => array(
                'author' => '<p class="comment-form-author">' .
                            '<label for="author">' . __( 'Name', 'domainreference' ) . '</label> ' .
                            '<input id="author" name="author" type="text" size="30" /></p>',
            ),
            'comment_field' => '<p class="comment-form-comment">' .
                               '<label for="comment">' . _x( 'Comment', 'noun' ) . '</label>' .
                               '<textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea>' .
                               '</p>',
            'label_submit' => __( 'Send', 'domainreference' ),
            'comment_notes_before' => '',
            'comment_notes_after' => '',
        ) );
    ?>
    
</div>
