<footer class="mt-5 bg-body-tertiary">
    <div class="container">
        <div class="row">
            <div class="col-md-6 d-flex justify-content-center justify-content-md-start align-items-center">
                <a href="<?php echo get_home_url() ?>">
                    <?php echo pt_logo(); ?>
                </a>
            </div>

            <div class="col-md-6 d-flex justify-content-center justify-content-md-end align-items-center text-align-center">
                <p class="mb-0">© <?php echo date('Y'); ?> <?php echo get_bloginfo( 'name' ); ?></p>
            </div>

            <?php /* ?>
        <div class="col-md-4 d-flex justify-content-center align-items-center">
        <?php get_template_part('template-parts/footer/footer-nav'); ?>
        </div>
        <?php */ ?>
        </div>
    </div>
</footer>