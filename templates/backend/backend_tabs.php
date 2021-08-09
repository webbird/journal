<nav>
        <ul>
            <li class="licol1<?php if($data['curr_tab']=='articles'): ?> active<?php endif; ?>">
                <a href="<?php echo $data['edit_url']; ?>tab=articles"><i class="fa fa-fw fa-clone"></i> <span><?php echo $this->t('Articles') ?></span></a>
            </li>
            <li class="licol2<?php if($data['curr_tab']=='groups'): ?> active<?php endif; ?>">
                <a href="<?php echo $data['edit_url']; ?>tab=groups"><i class="fa fa-fw fa-cubes"></i> <span><?php echo $this->t('Groups') ?></span></a>
            </li>
<?php if($this->s('mode')=='advanced'): ?>
            <li class="licol3<?php if($data['curr_tab']=='tags'): ?> active<?php endif; ?>">
                <a href="<?php echo $data['edit_url']; ?>tab=tags"><i class="fa fa-fw fa-tags"></i> <span><?php echo $this->t('Tags') ?></span></a>
            </li>
<?php endif; ?>
            <li class="licol4<?php if($data['curr_tab']=='options'): ?> active<?php endif; ?>">
                <a href="<?php echo $data['edit_url']; ?>tab=options"><i class="fa fa-fw fa-wrench"></i> <span><?php echo $this->t('Options') ?></span></a>
            </li>
            <li class="red<?php if($data['curr_tab']=='readme'): ?> active<?php endif; ?>">
                <a href="<?php echo $data['edit_url']; ?>tab=readme"><i class="fa fa-fw fa-info-circle"></i> <span><?php echo $this->t('Readme') ?></span></a>
            </li>
        </ul>
</nav><br style="clear:left" />