<?php
  // Expects $platform_bar_data, built by tps_build_platform_bar_data()
  // (inc/platform-page-router.php), to be set by the including template.
  if (empty($platform_bar_data)) return;
?>
<div class="platform-bar">
  <div class="platform-bar__inner">

    <nav class="platform-breadcrumb" aria-label="Breadcrumb">
      <a class="platform-breadcrumb__link" href="<?php echo esc_url($platform_bar_data['streamed_games_url']); ?>">Streamed Games</a>
      <span class="platform-breadcrumb__sep" aria-hidden="true">/</span>
      <span class="platform-breadcrumb__current"><?php echo esc_html($platform_bar_data['pillar_page_title']); ?></span>
    </nav>

    <div class="platform-identity-tabs">
      <div class="platform-identity">
        <a class="platform-identity__back" href="<?php echo esc_url($platform_bar_data['streamed_games_url']); ?>" aria-label="Back to Streamed Games">&larr;</a>
        <a class="platform-identity__link" href="<?php echo esc_url($platform_bar_data['pillar_page_url']); ?>">
          <span class="platform-identity__logo">
            <img src="<?php echo esc_url($platform_bar_data['pillar_page_image']); ?>" alt="" />
          </span>
          <span class="platform-identity__name"><?php echo esc_html($platform_bar_data['pillar_page_title']); ?></span>
        </a>
      </div>

      <div class="platform-tabs" role="tablist">
        <?php foreach ($platform_bar_data['tabs'] as $tab) : ?>
          <a class="platform-tabs__tab<?php echo $tab['is_active'] ? ' is-active' : ''; ?>"
             href="<?php echo esc_url($tab['url']); ?>"
             role="tab"
             <?php echo $tab['is_active'] ? 'aria-selected="true" aria-current="page"' : 'aria-selected="false"'; ?>>
            <?php echo esc_html($tab['label']); ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>

  </div><!-- .platform-bar__inner -->
</div><!-- .platform-bar -->
