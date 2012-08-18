<div class="span2">
    <div class="well-side">

		<ul class="nav nav-list">
			<?php if($is_zombie){ ?>
				<li class="nav-header">
					Game Actions
				</li>
				<li>
					<a href = "<?php echo site_url('game/'. $slug . '/register_kill');?>"><i class="icon-tint"></i> Register Kill</a>
				</li>
				<li class="divider"></li>
			<?php } ?>
			<li class="nav-header">
				Game Info
			</li>
			<li>
				<a href = "<?php echo site_url('game/'. $slug);?>"><i class="icon-user"></i> Players</a>
			</li>
			<li>
				<a href = "<?php echo site_url('game/'. $slug . '/teams');?>"><i class="icon-flag"></i> Teams</a>
			</li>
			<li>
				<a href = "<?php echo site_url('game/'. $slug . '/stats');?>"><i class="icon-tasks"></i> Game Stats</a>
			</li>
			<li class="divider"></li>
			<li class="nav-header">
				All Games
			</li>
			<li>
				<a href = "<?php echo site_url("overview");?>"><i class="icon-th-list"></i> Overview</a>
			</li>
		</ul>
	</div>
</div>