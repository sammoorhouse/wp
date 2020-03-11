<div id="progressally_progress_access_box" class="progressally-profile-container">
	<div class="progressally-profile-title-section">
		<div class="progressally-profile-title">ProgressAlly user progress and access</div>
	</div>
	<div class="progressally-setting-section">
		<div class="progressally-setting-section-header">User progress by page/post</div>
		<div class="progressally-user-profile-progress-container">
			<table class="progressally-user-profile-progress-table">
				<tbody>
					<tr class="progressally-user-profile-progress-table-header-row">
						<td>Post/page name</td>
						<td>Progress</td>
					</tr>
					{{completed-status}}
				</tbody>
			</table>
		</div>
	</div>
	<div class="progressally-setting-section">
		<div class="progressally-setting-section-header">Download user progress</div>
		<div class="progressally-setting-section-help-text">Download a CSV file containing the user progress.</div>
		<div class="progressally-setting-configure-block">
			<a class="progressally-button" href="{{user-progress-export-link}}" target="_blank">Download user progress</a>
		</div>
	</div>
	<div class="progressally-setting-section">
		<div class="progressally-setting-section-header">User footprint by page/post</div>
		<div class="progressally-user-profile-progress-container">
			<table class="progressally-user-profile-progress-table">
				<tbody>
					<tr class="progressally-user-profile-progress-table-header-row">
						<td>Post/page name</td>
						<td>Last access time</td>
					</tr>
					{{user-page-access}}
				</tbody>
			</table>
		</div>
	</div>
	<div class="progressally-setting-section">
		<div class="progressally-setting-section-header">User login log</div>
		<div class="progressally-user-profile-progress-container">
			<table class="progressally-user-profile-progress-table">
				<tbody>
					<tr class="progressally-user-profile-progress-table-header-row">
						<td>Login time</td>
					</tr>
					{{user-login-log}}
				</tbody>
			</table>
		</div>
	</div>
</div>
