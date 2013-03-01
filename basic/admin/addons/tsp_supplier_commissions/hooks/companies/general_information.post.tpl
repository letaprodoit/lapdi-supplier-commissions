{if $profile_fields.$supplier_section}

{if !$nothing_extra}
	{include file="common_templates/subheader.tpl" title=$lang.tspsc_commission_info}
{/if}

{foreach from=$profile_fields.$supplier_section item=field}
{if $field.field_name}
	{assign var="data_name" value="company_data"}
	{assign var="data_id" value=$field.field_name}
	{assign var="value" value=$company_data.$data_id}
{else}
	{assign var="data_name" value="company_data[fields]"}
	{assign var="data_id" value=$field.field_id}
	{assign var="value" value=$company_data.fields.$data_id}
{/if}

<div class="form-field">
	<label for="{$id_prefix}elm_{$field.field_id}" class="cm-profile-field {if $field.required == "Y"}cm-required{/if}{if $field.field_type == "P"} cm-phone{/if}{if $field.field_type == "Z"} cm-zipcode{/if}{if $field.field_type == "E"} cm-email{/if}{if $field.field_type == "A"} cm-state{/if}{if $field.field_type == "O"} cm-country{/if} {if $field.field_type == "O" || $field.field_type == "A" || $field.field_type == "Z"}{/if}">{$field.description}:</label>

	{if $field.field_type == "L"} {* Titles selectbox *}
		<select id="{$id_prefix}elm_{$field.field_id}" name="{$data_name}[{$data_id}]" {$disabled_param}>
			{foreach from=$titles item="t"}
			<option {if $value == $t.param}selected="selected"{/if} value="{$t.param}">{$t.descr}</option>
			{/foreach}
		</select>

	{elseif $field.field_type == "A"}  {* State selectbox *}
		<select id="{$id_prefix}elm_{$field.field_id}" name="{$data_name}[{$data_id}]" {$disabled_param}>
			<option value="">- {$lang.select_state} -</option>
			{* Initializing default states *}
			{assign var="country_code" value=$settings.General.default_country}
			{assign var="state_code" value=$value|default:$settings.General.default_state}
			{if $states}
				{foreach from=$states.$country_code item=state}
					<option {if $state_code == $state.code}selected="selected"{/if} value="{$state.code}">{$state.state}</option>
				{/foreach}
			{/if}
		</select><input type="text" id="elm_{$field.field_id}_d" name="{$data_name}[{$data_id}]" size="32" maxlength="64" value="{$value}" disabled="disabled" class="input-text hidden cm-skip-avail-switch" />
		<input type="hidden" id="elm_{$field.field_id}_default" value="{$state_code}" />
	
	{elseif $field.field_type == "O"}  {* Countries selectbox *}
		{assign var="_country" value=$value|default:$settings.General.default_country}
		<select id="{$id_prefix}elm_{$field.field_id}" class="{if $supplier_section == "S"}cm-location-shipping{else}cm-location-billing{/if}" name="{$data_name}[{$data_id}]" {$disabled_param}>
			{hook name="profiles:country_selectbox_items"}
			<option value="">- {$lang.select_country} -</option>
			{foreach from=$countries item=country}
			<option {if $_country == $country.code}selected="selected"{/if} value="{$country.code}">{$country.country}</option>
			{/foreach}
			{/hook}
		</select>
	
	{elseif $field.field_type == "C"}  {* Checkbox *}
		<input type="hidden" name="{$data_name}[{$data_id}]" value="N" {$disabled_param} />
		<input type="checkbox" id="{$id_prefix}elm_{$field.field_id}" name="{$data_name}[{$data_id}]" value="Y" {if $value == "Y"}checked="checked"{/if} class="checkbox" {$disabled_param} />

	{elseif $field.field_type == "T"}  {* Textarea *}
		<textarea class="input-textarea" id="{$id_prefix}elm_{$field.field_id}" name="{$data_name}[{$data_id}]" cols="32" rows="3" {$disabled_param}>{$value}</textarea>
	
	{elseif $field.field_type == "D"}  {* Date *}
		{include file="common_templates/calendar.tpl" date_id="elm_`$field.field_id`" date_name="`$data_name`[`$data_id`]" date_val=$value start_year="1902" end_year="0" extra=$disabled_param}

	{elseif $field.field_type == "S"}  {* Selectbox *}
		<select id="{$id_prefix}elm_{$field.field_id}" name="{$data_name}[{$data_id}]" {$disabled_param}>
			{if $field.required != "Y"}
			<option value="">--</option>
			{/if}
			{foreach from=$field.values key=k item=v}
			<option {if $value == $k}selected="selected"{/if} value="{$k}">{$v}</option>
			{/foreach}
		</select>
	
	{elseif $field.field_type == "R"}  {* Radiogroup *}
		<div class="select-field">
		{foreach from=$field.values key=k item=v name="rfe"}
		<input class="radio" type="radio" id="{$id_prefix}elm_{$field.field_id}_{$k}" name="{$data_name}[{$data_id}]" value="{$k}" {if (!$value && $smarty.foreach.rfe.first) || $value == $k}checked="checked"{/if} {$disabled_param} /><label for="{$id_prefix}elm_{$field.field_id}_{$k}">{$v}</label>
		{/foreach}
		</div>

	{elseif $field.field_type == "N"}  {* Address type *}
		<input class="radio valign {if !$skip_field}{$_class}{else}cm-skip-avail-switch{/if}" type="radio" id="{$id_prefix}elm_{$field.field_id}_residential" name="{$data_name}[{$data_id}]" value="residential" {if !$value || $value == "residential"}checked="checked"{/if} {if !$skip_field}{$disabled_param}{/if} /><span class="radio">{$lang.address_residential}</span>
		<input class="radio valign {if !$skip_field}{$_class}{else}cm-skip-avail-switch{/if}" type="radio" id="{$id_prefix}elm_{$field.field_id}_commercial" name="{$data_name}[{$data_id}]" value="commercial" {if $value == "commercial"}checked="checked"{/if} {if !$skip_field}{$disabled_param}{/if} /><span class="radio">{$lang.address_commercial}</span>

	{else}  {* Simple input *}
		<input type="text" id="{$id_prefix}elm_{$field.field_id}" name="{$data_name}[{$data_id}]" size="32" value="{$value}" class="input-text" {$disabled_param} />
	{/if}
</div>
{/foreach}
{if $body_id}
</div>
{/if}

{/if}