<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php use_helper('JavascriptBase') ?>
    <?php echo javascript_tag("
      i18n_strings = new Array();
      i18n_strings['javascripts.map.base.osmarender'] = 'Osmarender';
      i18n_strings['javascripts.map.base.noname'] = 'NoName';
      i18n_strings['javascripts.map.base.cycle_map'] = 'Cycle Map';
      i18n_strings['javascripts.map.base.mapnik'] = 'Mapnik';
      i18n_strings['javascripts.map.overlays.maplint'] = 'Maplint';
      i18n_strings['javascripts.site.history_disabled_tooltip'] = 'Zoom in to view edits for this area';
      i18n_strings['javascripts.site.history_tooltip'] = 'View edits for this area';
      i18n_strings['javascripts.site.edit_tooltip'] = 'Edit the map';
      i18n_strings['javascripts.site.history_zoom_alert'] = 'You must zoom in to view edits for this area';
      i18n_strings['javascripts.site.edit_zoom_alert'] = 'You must zoom in to edit the map';
      i18n_strings['javascripts.site.edit_disabled_tooltip'] = 'Zoom in to edit the map';
    ") ?>
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
  </head>
  <body>
    <?php if ($sf_user->isAuthenticated()): ?>
      <div id="menu">
        <?php echo link_to('Gesti&oacute;n de usuarios', '@sf_guard_user') ?> |
				<?php echo link_to('Gesti&oacute;n de buses', 'bus/index') ?> |
				<?php echo link_to('Gesti&oacute;n de rutas', '@homepage') ?>
      </div>
    <?php endif ?>
    <?php echo $sf_content ?>
  </body>
</html>
