<tr>
	<td colspan="4">
		<label>$Ticket.GroupNameFieldLabel:</label> $Group.Name
	</td>
</tr>
<tr>
	<td colspan="4">
		<div style="border: 1px solid #ddd; border-left-width: 15px; margin-left: 10px; padding: 1em;">
			<table class="group-table table">
				<tbody>
					<% loop	TicketSelections %>
						$RenderRow($Up.BaseLink)
					<% end_loop %>
				</tbody>
			</table>
		</div>
	</td>
</tr>
