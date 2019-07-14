      <tr>
        <td class="<?php echo $diff_level; ?>"><?php echo $icon; ?><?php echo $name; ?></td>
      <?php foreach($revisions as $revision): ?>
        <td class="<?php echo $diff_level; ?> big"><div align="right"><?php echo $revision['bar']; ?></div></td>
        <td class="<?php echo $diff_level; ?> small"><div align="right"><?php echo $revision['percentage']; ?></div></td>
        <td class="<?php echo $diff_level; ?> small"><div align="right"><?php echo $revision['lines_number']; ?></div></td>
      <?php endforeach ?>
      <?php if ('n/a' === $diff_percentage): ?>
        <td class="<?php echo $diff_level; ?> small" colspan="3"><div align="center">n/a</div></td>
      <?php elseif ('+0.00%' === $diff_percentage): ?>
        <td class="<?php echo $diff_level; ?> small" colspan="3"><div align="center">No difference in percentages.</div></td>
      <?php else: ?>
        <td class="<?php echo $diff_level; ?> big" colspan="2"><div align="right"><?php echo $diff_bar; ?></div></td>
        <td class="<?php echo $diff_level; ?> small"><div align="right"><?php echo $diff_percentage; ?></div></td>
      <?php endif ?>
      </tr>
