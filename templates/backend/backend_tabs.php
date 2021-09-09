<nav>
        <ul>
            <li class="licol1<?php if($data['curr_tab']=='articles'): ?> active<?php endif; ?>">
                <a href="<?php echo $data['edit_url'].$data['delim']; ?>tab=articles"><span class="fa fa-fw fa-clone"></span> <span><?php echo $this->t('Articles') ?></span></a>
            </li>
            <li class="licol2<?php if($data['curr_tab']=='groups'): ?> active<?php endif; ?>">
                <a href="<?php echo $data['edit_url'].$data['delim']; ?>tab=groups"><span class="fa fa-fw fa-cubes"></span> <span><?php echo $this->t('Groups') ?></span></a>
            </li>
<?php if($this->s('mode')=='advanced'): ?>
            <li class="licol3<?php if($data['curr_tab']=='tags'): ?> active<?php endif; ?>">
                <a href="<?php echo $data['edit_url'].$data['delim']; ?>tab=tags"><span class="fa fa-fw fa-tags"></span> <span><?php echo $this->t('Tags') ?></span></a>
            </li>
<?php endif; ?>
            <li class="licol4<?php if($data['curr_tab']=='options'): ?> active<?php endif; ?>">
                <a href="<?php echo $data['edit_url'].$data['delim']; ?>tab=options"><span class="fa fa-fw fa-wrench"></span> <span><?php echo $this->t('Options') ?></span></a>
            </li>
            <li class="red<?php if($data['curr_tab']=='readme'): ?> active<?php endif; ?>">
                <a href="<?php echo $data['edit_url'].$data['delim']; ?>tab=readme"><span class="fa fa-fw fa-info-circle"></span> <span><?php echo $this->t('Readme') ?></span></a>
            </li>
        </ul>
</nav><br style="clear:left" />