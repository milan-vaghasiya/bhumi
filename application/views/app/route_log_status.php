<form>
    <div class="form-item">
        <input type="hidden" name="id" value="<?=$id?>">
        <input type="hidden" name="status" value="<?=$status?>">
        <input type="hidden" name="route_id" value="<?=$route_id?>">
        <label class="form-label"><?=($status == 1)?'Reason':'Notes'?></label>
        <textarea id="notes" name="notes" class="form-control req"></textarea>
    </div>
</form>