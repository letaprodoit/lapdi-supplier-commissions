{if $order_info}

<style type="text/css">
body,th,td,tt,p,div,span {$ldelim}
	color: #000000;
	font-family: tahoma, verdana, arial, sans-serif;
	font-size: 11px;
{$rdelim}
p,ul {$ldelim}
	margin-top: 6px;
	margin-bottom: 6px;
{$rdelim}
.form-field-caption {$ldelim}
	font-style:italic;
{$rdelim}
.form-title	{$ldelim}
	background-color: #ffffff;
	color: #141414;
	font-weight: bold;
{$rdelim}
</style>

<table cellpadding="0" cellspacing="0" width="100%"	border="0">
<tr>
	<td><img src="{$images_dir}/spacer.gif" width="1" height="1" border="0" alt="" /></td>
	<td width="600" style="border: #444444; border-style: solid; border-width: 2px" align="center">
		<table cellpadding="10" cellspacing="0" width="100%" border="0">
		<tr>
			<td>
			{* Customer info *}
			{if !$profile_fields}
			{assign var="profile_fields" value='I'|fn_get_profile_fields}
			{/if}
			{assign var="contact_fields" value="`$profile_fields.C`"|array_slice:0:4}
			<table cellpadding="4" cellspacing="0" border="0" width="100%">
			<tr>
				<td valign="top" width="100%">
				<table>
				<tr>
					<td>
						<table>
							{include file="profiles/profile_fields_info.tpl" fields=$contact_fields title=__('contact_information') user_data=$order_info}
						</table>
					</td>
				</tr>
				</table>
				</td>
			</tr>
			</table>
			<table cellpadding="4" cellspacing="0" border="0" width="100%">
			<tr>
				<td valign="top" width="50%">
				<table>
				<tr>
					<td>
						<table>
						{include file="profiles/profile_fields_info.tpl" fields=$profile_fields.B title=__('billing_address') user_data=$order_info}
						</table>
					</td>
				</tr>
				</table>
				</td>
				<td width="1%">&nbsp;</td>
				<td valign="top" width="49%">
					<table>
						{include file="profiles/profile_fields_info.tpl" fields=$profile_fields.S title=__('shipping_address') user_data=$order_info}
					</table>
				</td>
			</tr>
			</table>
			<p></p><br />
			{* /Customer info *}

			{* Ordered products *}
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<tr>
			<td valign="top">
			<table cellpadding="2" cellspacing="1" border="0" width="100%" bgcolor="#000000">
			<tr>
				<td width="10%" align="center" bgcolor="#dddddd"><b>{__('sku')}</b></td>
				<td width="40%" bgcolor="#dddddd"><b>{__('product')}</b></td>
				<td width="10%" align="center" bgcolor="#dddddd"><b>{__('quantity')}</b></td>
				<td width="10%" align="center" bgcolor="#dddddd"><b>{__('price')}</b></td>
				<td width="10%" align="center" bgcolor="#dddddd"><b>{__('total')}</b></td>
				<td width="10%" align="center" bgcolor="#dddddd"><b>{__('discount')}</b></td>
				<td width="10%" align="center" bgcolor="#dddddd"><b>{__('commission')}</b></td>
			</tr>
			{foreach from=$order_info.items item="oi"}
			{if $oi.company_id == $supplier_id}
			<tr>
				<td bgcolor="#ffffff">{$oi.product_code|default:"-"}</td>
				<td bgcolor="#ffffff">{$oi.product}
					{if $oi.product_options}<div style="padding-top: 1px; padding-bottom: 2px;">{include file="common/options_info.tpl" product_options=$oi.product_options}</div>{/if}</td>
				<td bgcolor="#ffffff" align="center">{$oi.amount}</td>
				<td bgcolor="#ffffff" align="center">{include file="common/price.tpl" value=$oi.price}</td>
				<td bgcolor="#ffffff" align="center">{include file="common/price.tpl" value=$oi.subtotal}</td>
				<td bgcolor="#ffffff" align="center">{$oi.discount} %</td>
				<td bgcolor="#ffffff" align="center">{include file="common/price.tpl" value=$oi.commission}</td>
			</tr>
			{/if}
			{/foreach}
			</table>
			</td>
			</tr>
			</table>
			{* /Ordered products *}

			{* Order totals *}
			<div align="right">
			<table>
			<tr>
				<td align="right" nowrap="nowrap"><b>{__('shipping_cost')}:</b>&nbsp;</td>
				<td align="right" nowrap="nowrap">{include file="common/price.tpl" value=$shipping_cost}</td>
			</tr>
			</table><br />
			</div>
			{* /Order totals *}
			</td>
		</tr>
		</table>
	</td>
	<td><img src="{$images_dir}/spacer.gif" width="1" height="1" border="0" alt="" /></td>
</tr>
</table>

{/if}