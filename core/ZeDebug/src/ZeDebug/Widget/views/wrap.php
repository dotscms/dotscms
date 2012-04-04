<div id="zedebug">
    <ul class="menu">
    <?php foreach($this->getWidgets() as $key =>$widget):?>
        <li><a href="#"><?php echo $widget->getName();?></a></li>
    <?php endforeach;?>
    </ul>
    <div class="widgets">
        <?php foreach($this->getWidgets() as $key =>$widget):?>
            <div class="widget widget_<?php echo $key ?>"><?php echo $widget->render();?></div>
        <?php endforeach;?>
    </div>
    <style type="text/css">
        #zedebug .menu{list-style: none; padding:3px; margin:0; border:solid 1px #CCC;overflow: hidden;}
        #zedebug .menu li{float:left;padding:0 2px;}
    </style>
</div>