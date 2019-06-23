<?php if (NULL !== $percent): ?>
      <div class="progress diff">
        <div class="progress-bar progress-bar-<?php echo $level; ?>" role="progressbar" aria-valuenow="<?php echo $percent; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo max(1, abs($percent / 2.0)); ?>%; <?php if ($percent < 0): ?> float: right; margin-right: 50%; <?php else: ?> float: left; margin-left: 50%; <?php endif ?>">
          <span class="sr-only"><?php echo $percent; ?>% covered (<?php echo $level; ?>)</span>
        </div>
      </div>
<?php endif; ?>
