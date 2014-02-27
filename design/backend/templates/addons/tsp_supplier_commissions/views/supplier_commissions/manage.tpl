{capture name="mainbox"}

<form action="{""|fn_url}" method="post" name="manage_supplier_commissions_form">

{include file="common/pagination.tpl"}

{assign var="c_url" value=$config.current_url|fn_query_remove:"sort_by":"sort_order"}
{assign var="c_icon" value="<i class=\"exicon-`$search.sort_order_rev`\"></i>"}
{assign var="c_dummy" value="<i class=\"exicon-dummy\"></i>"}

{assign var="rev" value=$smarty.request.content_id|default:"pagination_contents"}

{if $supplier_commissions}
<table width="100%" class="table table-middle">
<thead>
<tr>
    <th  class="left">
        {include file="common/check_items.tpl" check_statuses=$simple_statuses}
    </th>
    <th width="45%"><a class="cm-ajax" href="{"`$c_url`&sort_by=company&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("tspsc_supplier")}{if $search.sort_by == "supplier_commission_id"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
    <th width="10%"><a class="cm-ajax" href="{"`$c_url`&sort_by=status&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("status")}{if $search.sort_by == "status"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
    <th width="10%"><a class="cm-ajax" href="{"`$c_url`&sort_by=discount&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("discount")}{if $search.sort_by == "discount"}{$c_icon nofilter}{/if}</a></th>
    <th width="10%"><a class="cm-ajax" href="{"`$c_url`&sort_by=total&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("total")}{if $search.sort_by == "total"}{$c_icon nofilter}{/if}</a></th>
    <th width="10%"><a class="cm-ajax" href="{"`$c_url`&sort_by=total_paid&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("totally_paid")}{if $search.sort_by == "totally_paid"}{$c_icon nofilter}{/if}</a></th>
    <th width="10%"><a class="cm-ajax" href="{"`$c_url`&sort_by=date_created&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("date_created")}{if $search.sort_by == "date_created"}{$c_icon nofilter}{/if}</a></th>
    <th>&nbsp;</th>
</tr>
</thead>

{foreach from=$supplier_commissions key="id" item="supplier_commission"}
<tr class="cm-row-status-{$supplier_commission.color_status|lower}">
    <td class="left">
        <input type="checkbox" name="supplier_commission_ids[]" value="{$supplier_commission.id}" class="cm-item cm-item-status-{$supplier_commission.status|lower}" /></td>
    <td>
        <a href="{"companies.update?company_id=`$supplier_commissions.supplier_id`"|fn_url}">{$supplier_commissions.company}</a>
        {include file="views/companies/components/company_name.tpl" object=$supplier_commission}
    </td>
    <td>
        {assign var="this_url" value=$config.current_url|escape:"url"}
        {include file="common/select_popup.tpl" suffix="o" id=$supplier_commission.id status=$supplier_commission.status items_status=$simple_statuses update_controller="supplier_commissions" notify=true status_target_id="`$rev`" extra="&return_url=`$this_url`" statuses=$statuses btn_meta="btn btn-info o-status-`$supplier_commission.color_status` btn-small"|lower}
    </td>
    <td><input type="hidden" name="supplier_commissions[{$id}][discount]" value="{$supplier_commissions.discount}" /> {math equation="x * y" x=$supplier_commissions.discount y=100}%</td>
    <td><input type="hidden" name="supplier_commissions[{$id}][total]" value="{$supplier_commissions.total}" />{include file="common/price.tpl" value=$supplier_commissions.total}</td>
    <td><input type="hidden" name="supplier_commissions[{$id}][total_paid]" value="{$supplier_commissions.total_paid}" />{include file="common/price.tpl" value=$supplier_commissions.total_paid}</td>
    <td>{if $supplier_commission.date_created}{$supplier_commission.date_created|date_format:"`$settings.Appearance.date_format` `$settings.Appearance.time_format`"}{/if}</td>
    {capture name="tools_items"}
        <li>{btn type="list" href="supplier_commissions.update?commission_id=`$supplier_commissions.id`" text={__("view")}}</li>
        {hook name="orders:list_extra_links"}
            {assign var="current_redirect_url" value=$config.current_url|escape:url}
            <li>{btn type="list" href="supplier_commissions.process_commission?commission_id=`$supplier_commissions.id`&redirect_url=`$current_redirect_url`" class="cm-confirm" text={__("process")}}</li>
            <li>{btn type="list" href="supplier_commissions.delete?commission_id=`$supplier_commissions.id`&redirect_url=`$current_redirect_url`" class="cm-confirm" text={__("delete")}}</li>
        {/hook}
    {/capture}
</tr>
{/foreach}
</table>
{else}
    <p class="no-items">{__("no_data")}</p>
{/if}

{include file="common/pagination.tpl"}
</form>
{/capture}

{capture name="buttons"}
    {capture name="tools_list"}
        {if $supplier_commissions}
            <li>{btn type="list" text={__("tspsc_pay_selected_commissions")} dispatch="dispatch[supplier_commissions.process_commissions]" form="manage_supplier_commissions_form"}</li>
            <li class="divider"></li>
            <li>{btn type="delete_selected" dispatch="dispatch[supplier_commissions.m_delete]" form="manage_supplier_commissions_form"}</li>
        {/if}
    {/capture}
    {dropdown content=$smarty.capture.tools_list}
{/capture}

{capture name="adv_buttons"}
{/capture}

{capture name="sidebar"}
    {capture name="content_sidebar"}
        <ul class="nav nav-list">
            <li><a href="{"addons.manage#grouptsp_supplier_commissions"|fn_url}"><i class="icon-cog"></i>{__("tspsc_supplier_commission")} {__("settings")}</a></li>
        </ul>
    {/capture}
    {include file="common/sidebox.tpl" content=$smarty.capture.content_sidebar title=__("settings")}
{/capture}

{include file="common/mainbox.tpl" title=__("tspsc_supplier_commissions") content=$smarty.capture.mainbox buttons=$smarty.capture.buttons adv_buttons=$smarty.capture.adv_buttons sidebar=$smarty.capture.sidebar}