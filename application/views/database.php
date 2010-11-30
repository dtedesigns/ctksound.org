<?php
/* vim: set noet fenc= ff=unix sts=0 sw=4 ts=4 : */
/* SVN FILE: $Id: php 135 2009-06-10 02:20:13Z kevin $ */

$spl_hymns = json_decode($sermon['hymns_spl'],TRUE);
$spl_sermon = json_decode($sermon['sermon_spl'],TRUE);
$spl_ace = json_decode($ace['spl'],TRUE);

?>
<style type='text/css'>
.hidden {
	display: none;
}
#sermon {
	display: block;
}
</style>

<ul id="nav">
	<li class="selected" onclick="$('.hidden').hide();$('#sermon').show();"><a href='javascript:;'>Sermon</a></li>
	<li onclick="$('.hidden').hide();$('#ace').show();"><a href='javascript:;'>A.C.E.</a></li>
	<li onclick="$('.hidden').hide();$('#portrait').show();"><a href='javascript:;'>Portrait</a></li>
	<li onclick="$('.hidden').hide();$('#dedication').show();"><a href='javascript:;'>Dedication</a></li>
	<!--
	<li><a href='javascript:;' onclick="$('.hidden').hide();$('#baptism').show();">Baptism</a></li>
	<li><a href='javascript:;' onclick="$('.hidden').hide();$('#good_friday').show();">Good Friday</a></li>
	<li><a href='javascript:;' onclick="$('.hidden').hide();$('#christmas').show();">Christmas</a></li>
	-->
</ul>

