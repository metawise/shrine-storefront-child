<?php

/**
 * Plugin Name: Simple Image Slider
 * Description: A simple image slider plugin.
 */

function simple_image_slider()
{
    $image_urls = get_option('simple_slider_images', '');
    $images = explode("\n", $image_urls);
    ob_start(); // Start output buffering
?>
    <!-- Your HTML, CSS, and JavaScript goes here -->
    <style>
        #slider {
            position: relative;
            width: 100%;
            min-height: 450px;
            overflow: hidden;
            display: flex;
        }

        .slides .slide {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }

        .slides .slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .slides .slide.active {
            opacity: 1;
        }

        .controls {
            position: absolute;
            height: 100%;
            width: 100%;
            display: flex;
            justify-content: space-between;
        }

        .controls .prev {
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
            font-size: 3em;
            color: #fff;
            cursor: pointer;
            user-select: none;
        }

        .controls .next {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            font-size: 3em;
            color: #fff;
            cursor: pointer;
            user-select: none;
        }

        .pagination {
            position: relative;
            align-items: flex-end;
            display: flex;
            margin: 0 auto 20px;
        }

        .pagination span {
            background: rgba(255, 255, 255, 0.8);
            border-radius: 50%;
            display: inline-block;
            margin: 0 2px;
            width: 10px;
            height: 10px;
        }

        .pagination span.active {
            background: #fff;
        }
    </style>

    <div id="slider">
        <div class="slides">
            <?php foreach ($images as $image) : ?>
                <div class="slide">
                    <img src="<?php echo esc_url(trim($image)); ?>" alt="Slide" fetchpriority="high" />
                </div>
            <?php endforeach; ?>
        </div>
        <div class="controls">
            <span class="prev">‹</span>
            <span class="next">›</span>
        </div>
        <div class="pagination">
            <!-- Dots will be generated here by JS -->
        </div>
    </div>

    <script>
        var slides = document.querySelectorAll('.slide');
        var prev = document.querySelector('.prev');
        var next = document.querySelector('.next');
        var pagination = document.querySelector('.pagination');
        var activeIndex = 0;

        // Generate pagination dots
        for (var i = 0; i < slides.length; i++) {
            var dot = document.createElement('span');
            pagination.appendChild(dot);
        }

        updateSlider();

        prev.addEventListener('click', function() {
            activeIndex = (activeIndex - 1 + slides.length) % slides.length;
            updateSlider();
        });

        next.addEventListener('click', function() {
            activeIndex = (activeIndex + 1) % slides.length;
            updateSlider();
        });

        function updateSlider() {
            slides.forEach(function(slide, index) {
                slide.classList.toggle('active', index === activeIndex);
            });
            pagination.querySelectorAll('span').forEach(function(dot, index) {
                dot.classList.toggle('active', index === activeIndex);
            });
        }
    </script>
<?php
    return ob_get_clean(); // Return the buffered output
}

add_shortcode('simple-slider', 'simple_image_slider');

function simple_slider_admin_menu()
{
    add_menu_page(
        'Simple Slider Settings',
        'Simple Slider',
        'manage_options',
        'simple-slider',
        'simple_slider_settings_page',
        '',
        20
    );
}

add_action('admin_menu', 'simple_slider_admin_menu');

function simple_slider_settings_page()
{
    // Check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }

    // Your settings form could go here, for simplicity I'm just including a file
?>
    <div class="wrap">
        <h1><?= esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <!-- Your settings fields go here -->
            <!-- For simplicity, we'll just make a text area to paste image URLs, one per line -->
            <?php
            settings_fields('simple-slider');
            do_settings_sections('simple-slider');
            ?>
            <textarea name="simple_slider_images" rows="10" cols="50"><?php echo esc_textarea(get_option('simple_slider_images')); ?></textarea>
            <?php
            submit_button('Save Images');
            ?>
        </form>
    </div>
<?php
}

function simple_slider_register_settings()
{
    register_setting('simple-slider', 'simple_slider_images');
}

add_action('admin_init', 'simple_slider_register_settings');
?>