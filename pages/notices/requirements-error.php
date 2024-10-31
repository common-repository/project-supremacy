<div class="error">
	
	<p>Project Supremacy error: Your environment doesn't meet <strong>all</strong> of the system requirements listed below.</p>

	<ul class="ul-disc">
		
		<li>
			<strong>PHP <?php echo PRS_REQUIRED_PHP_VERSION; ?>+ is required</strong>
			<em>(You're running version <?php echo PHP_VERSION; ?>)</em>
		</li>

		<li>
			<strong>WordPress <?php echo PRS_REQUIRED_WP_VERSION; ?>+ is required</strong>
			<em>(You're running version <?php echo esc_html( $wp_version ); ?>)</em>
		</li>

        <li>
            <strong>PHP Memory Limit of 64M+ is required</strong>
            <em>(You're currently have <?php
                    $MEMORY_LIMIT = ini_get('memory_limit');
                    if ($MEMORY_LIMIT == -1) {
                        echo 'Unlimited';
                    } else {
                        echo $MEMORY_LIMIT;
                    }
                ?>)</em>
        </li>

	</ul>
	
</div>