<div id="sermon" class='hidden'>
	<form id='enter_sermon' action='<?= url::base() ?>dash/write_record/' method='post'><fieldset>
		<legend>Enter Sermon Information</legend>

		<input type='hidden' name='type' value='Sermons' />

		<p>
		<label for='date'>Date</label>
		<input type='text' name='date' id='date' value='<?= $sunday ?>' />
		</p>

		<p>
			<label for='scripture'>Scripture</label>
			<input
					type='text'
					name='scripture'
					id='sermon_scripture'
					<?php if($sermon) echo "value=\"".htmlspecialchars($sermon['scripture'])."\""; ?>
			/>
		</p>

		<p>
			<label for='reader'>Reader</label>
			<input
					type='text'
					name='reader'
					id='sermon_reader'
					<?php if($sermon) echo "value=\"".htmlspecialchars($sermon['reader'])."\""; ?>
			/>
		</p>

		<p>
			<label for='series'>Series</label>
			<input
					type='text'
					name='series'
					id='sermon_series'
					<?php if($sermon) echo "value=\"".htmlspecialchars($sermon['series'])."\""; ?>
			/>
		</p>

		<p>
			<label for='title'>Title</label>
			<input
					type='text'
					name='title'
					id='sermon_title'
					<?php if($sermon) echo "value=\"".htmlspecialchars($sermon['title'])."\""; ?>
			/>
		</p>

		<p>
			<label for='preacher'>Preacher</label>
			<input
					type='text'
					name='preacher'
					id='sermon_preacher'
					<?php if($sermon) echo "value=\"".htmlspecialchars($sermon['preacher'])."\""; ?>
			/>
		</p>

		<p>
			<label for='hymns_value1'>Hymns SPL</label>
			<input
					type='text'
					name='hymns_value1'
					class='spl_value'
					<?php if($sermon) echo "value=\"".htmlspecialchars($spl_hymns[0]['value'])."\""; ?>
			/>
			<select id="hymns_type1" name="hymns_type1" class="spl_type">
				<option value="average">Average (dB)</option>
				<option value="min">Min (dB)</option>
				<option value="max">Max (dB)</option>
			</select>
			<script type="text/javascript">$("#hymns_type1 option[value='<?= $spl_hymns[0]['type'] ?>']").attr("selected","selected");</script>
			<br />
			<label>&nbsp;</label>
			<input
					type='text'
					name='hymns_value2'
					class='spl_value spl_second'
					<?php if($sermon) echo "value=\"".htmlspecialchars($spl_hymns[1]['value'])."\""; ?>
			/>
			<select id="hymns_type2" name="hymns_type2" class="spl_type">
				<option value="average">Average (dB)</option>
				<option value="min">Min (dB)</option>
				<option value="max">Max (dB)</option>
			</select>
			<script type="text/javascript">$("#hymns_type2 option[value='<?= $spl_hymns[1]['type'] ?>']").attr("selected","selected");</script>
		</p>

		<p>
			<label for='sermon_spl'>Sermon SPL</label>
			<input
					type='text'
					name='sermon_value1'
					class='spl_value'
					<?php if($sermon) echo "value=\"".htmlspecialchars($spl_sermon[0]['value'])."\""; ?>
			/>
			<select id="sermon_type1" name="sermon_type1" class="spl_type">
				<option value="average">Average (dB)</option>
				<option value="min">Min (dB)</option>
				<option value="max">Max (dB)</option>
			</select>
			<script type="text/javascript">$("#sermon_type1 option[value='<?= $spl_sermon[0]['type'] ?>']").attr("selected","selected");</script>
			<br />
			<label>&nbsp;</label>
			<input
					type='text'
					name='sermon_value2'
					class='spl_value spl_second'
					<?php if($sermon) echo "value=\"".htmlspecialchars($spl_sermon[1]['value'])."\""; ?>
			/>
			<select id="sermon_type2" name="sermon_type2" class="sermon_type">
				<option value="average">Average (dB)</option>
				<option value="min">Min (dB)</option>
				<option value="max">Max (dB)</option>
			</select>
			<script type="text/javascript">$("#sermon_type2 option[value='<?= $spl_sermon[1]['type'] ?>']").attr("selected","selected");</script>
		</p>

		<p>
			<label for='engineer'>Tech</label>
			<input
					type='text'
					name='engineer'
					id='sermon_engineer'
					<?php if($sermon) echo "value=\"".htmlspecialchars($sermon['engineer'])."\""; ?>
			/>
		</p>

		<p>
			<label for='processor'>Processor</label>
			<input
					type='text'
					name='processor'
					id='sermon_processor'
					<?php if($sermon) echo "value=\"".htmlspecialchars($sermon['processor'])."\""; ?>
			/>
		</p>

		<p>
			<label for="notes">Notes</label>
			<textarea name="notes" id="sermon_notes" rows="4" cols="39" ><?php if($sermon) echo htmlspecialchars($sermon['notes']); ?></textarea>
		</p>

		<input type='submit' value='submit' /> &nbsp; <span id='sermon_message' />

	</fieldset></form>
</div>

<div id="ace" class='hidden'>
	<form id='enter_ace' action='<?= url::base() ?>dash/write_record/' method='post'><fieldset>
		<legend>Enter A.C.E. Information</legend>
		<!-- Aces: series, title, teacher, comment, date, disk -->

		<input type='hidden' name='type' value='Aces' />

		<p>
			<label for='date'>Date</label>
			<input type='text' name='date' id='date' value='<?= $sunday ?>' />
		</p>

		<p>
			<label for='series'>Series</label>
			<input type='text' name='series' id='ace_series' <?php if($ace) echo "value=\"".htmlspecialchars($ace['series'])."\""; ?>/>
		</p>

		<p>
			<label for='title'>Title</label>
			<input type='text' name='title' id='ace_title' <?php if($ace) echo "value=\"".htmlspecialchars($ace['title'])."\""; ?>/>
		</p>

		<p>
			<label for='teacher'>Teacher</label>
			<input type='text' name='teacher' id='ace_teacher' <?php if($ace) echo "value=\"".htmlspecialchars($ace['teacher'])."\""; ?>/>
		</p>

		<p>
			<label for='comment'>Comment</label>
			<input type='text' name='comment' id='ace_comment' <?php if($ace) echo "value=\"".htmlspecialchars($ace['comment'])."\""; ?>/>
		</p>

		<p>
			<label for='sermon_spl'>A.C.E. SPL</label>
			<input
					type='text'
					name='spl_value1'
					class='spl_value'
					<?php if($sermon) echo "value=\"".htmlspecialchars($spl_ace[0]['value'])."\""; ?>
			/>
			<select id="sermon_type1" name="spl_type1" class="spl_type">
				<option value="average">Average (dB)</option>
				<option value="min">Min (dB)</option>
				<option value="max">Max (dB)</option>
			</select>
			<script type="text/javascript">$("#sermon_type1 option[value='<?= $spl_ace[0]['type'] ?>']").attr("selected","selected");</script>
			<br />
			<label>&nbsp;</label>
			<input
					type='text'
					name='spl_value2'
					class='spl_value spl_second'
					<?php if($sermon) echo "value=\"".htmlspecialchars($spl_ace[1]['value'])."\""; ?>
			/>
			<select id="spl_type2" name="spl_type2" class="sermon_type">
				<option value="average">Average (dB)</option>
				<option value="min">Min (dB)</option>
				<option value="max">Max (dB)</option>
			</select>
			<script type="text/javascript">$("#spl_type2 option[value='<?= $spl_ace[1]['type'] ?>']").attr("selected","selected");</script>
		</p>

		<input type='submit' value='submit' /> &nbsp; <span id='ace_message' />
	</fieldset></form>
