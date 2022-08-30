
<div style="background-color: rgba(0,0,0,.1); border: 1px solid var(--main-bg-color); padding: 5px;">
    <a class="bb_btn" type="submit" onclick="wrapText('txtarea','[b]','[/b]');"><b>B</b></a>
    <a class="bb_btn" type="submit" onclick="wrapText('txtarea','[i]','[/i]');"><i>i</i></a>
    <a class="bb_btn" type="submit" onclick="wrapText('txtarea','[u]','[/u]');"><u>u</u></a>
    <a class="bb_btn" type="submit" onclick="wrapText('txtarea','[center]','[/center]');">center</a>
    <a class="bb_btn" type="submit" onclick="wrapText('txtarea','[img]','[/img]');">img</a>
    <a class="bb_btn" type="submit" onclick="wrapText('txtarea','[color=#?]','[/color]');">color</a>
    <a class="bb_btn" type="submit" onclick="wrapText('txtarea','[size=?]','[/size]');">size</a>
    <a class="bb_btn" type="submit" onclick="wrapText('txtarea','[youtube]','[/youtube]');">youtube</a>
    <a class="bb_btn" type="submit" onclick="wrapText('txtarea','[spotify]','[/spotify]');">Spotify</a>
</div>

<script>

$('.showMe').click(function(){
   $('.clickMeToShow').toggle();
});

</script>