<div id="progressally-dashboard-page-overview" class="progressally-setting-section">
	<div class="progressally-setting-section-header">Page Progress Reports</div>
	<div class="progressally-setting-section-help-text">view progress information for posts/pages</div>

	<input type="hidden" id="progressally-access-current-page" value="0" />
	<input type="hidden" id="progressally-access-current-type" value="page" />
	<div id="progressally-dashboard-overview-search" class="tablenav top">
		<div class="progressally-dashboard-page-type-container">
			<label for="progressally-access-type-selection">Currently showing</label>
			<select id="progressally-access-type-selection" progressally-param="page-type">
				<?php foreach ($page_types as $name => $label) { ?>
					<option value="<?php echo esc_attr($name); ?>"><?php echo esc_attr($label); ?></option>
				<?php } ?>
			</select>
		</div>
		<div class="progressally-dashboard-page-searchbox-container">
			<label for="progressally-dashboard-page-search">Search: </label>
			<input id="progressally-dashboard-page-search" type="text" progressally-param="page-name" value="" />
			<div class="progressally-dashboard-page-searchbox-botton" id="progressally-dashboard-overview-page-search">
				Search
			</div>
		</div>
		<div class="tablenav-pages">
			<span class="pagination-links">
				<a class="first-page" title="Go to the first page" progressally-access-action="first">«</a>
				<a class="prev-page" title="Go to the previous page" progressally-access-action="-1">‹</a>
				<span class="paging-input"><input class="current-page" title="Current page" type="text" value="1" size="3" progressally-param="page-num" id='progressally-access-page-input' /> of <span id="progressally-access-max-page">1</span></span>
				<a class="next-page" title="Go to the next page" progressally-access-action="1">›</a>
				<a class="last-page" title="Go to the last page" progressally-access-action="last">»</a>
			</span>
		</div>
		<br class="clear" />
	</div>

	<div class="progressally-dashboard-container">
		<div id="progressally-dashboard-page-wait-row" class="progressally-dashboard-page-notice-row">
			Fetching data. Please wait.
		</div>
		<div id="progressally-dashboard-page-error-row" class="progressally-dashboard-page-notice-row">
		</div>
		<div class="progressally-dashboard-page-content-wrapper">
			<div class="progressally-dashboard-page-header">Page Title</div>
			<div id="progressally-dashboard-page-content-container"></div>
		</div>
	</div>
</div>