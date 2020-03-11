<div class="progressally-setting-section">
	<div class="progressally-setting-section-header">Event Log</div>
</div>
<div id="progressally-event-log-container">
	<div class="progressally-setting-configure-block">
		<div class="progressally-setting-section-sub-header">Payment filters</div>
		<table class="progressally-filter-selection-table">
			<tbody>
				<tr>
					<th scope="row">
						<label for="progressally-event-log-filter-event-id-input">Event ID</label>
					</th>
					<td>
						<input type="text" class="progressally-update-event-log" id="progressally-event-log-filter-event-id-input" progressally-param="event-id" value="" />
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="progressally-event-log-filter-user-id-input">User ID</label>
					</th>
					<td>
						<input type="text" class="progressally-update-event-log" id="progressally-event-log-filter-user-id-input" progressally-param="user-id" value="" />
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="progressally-setting-configure-block tablenav">
		<div class="tablenav-pages">
			<span class="pagination-links">
				<a class="first-page" title="Go to the first page" progressally-event-log-action="first">«</a>
				<a class="prev-page" title="Go to the previous page" progressally-event-log-action="-1">‹</a>
				<span class="paging-input"><input class="current-page" title="Current page" type="text" value="1" size="3" progressally-param="page" id="progressally-event-log-current-page" /> of <span id="progressally-event-log-max-page">1</span></span>
				<a class="next-page" title="Go to the next page" progressally-event-log-action="1">›</a>
				<a class="last-page" title="Go to the last page"  progressally-event-log-action="last">»</a>
			</span>
		</div>
	</div>
	<div class="progressally-duplicate-scroll-bar" id="progressally-event-log-table-container-top-scroll-bar" progressally-duplicate-scroll-target="#progressally-event-log-table-container"><div></div></div>
	<div class="progressally-log-summary-table-container" id="progressally-event-log-table-container" progressally-duplicate-scroll="#progressally-event-log-table-container-top-scroll-bar">
		<table class="progressally-log-summary-table" id="progressally-event-log-table" progressally-duplicate-scroll-width="#progressally-event-log-table-container-top-scroll-bar">
			<tbody>
				<tr valign="top" id='progressally-event-log-header-row'>
					<td>Fetching data. Please wait</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>