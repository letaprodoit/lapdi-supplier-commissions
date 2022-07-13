{* $Id$ *}

{capture name="section"}

<form name="supplier_commissions_search_form" action="{""|fn_url}" method="get">

<table cellpadding="0" cellspacing="0" border="0" class="search-header">
<tr>
	<td class="search-field">
		<label for="elm_vendor_id">{$lang.tspsc_supplier}:</label>
		<div class="break">
			<select name="company_id" id="elm_company_id">
				<option value="0" {if !$search.company_id}selected="selected"{/if}> -- </option>
				{html_options options=$company_list selected=$search.company_id}
			</select>
		</div>
	</td>
	<td class="search-field">
		<label for="elm_status">{$lang.status}:</label>
		<div class="break">
			<select name="status" id="elm_status">
				<option value=""> -- </option>
				<option value="O" {if $search.status == "O"}selected="selected"{/if}>{$lang.open}</option>
				<option value="P" {if $search.status == "P"}selected="selected"{/if}>{$lang.pending}</option>
				<option value="S" {if $search.status == "S"}selected="selected"{/if}>{$lang.successful}</option>
			</select>
		</div>
	</td></td>
	<td class="search-field nowrap">
		<label for="amount_from">{$lang.total} ({$currencies.$primary_currency.symbol}):</label>
		<div class="break">
			<input type="text" name="amount_from" size="7" value="{$search.amount_from}" class="input-text" id="amount_from" />&nbsp;&ndash;&nbsp;
			<input type="text" name="amount_to" size="7" value="{$search.amount_to}" class="input-text" />
		</div>
	</td>
	<td class="buttons-container">
		{include file="buttons/search.tpl" but_name="dispatch[supplier_commissions.manage]" but_role="submit"}
	</td>
</tr>
</table>

{capture name="advanced_search"}

<div class="search-field">
	<label>{$lang.period}:</label>
	{include file="common_templates/period_selector.tpl" period=$search.period form_name="supplier_commissions_search_form" time_from=$search.time_from time_to=$search.time_to}
</div>

{/capture}

{include file="common_templates/advanced_search.tpl" content=$smarty.capture.advanced_search dispatch="supplier_commissions.manage" view_type="supplier_commissions"}

</form>
{/capture}
{include file="common_templates/section.tpl" section_content=$smarty.capture.section}
