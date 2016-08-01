<?php
/*
THIS INCLUDE NEEDS A OBJECT CALLED $paginator IN ORDER TO WORK
"Page %d of %d"|plang:$paginator->thisPage:$paginator->linksCnt
*/
?>
<?php if ( $paginator->hasPrevious ) { ?> <a href="<?php echo $paginator->previousLink; ?>">Previous</a> &nbsp; <?php } ?>
<?php if ( $paginator->hasNext ) { ?> <a href="<?php echo $paginator->nextLink; ?>">Next</a> &nbsp; <?php } ?>
<?php if ( $paginator->showSpan > 0 and $paginator->thisPage > $paginator->showSpan ) { ?><a href="<?php echo $paginator->links[1]['link']; ?>">&lt;&lt;</a> ...<?php } ?>
<?php foreach ( $paginator->links as $number => $p ) { ?>
<?php if ( $p['show'] ) { ?>
<?php if ( !$p['this'] ) { ?>
<a href="<?php echo $p['link']; ?>"><?php echo $number; ?></a>
<?php } elseif ( $paginator->linksCnt > 1 ) { ?>
<strong><?php echo $number; ?></strong>
<?php } ?>
<?php } ?>
<?php } ?>
<?php if ( $paginator->showSpan > 0 and $paginator->thisPage <= $paginator->linksCnt - $paginator->showSpan ) { ?>... <a href="<?php echo $p['link']; ?>">&gt;&gt;</a><?php } ?>
