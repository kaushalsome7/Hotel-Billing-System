@php $adj = $adjustments[$prefix] ?? null; @endphp
<div style="display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-bottom:4px;">
    <div class="form-group" style="margin-bottom:8px;">
        <label style="font-size:0.68rem;">Type</label>
        <select name="{{ $prefix }}_adj_type" class="form-control" style="font-size:0.8rem;padding:7px 10px;">
            <option value="none" {{ (!$adj || $adj['mode']==='none') ? 'selected' : '' }}>None</option>
            <option value="discount_percent" {{ ($adj && $adj['mode']==='discount' && $adj['type']==='percent') ? 'selected' : '' }}>Discount %</option>
            <option value="discount_flat"    {{ ($adj && $adj['mode']==='discount' && $adj['type']==='flat')    ? 'selected' : '' }}>Discount ₹</option>
            <option value="charge_percent"   {{ ($adj && $adj['mode']==='charge'   && $adj['type']==='percent') ? 'selected' : '' }}>Extra Charge %</option>
            <option value="charge_flat"      {{ ($adj && $adj['mode']==='charge'   && $adj['type']==='flat')    ? 'selected' : '' }}>Extra Charge ₹</option>
        </select>
    </div>
    <div class="form-group" style="margin-bottom:8px;">
        <label style="font-size:0.68rem;">Value</label>
        <input type="number" name="{{ $prefix }}_adj_value"
               class="form-control" style="font-size:0.8rem;padding:7px 10px;"
               value="{{ $adj['value'] ?? '' }}"
               min="0" step="0.01" placeholder="0">
    </div>
</div>
