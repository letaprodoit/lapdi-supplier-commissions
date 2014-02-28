{if $fields}
<div id="{$target_name}" class="in collapse">
    <fieldset>        
        {foreach from=$fields item="field"}
        <div class="control-group">
            {if !$field.readonly}
                <label class="control-label" for="{if $field_id_prefix}{$field_id_prefix}{/if}{$type}_{$field.name}">{$field.title}:</label>
                <div class="controls form-field {if $field.type == 'D'}clearfix{/if}">
                    {if $field.type == 'I' || $field.type == 'U'}
                        <input type="hidden" name="{$array_name}[{$field.name}]" value="" />
                        <input id="{if $field_id_prefix}{$field_id_prefix}{/if}{$type}_{$field.name}" type="text" name="{$array_name}[{$field.name}]" {if $field.width}style="width: {$field.width};"{/if} value="{$field.value}" class="valign input-text {if $field.hint}cm-hint{/if}" title="{$field.hint}"/>
                    {elseif $field.type == 'S'}
                        <select id="{if $field_id_prefix}{$field_id_prefix}{/if}{$type}_{$field.name}" name="{$array_name}[{$field.name}]">
                        <option value="">{__("please_select_one")}</option>
                            {foreach from=$field.options item="option"}
                                <option value="{$option}" {if $field.value == $option}selected="selected"{/if}>{$option}</option>
                            {/foreach}
                        </select>
                    {elseif $field.type == 'H'}
                        <select id="{if $field_id_prefix}{$field_id_prefix}{/if}{$type}_{$field.name}" name="{$array_name}[{$field.name}]">
                        <option value="">{__("please_select_one")}</option>
                            {foreach from=$field.options key=k item=v name="option"}
                                <option value="{$k}" {if $field.value == $k}selected="selected"{/if}>{$v}</option>
                            {/foreach}
                        </select>
                    {elseif $field.type == 'T'}
                        <textarea id="{if $field_id_prefix}{$field_id_prefix}{/if}{$type}_{$field.name}" name="{$array_name}[{$field.name}]" class="input-textarea-long" style="height: 90px;">{$field.value|unescape}</textarea>
                    {elseif $field.type == 'C'}
                        <input type="hidden" name="{$array_name}[{$field.name}]" value="" />
                        <input type="checkbox" name="{$array_name}[{$field.name}]" id="{if $field_id_prefix}{$field_id_prefix}{/if}{$type}_{$field.name}" value="Y" {if $field.value == "Y"}checked="checked"{/if} class="checkbox" />
                    {elseif $field.type == 'D'}
                        {include file="common/calendar.tpl" date_id="{if $field_id_prefix}{$field_id_prefix}{/if}{$type}_`$field.name`" date_name="{$array_name}[`$field.name`]" start_year=$settings.Company.company_start_year date_val=$field.value}
                    {/if}
                </div>
            {else}
                {if $field.type == 'U'}
                    {assign var="url" value=$field.value}
                    {if !$url}
                        {assign var="url" value="#"}
                    {/if}
                    
                    {if $field.icon}
                        <a href="{$url|unescape}" target="_blank"><div class="controls {$field.class} field_type_{$field.type}" style="cursor: pointer;">{$field.label}</div></a>                        
                    {else}
                        <label class="control-label" for="{if $field_id_prefix}{$field_id_prefix}{/if}{$type}_{$field.name}">{$field.title}:</label>
                        <div class="controls form-field {if $field.type == 'D'}clearfix{/if} product-list-field">
                            <span class="{$field.class} field_type_{$field.type}"><a href="{$url|unescape}">{$field.title|unescape}</a></span>
                        </div>
                    {/if}
                {else}
                    <label class="control-label" for="{if $field_id_prefix}{$field_id_prefix}{/if}{$type}_{$field.name}">{$field.title}:</label>
                    <div class="controls form-field {if $field.type == 'D'}clearfix{/if} product-list-field">
                        <span class="{$field.class} field_type_{$field.type}">{$field.value|unescape}</span>
                    </div>
                {/if}
            {/if}       
        </div>
        {/foreach}
    </fieldset>
</div>
{/if}