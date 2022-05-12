<?php
    $url = $_GET['parse_url'];
    $c = curl_init($url);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
    curl_setopt ($c, CURLOPT_SSL_VERIFYPEER, false);

    $html = curl_exec($c);

    if (curl_error($c))
        die(curl_error($c));

    curl_close($c);

    $dom = new domDocument;
    $dom->loadHTML($html);
    $dom->preserveWhiteSpace = false;

    $images = $dom->getElementsByTagName('img');
    $images_array = array();
    $images_size = 0;

    foreach ($images as $image) {
        $images_array[] = $image->getAttribute('src');
        $image = get_headers($image->getAttribute('src'), 1);
        $images_size += (int)$image["Content-Length"] / (1024 * 1024);
    }

    $images_array = array_chunk($images_array, 4);
?>

<center>
    <table>
        <?php foreach ($images_array as $image_arr) { ?>
            <tr>
                <?php foreach ($image_arr as $image) { ?>
                    <td><img src="<?= $image ?>" width='100' height='100'></td>
                <?php } ?>
            </tr>
        <?php } ?>
    </table>

    <h2>На странице обнаружено <?= $images->count() ?> изображений на <?= $images_size ?> Мб</h2>
</center>