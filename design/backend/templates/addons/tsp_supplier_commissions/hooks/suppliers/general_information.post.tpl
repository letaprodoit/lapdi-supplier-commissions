{include file="common/subheader.tpl" title=__("tspsc_commission_info") target="#tsp_supplier_commissions_fields"}
{include file="addons/tsp_supplier_commissions/views/supplier_commissions/components/update_fields.tpl" target_name="tsp_supplier_commissions_fields" type="supplier" field_id_prefix="elm_" array_name="supplier_data" record=$supplier_data fields=$tspsc_profile_addon_fields}