</div>

<div id="portrait" class='hidden'>
	<form id='enter_portrait' action='<?= url::base() ?>dash/write_record/' method='post'><fieldset>
		<legend>Enter Portrait of Grace Information</legend>

		<input type='hidden' name='type' value='Portraits' />

		<p>
		<label for='date'>Date</label>
		<input type='text' name='date' id='date' value='<?= $sunday ?>' />
		</p>

		<p>
			<label for='preacher'>Speaker</label>
			<input type='text' name='speaker' id='portrait_speaker' <?php if($portrait) echo "value='".htmlspecialchars($portrait['speaker'])."'"; ?>/>
		</p>

		<p>
			<label for='scripture'>Comment</label>
			<input type='text' name='comment' id='portrait_comment' <?php if($portrait) echo "value='".htmlspecialchars($portrait['comment'])."'"; ?>/>
		</p>

		<input type='submit' value='submit' /> &nbsp; <span id='portrait_message' />
	</fieldset></form>
</div>

<div id="dedication" class='hidden'>
	<form id='enter_dedication' action='<?= url::base() ?>dash/write_record/' method='post'><fieldset>
		<legend>Enter Child Dedication Information</legend>

		<input type='hidden' name='type' value='Dedications' />

		<p>
		<label for='date'>Date</label>
		<input type='text' name='date' id='date' value='<?= $sunday ?>' />
		</p>

		<p>
			<label for='official'>Official</label>
			<input type='text' name='official' id='dedication_official' <?php if($dedication) echo "value=\"".htmlspecialchars($dedication['official'])."\""; ?>/>
		</p>

		<p>
			<label for='child'>Child</label>
			<input type='text' name='child' id='dedication_child' <?php if($dedication) echo "value=\"".htmlspecialchars($dedication['child'])."\""; ?>/>
		</p>

		<p>
			<label for='comment'>Comment</label>
			<input type='text' name='comment' id='dedication_comment' <?php if($dedication) echo "value=\"".htmlspecialchars($dedication['comment'])."\""; ?>/>
		</p>

		<input type='submit' value='submit' /> &nbsp; <span id='dedication_message' />
	</fieldset></form>
</div>

<div id="baptism" class='hidden'>
	<form id='enter_baptism' action='/sound/write.php' method='post'><fieldset>
		<legend>Enter Baptism Information</legend>

		<input type='hidden' name='type' value='Baptisms' />
		<input type='submit' value='submit' /> &nbsp; <span id='baptism_message' />
	</fieldset></form>
</div>

<div id="good_friday" class='hidden'>
	<form id='enter_good_friday' action='/sound/write.php' method='post'><fieldset>
		<legend>Enter Good Friday Information</legend>

		<input type='hidden' name='type' value='Good_Fridays' />
		<input type='submit' value='submit' /> &nbsp; <span id='good_friday_message' />
	</fieldset></form>
</div>

