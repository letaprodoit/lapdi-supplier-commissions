{* $Id$ *}
                                                   
{__('dear_sirs')},<br /><br />

{if $status_inventory == 'D'}
{__('supplier_email_header')}<br /><br />
{/if}

<b>{__('invoice')}:</b><br>

{include file="addons/tsp_supplier_commissions/commission_invoice.tpl"}

{__('contact_information')}:<br /><br />
<span style="margin-left:20px;">&nbsp;</span>{$settings.Company.company_name}<br />
<span style="margin-left:20px;">&nbsp;</span>{if $settings.Company.company_address}{$settings.Company.company_address}, {/if}
				  {if $settings.Company.company_zipcode}{$settings.Company.company_zipcode}, {/if}
				  {if $settings.Company.company_city}{$settings.Company.company_city}, {/if}
				  {if $settings.Company.company_state && $settings.Company.company_country}{$settings.Company.company_state|fn_get_state_name:$settings.Company.company_country|escape}, {/if}
				  {$settings.Company.company_country|fn_get_country_name|escape}<br />
<span style="margin-left:20px;">&nbsp;</span>{if $settings.Company.company_phone}{__('phone')}:&nbsp;{$settings.Company.company_phone}{if $settings.Company.company_fax}, {/if}{/if}{if $settings.Company.company_fax}{__('fax')}:&nbsp;{$settings.Company.company_fax}{/if}.<br />
<span style="margin-left:20px;">&nbsp;</span>{__('email')}:&nbsp;{$settings.Company.company_orders_department}
