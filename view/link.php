<div <?php if (!isset($shortLink)) echo 'hidden'?>>
    <p>Ваша ссылка: <?php if (isset($shortLink)) echo "<a href ='$shortLink'>$shortLink</a>"?></p>
</div>