<div id="christmas" class='hidden'>
	<form id='enter_christmas' action='/sound/write.php' method='post'><fieldset>
		<legend>Enter Christmas (Eve) Information</legend>

		<input type='hidden' name='type' value='Christmases' />
		<input type='submit' value='submit' /> &nbsp; <span id='christmas_message' />
	</fieldset></form>
</div>

<script type='text/javascript'>
	$('#nav li').click(function() {
		$('#nav li').removeClass('selected');
		$(this).addClass('selected');
	});

$('#enter_sermon').ajaxForm({
	target: $('#sermon_message'),
	success: function(data) {
		$('#sermon_message').fadeOut(5000);
	}
});
$('#enter_ace').ajaxForm({
	target: $('#ace_message'),
	success: function(data) {
		$('#ace_message').fadeOut(5000);
	}
});
$('#enter_portrait').ajaxForm({
	target: $('#portrait_message'),
	success: function(data) {
		$('#portrait_message').fadeOut(5000);
	}
});
$('#enter_dedication').ajaxForm({
	target: $('#dedication_message'),
	success: function(data) {
		$('#dedication_message').fadeOut(5000);
	}
});
$('#enter_baptism').ajaxForm({
	target: $('#baptism_message'),
	success: function(data) {
		$('#baptism_message').fadeOut(5000);
	}
});
$('#enter_good_friday').ajaxForm({
	target: $('#good_friday_message'),
	success: function(data) {
		$('#good_friday_message').fadeOut(5000);
	}
});
$('#enter_christmas').ajaxForm({
	target: $('#christmas_message'),
	success: function(data) {
		$('#christmas_message').fadeOut(5000);
	}
});

$('#sermon_preacher').autocomplete("<?= url::base() ?>dash/autocomplete?type=Sermons&val=preacher",{
	cacheLength:10, autoFill:true, selectFirst:true
});
$('#sermon_title').autocomplete("<?= url::base() ?>dash/autocomplete?type=Sermons&val=title",{
	cacheLength:10, autoFill:true, selectFirst:true
});
$('#sermon_scripture').autocomplete("<?= url::base() ?>dash/autocomplete?type=Sermons&val=book",{
	cacheLength:10, autoFill:true, selectFirst:true
});
$('#sermon_reader').autocomplete("<?= url::base() ?>dash/autocomplete?type=Sermons&val=reader",{
	cacheLength:10, autoFill:true, selectFirst:true
});
$('#sermon_series').autocomplete("<?= url::base() ?>dash/autocomplete?type=Sermons&val=series",{
	cacheLength:10, autoFill:true, selectFirst:true
});

$('#sermon_engineer').autocomplete("<?= url::base() ?>dash/autocomplete?type=Sermons&val=engineer",{
	cacheLength:10, autoFill:true, selectFirst:true
});

$('#sermon_processor').autocomplete("<?= url::base() ?>dash/autocomplete?type=Sermons&val=processor",{
	cacheLength:10, autoFill:true, selectFirst:true
});

$('#ace_teacher').autocomplete("<?= url::base() ?>dash/autocomplete?type=Aces&val=teacher",{
	cacheLength:10, autoFill:true, selectFirst:true
});
$('#ace_title').autocomplete("<?= url::base() ?>dash/autocomplete?type=Aces&val=title",{
	cacheLength:10, autoFill:true, selectFirst:true
});
$('#ace_scripture').autocomplete("<?= url::base() ?>dash/autocomplete?type=Aces&val=book",{
	cacheLength:10, autoFill:true, selectFirst:true
});
$('#ace_series').autocomplete("<?= url::base() ?>dash/autocomplete?type=Aces&val=series",{
	cacheLength:10, autoFill:true, selectFirst:true
});

// initial value
$('input[type=submit]').attr('disabled','disabled');

// unsaved value
$('input,textarea,select').change(function() {
	$('input[type=submit]').removeAttr('disabled');
});

// after saving, disable submit
//		save with Ajax use the return value to verify saving
//		throw error if failure
$('form').submit(function() {
	$('input[type=submit]').attr('disabled','disabled');
});

</script>
