<div class="progressally-setting-section">
	<div class="progressally-setting-section-header" id="progressally-dashboard-page-detailview-title">Detailed Report</div>
	<table class="progressally-dashboard-detail-search-container">
		<tbody>
			<td style="width:80px;text-align:center;"><label for="progressally-detail-page-selection">Select page</label></td>
			<td>
				<select type="text" id="progressally-detail-page-selection" class="progressally-autocomplete-add">
					<option value="0"></option>
					<?php echo $post_selection; ?>
				</select>
			</td>
		</tbody>
	</table>
	<div id="progressally-dashboard-page-detailview-wait-row" class="progressally-dashboard-page-notice-row">
		Fetching data. Please wait.
	</div>
	<div class="progressally-dashboard-page-detailview-report" id="progressally-dashboard-page-detailview-report">
	</div>
</div>