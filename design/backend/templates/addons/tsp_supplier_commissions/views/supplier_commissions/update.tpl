{if $supplier_commission}
    {assign var="id" value=$supplier_commission.id}
{else}
    {assign var="id" value=0}
{/if}

{capture name="mainbox"}

<div id="tspsc_supplier_commissions">
    <form action="{""|fn_url}" method="post" name="supplier_commission_update_form" class="form-horizontal form-edit ">
    <input type="hidden" name="supplier_commission_id" value="{$id}" />
    <input type="hidden" name="selected_section" id="selected_section" value="{$smarty.request.selected_section}" />
       
    {capture name="tabsbox"}
    <div id="content_general">
        <fieldset>
             <div class="control-group">
                <label class="control-label">{__("tspsc_supplier")}:</label>
                <div class="controls">
                    <a href="{"suppliers.update?supplier_id=`$commission.supplier.supplier_id`"|fn_url}">{$commission.supplier.name}</a>
                </div>
            </div>
 
            <div class="control-group">
                <label class="control-label">{__("email")}:</label>
                <div class="controls">
                    {$commission.supplier.email}
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">{__("date_created")}:</label>
                <div class="controls">
                    {if $commission.date_created}{$commission.date_created|date_format:$settings.Appearance.date_format}{/if}
                </div>
            </div>

             <div class="control-group">
                <label class="control-label">{__("order")}:</label>
                <div class="controls">
                    <a href="{"orders.details?order_id=`$commission.order_id`"|fn_url}">#{$commission.order_id}</a>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">{__("product")}:</label>
                <div class="controls">
                    <a href="{"products.update?product_id=`$commission.product_id`"|fn_url}">{$commission.product.product} (Product Code: {$commission.product.product_code})</a>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">{__("promotion_cond_price")}:</label>
                <div class="controls">
                    <span>{include file="common/price.tpl" value=$commission.product_price}</span>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">{__("products_amount")}:</label>
                <div class="controls">
                    {$commission.product_quantity}
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">{__("discount")}:</label>
                <div class="controls">
                    {math equation="x * y" x=$commission.discount y=100} %
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">{__("total")}:</label>
                <div class="controls">
                    <span>{include file="common/price.tpl" value=$commission.product_total}</span>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">{__("tspsc_store_earned")}:</label>
                <div class="controls">
                    <span><strong>{include file="common/price.tpl" value=$commission.earned}</strong></span>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label"><font color="orange">{__('tspsc_supplier_commission')}:</font></strong></label>
                <div class="controls">
                    <span id="id_td_amount_{$commission.supplier.company_id}"><strong><font color="orange">{include file="common/price.tpl" value=$commission.total}</font></strong></span>
                </div>
            </div>

            <hr>

            <div class="control-group">
                <label class="control-label">{__('status')}:</label>
                <div class="controls">
                    {assign var="lang_open" value=__('open')|escape:dquotes}
                    {assign var="lang_pending" value=__('pending')|escape:dquotes}
                    {assign var="lang_successful" value=__('successful')|escape:dquotes}
                
                    {if $commission.status == 'O'}
                        <span><font color="red">{$lang_open}</font></span>
                    {elseif $commission.status == 'P'}
                        <span><font color="orange">{$lang_pending}</font></span>
                    {else}
                        <span><font color="green">{$lang_successful}</font></span>
                    {/if}
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">{__('totally_paid')}:</label>
                <div class="controls">
                    {include file="common/price.tpl" value=$commission.total_paid}
                </div>
            </div>

             <div class="control-group">
                <label class="control-label">PayPal {__('email')}:</label>
                <div class="controls">
                    {$commission.supplier.paypal_email}
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">{__('lmi_sys_trans_date')}:</label>
                <div class="controls">
                    {if $commission.date_paid}
                        {$commission.date_paid|date_format:$settings.Appearance.date_format}
                    {/if}
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">{__('transaction_id')}:</label>
                <div class="controls">
                    {$commission.transaction_id}
                </div>
            </div>

        </fieldset>
    </div>
    {/capture}
    {include file="common/tabsbox.tpl" content=$smarty.capture.tabsbox active_tab=$smarty.request.selected_section track=true}
    </form>
</div>
{capture name="buttons"}
    {include file="buttons/save_cancel.tpl" but_name="dispatch[supplier_commissions.update]" but_role="submit-link" but_target_form="supplier_commission_update_form" save=$id}
{/capture}

{if !$id}
    {assign var="title" value="{__("new")}  {__("tspsc_supplier_commission")}"}
{else}
    {assign var="title" value="{__("tspsc_editing_supplier_commission")} {__("for")} `$supplier_commission.user.lastname`, `$supplier_commission.user.firstname`"}
{/if}

{/capture}
{include file="common/mainbox.tpl" title=$title content=$smarty.capture.mainbox buttons=$smarty.capture.buttons select_languages=true}
