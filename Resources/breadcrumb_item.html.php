<?php if ($active): ?>
  <li class="breadcrumb-item active"><?php echo $name ?></li>
<?php else: ?>
  <li class="breadcrumb-item"><a href="<?php echo $path_to_root ?>index.html"><?php echo $name ?></a></li>
<?php endif ?>
