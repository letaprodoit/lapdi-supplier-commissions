{* $Id$ *}

{capture name="mainbox"}

{if $commission}

<div class="form-field">
	<label>{$lang.tspsc_supplier}:</label>
	<a href="{"companies.update?company_id=`$commission.supplier.company_id`"|fn_url}">{$commission.supplier.company}</a>
</div>

<div class="form-field">
	<label>{$lang.email}:</label>
	{$commission.supplier.email}
</div>

<div class="form-field">
	<label>{$lang.date_created}:</label>
	{$commission.date_created|date_format:$settings.Appearance.date_format}
</div>

<div class="form-field">
	<label>{$lang.order}:</label>
	<a href="{"orders.details?order_id=`$commission.order_id`"|fn_url}">#{$commission.order_id}</a>
</div>

<div class="form-field">
	<label>{$lang.product}:</label>
	<a href="{"products.update?product_id=`$commission.product_id`"|fn_url}">{$commission.product.product} (Product Code: {$commission.product.product_code})</a>
</div>

<div class="form-field">
	<label>{$lang.promotion_cond_price}:</label>
	<span>{include file="common_templates/price.tpl" value=$commission.product_price}</span>
</div>

<div class="form-field">
	<label>{$lang.products_amount}:</label>
	{$commission.product_quantity}
</div>

<div class="form-field">
	<label>{$lang.discount}:</label>
	{math equation="x * y" x=$commission.discount y=100} %
</div>

<div class="form-field">
	<label>{$lang.total}:</label>
	<span>{include file="common_templates/price.tpl" value=$commission.product_total}</span>
</div>

<div class="form-field">
	<label><strong>{$lang.tspsc_store_earned}:</strong></label>
	<span><strong>{include file="common_templates/price.tpl" value=$commission.earned}</strong></span>
</div>

<div class="form-field">
	<label><strong><font color="orange">{$lang.tspsc_supplier_commission}:</font></strong></label>
	<span id="id_td_amount_{$commission.supplier.company_id}"><strong><font color="orange">{include file="common_templates/price.tpl" value=$commission.total}</font></strong></span>
</div>


<hr>


<div class="form-field">
	<label>{$lang.status}:</label>
	{assign var="lang_open" value=$lang.open|escape:dquotes}
	{assign var="lang_pending" value=$lang.pending|escape:dquotes}
	{assign var="lang_successful" value=$lang.successful|escape:dquotes}

	{if $commission.status == 'O'}
		<span><font color="red">{$lang_open}</font></span>
	{elseif $commission.status == 'P'}
		<span><font color="orange">{$lang_pending}</font></span>
	{else}
		<span><font color="green">{$lang_successful}</font></span>
	{/if}
</div>

<div class="form-field">
	<label>{$lang.totally_paid}:</label>
	{include file="common_templates/price.tpl" value=$commission.total_paid}
</div>

<div class="form-field">
	<label>PayPal {$lang.email}:</label>
	{$commission.supplier.paypal_email}
</div>

<div class="form-field">
	<label>{$lang.lmi_sys_trans_date}:</label>
	{if $commission.date_paid}
		{$commission.date_paid|date_format:$settings.Appearance.date_format}
	{/if}
</div>

<div class="form-field">
	<label>{$lang.transaction_id}:</label>
	{$commission.transaction_id}
</div>

{/if}
{/capture}

{include file="common_templates/mainbox.tpl" title="`$lang.tspsc_editing_supplier_commission`:&nbsp; `$commission.supplier.company`" content=$smarty.capture.mainbox select_languages=true}
