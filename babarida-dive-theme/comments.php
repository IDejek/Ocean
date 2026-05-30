<?php
/**
 * Babarida Dive Center - Comments Template
 *
 * @package Babarida_Dive_Theme
 * @version 7.0.0
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

if (post_password_required()) {
    return;
}

 $comment_count = get_comments_number();
?>

<div id="comments" class="comments-area" aria-label="<?php esc_attr_e('Comments', 'babarida-dive'); ?>">

    <?php if (have_comments()) : ?>
        <h2 class="comments-title">
            <?php
            printf(
                esc_html(
                    _nx(
                        '%1$s Comment',
                        '%1$s Comments',
                        $comment_count,
                        'comments title',
                        'babarida-dive'
                    )
                ),
                esc_html(number_format_i18n($comment_count))
            );
            ?>
        </h2>

        <ol class="comment-list">
            <?php
            wp_list_comments(
                array(
                    'style'      => 'ol',
                    'short_ping' => true,
                    'avatar_size' => 60,
                    'walker'     => null,
                )
            );
            ?>
        </ol>

        <?php
        the_comments_navigation(
            array(
                'prev_text' => esc_html__('Older Comments', 'babarida-dive'),
                'next_text' => esc_html__('Newer Comments', 'babarida-dive'),
            )
        );
        ?>

    <?php endif; ?>

    <?php
    if (!comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')) :
        ?>
        <p class="no-comments"><?php esc_html_e('Comments are closed.', 'babarida-dive'); ?></p>
    <?php endif; ?>

    <?php
    comment_form(
        array(
            'class_form'         => 'comment-form',
            'title_reply'        => esc_html__('Leave a Comment', 'babarida-dive'),
            'title_reply_before' => '<h3 id="reply-title" class="comment-reply-title">',
            'title_reply_after'  => '</h3>',
            'comment_notes_before' => '',
        )
    );
    ?>

</div>
