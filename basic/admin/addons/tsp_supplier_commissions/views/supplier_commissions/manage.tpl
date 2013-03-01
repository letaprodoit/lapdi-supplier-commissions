{* $Id$ *}

{capture name="mainbox"}

{include file="addons/tsp_supplier_commissions/views/supplier_commissions/components/supplier_commissions_search.tpl"}

<form action="{""|fn_url}" method="post" name="supplier_commissions_form">

{include file="common_templates/pagination.tpl"}

{assign var="c_url" value=$config.current_url|fn_query_remove:"sort_by":"sort_order"}

{if $settings.DHTML.admin_ajax_based_pagination == "Y"}
	{assign var="ajax_class" value="cm-ajax"}

{/if}

<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table sortable">
<tr>
	<th width="1%" class="center">
		<input type="checkbox" name="check_all" value="Y" title="{$lang.check_uncheck_all}" class="checkbox cm-check-items" /></th>
	<th width="45%"><a class="{$ajax_class}{if $search.sort_by == "company"} sort-link-{$search.sort_order}{/if}" href="{"`$c_url`&amp;sort_by=company&amp;sort_order=`$search.sort_order`"|fn_url}" rev="pagination_contents">{$lang.tspsc_supplier}</a></th>
	<th width="10%"><a class="{$ajax_class}{if $search.sort_by == "discount"} sort-link-{$search.sort_order}{/if}" href="{"`$c_url`&amp;sort_by=discount&amp;sort_order=`$search.sort_order`"|fn_url}" rev="pagination_contents">{$lang.discount}</a></th>
	<th width="10%"><a class="{$ajax_class}{if $search.sort_by == "total"} sort-link-{$search.sort_order}{/if}" href="{"`$c_url`&amp;sort_by=total&amp;sort_order=`$search.sort_order`"|fn_url}" rev="pagination_contents">{$lang.total}</a></th>
	<th width="10%"><a class="{$ajax_class}{if $search.sort_by == "total_paid"} sort-link-{$search.sort_order}{/if}" href="{"`$c_url`&amp;sort_by=total_paid&amp;sort_order=`$search.sort_order`"|fn_url}" rev="pagination_contents">{$lang.totally_paid}</a></th>
	<th width="20%" class="center"><a class="{$ajax_class}{if $search.sort_by == "date_created"} sort-link-{$search.sort_order}{/if}" href="{"`$c_url`&amp;sort_by=date_created&amp;sort_order=`$search.sort_order`"|fn_url}" rev="pagination_contents">{$lang.date_created}</a></th>
	<th width="5%"><a class="{$ajax_class}{if $search.sort_by == "status"} sort-link-{$search.sort_order}{/if}" href="{"`$c_url`&amp;sort_by=status&amp;sort_order=`$search.sort_order`"|fn_url}" rev="pagination_contents">{$lang.status}</a></th>
	<th>&nbsp;</th>
</tr>
{if $supplier_commissions}
{foreach from=$supplier_commissions key="id" item="supplier_commissions"}
<tr {cycle values="class=\"table-row\", "}>
	<td width="1%" class="center">
		<input type="checkbox" name="commission_ids[]" value="{$supplier_commissions.id}" class="checkbox cm-item" /></td>
	<td><a href="{"companies.update?company_id=`$supplier_commissions.supplier_id`"|fn_url}">{$supplier_commissions.company}</a></td>
	<td><input type="hidden" name="supplier_commissions[{$id}][discount]" value="{$supplier_commissions.discount}" /> {math equation="x * y" x=$supplier_commissions.discount y=100}%</td>
	<td><input type="hidden" name="supplier_commissions[{$id}][total]" value="{$supplier_commissions.total}" />{include file="common_templates/price.tpl" value=$supplier_commissions.total}</td>
	<td><input type="hidden" name="supplier_commissions[{$id}][total_paid]" value="{$supplier_commissions.total_paid}" />{include file="common_templates/price.tpl" value=$supplier_commissions.total_paid}</td>
	<td class="center">{$supplier_commissions.date_created|date_format:"`$settings.Appearance.date_format` `$settings.Appearance.time_format`"}</td>
	<td>
		{assign var="lang_open" value=$lang.open|escape:dquotes}
		{assign var="lang_pending" value=$lang.pending|escape:dquotes}
		{assign var="lang_successful" value=$lang.successful|escape:dquotes}

		{if $supplier_commissions.status == 'O'}
			<span><font color="red">{$lang_open}</font></span>
		{elseif $supplier_commissions.status == 'P'}
			<span><font color="orange">{$lang_pending}</font></span>
		{else}
			<span><font color="green">{$lang_successful}</font></span>
		{/if}
	</td>
	<td class="nowrap">
		{capture name="tools_items"}
		<li><a class="cm-confirm text-button-edit" href="{"supplier_commissions.process_commission?commission_id=`$supplier_commissions.id`"|fn_url}">{$lang.process}</a></li>
		<li><a class="cm-confirm text-button-edit" href="{"supplier_commissions.delete?commission_id=`$supplier_commissions.id`"|fn_url}">{$lang.delete}</a></li>
		{/capture}
		{include file="common_templates/table_tools_list.tpl" prefix=$id tools_list=$smarty.capture.tools_items href="supplier_commissions.update?commission_id=`$id`" link_text=$lang.view}
	</td>
</tr>
{/foreach}
{else}
<tr class="no-items">
	<td colspan="7"><p>{$lang.no_data}</p></td>
</tr>
{/if}
</table>

{if $supplier_commissions}
	{include file="common_templates/table_tools.tpl" href="#supplier_commissions"}
{/if}

{include file="common_templates/pagination.tpl"}

{if $supplier_commissions}
	<div class="buttons-container buttons-bg">
		{include file="buttons/delete_selected.tpl" but_name="dispatch[supplier_commissions.do_delete]" but_role="button_main" but_meta="cm-process-items cm-confirm"}
		{include file="buttons/button.tpl" but_text=$lang.tspsc_pay_selected_commissions but_name="dispatch[supplier_commissions.process_commissions]" but_meta="cm-confirm cm-process-items" but_role="button_main"}
	</div>
{/if}

</form>

{/capture}
{include file="common_templates/mainbox.tpl" title=$lang.tspsc_supplier_commissions content=$smarty.capture.mainbox title_extra=$smarty.capture.title_extra}
