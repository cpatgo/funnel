<?php
foreach ($sites as $site) {
    $content = str_replace(array('css/', 'images/', 'js/'), array('../elements/css/', '../elements/images/', '../elements/js/'), $site['lastFrame']->frames_content);
    echo $content;



}

?>

<ul id="pages">
    <li style="display: none;" id="newPageLI">
        <input type="text" value="index" name="page">
        <span class="pageButtons">
            <a href="" class="fileEdit"><span class="fui-new"></span></a>
            <a href="" class="fileDel"><span class="fui-cross"></span></a>
            <a class="btn btn-xs btn-primary btn-embossed fileSave" href="#"><span class="fui-check"></span></a>
        </span>
    </li>
    
</ul>