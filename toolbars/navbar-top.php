<?php
    $active = ' class="active"';
    $chcked = '<span class="glyphicon glyphicon-ok"></span> ';

    $deepPath = str_repeat("../", $packages[$package]["level"] + ($action ? 1 : 0));
?>
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <ul class="nav navbar-nav">
                <li<?php echo ($packages["home"]["name"] == $package ? $active : '' ) ?>><a class="navbar-brand" href="<?php echo $deepPath?>"><span class="glyphicon glyphicon-home"></span></a></li>
            </ul>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li<?php echo ($packages["boats"]["name"] == $package ? $active : '' ) ?>><a href="<?php echo $deepPath.$packages["boats"]["parent"].$packages["boats"]["path"] ?>"><?php echo $packages["boats"]["title"] ?></a></li>
                <li<?php echo ($packages["owners"]["name"] == $package ? $active : '' ) ?>><a href="<?php echo $deepPath.$packages["owners"]["parent"].$packages["owners"]["path"] ?>"><?php echo $packages["owners"]["title"] ?></a></li>
                <li<?php echo ($packages["engines"]["name"] == $package ? $active : '' ) ?>><a href="<?php echo $deepPath.$packages["engines"]["parent"].$packages["engines"]["path"] ?>"><?php echo $packages["engines"]["title"] ?></a></li>
                <li class="dropdown <?php echo ( preg_match('/admin/',$packages[$package]["parent"]) || $packages[$package]["name"] == "admin" ? 'active' : '' ) ?>">
                    <a href="<?php echo $deepPath ?>admin/" class="dropdown-toggle" data-toggle="dropdown">Διαχείριση<span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="<?php echo $deepPath.$packages["admin"]["parent"].$packages["admin"]["path"] ?>"><?php echo ($packages["admin"]["name"] == $package ? $chcked : '' ) ?><?php echo $packages["admin"]["title"]?></a></li>
                        <li class="divider"></li>
                        <li><a href="<?php echo $deepPath.$packages["boat-ports"]["parent"].$packages["boat-ports"]["path"] ?>"><?php echo ($packages["boat-ports"]["name"] == $package ? $chcked : '' ) ?><?php echo $packages["boat-ports"]["title"]?></a></li>
                        <li><a href="<?php echo $deepPath.$packages["registry-types"]["parent"].$packages["registry-types"]["path"] ?>"><?php echo ($packages["registry-types"]["name"] == $package ? $chcked : '' ) ?><?php echo $packages["registry-types"]["title"]?></a></li>
                        <li><a href="<?php echo $deepPath.$packages["boat-kinds"]["parent"].$packages["boat-kinds"]["path"] ?>"><?php echo ($packages["boat-kinds"]["name"] == $package ? $chcked : '' ) ?><?php echo $packages["boat-kinds"]["title"]?></a></li>
                        <li><a href="<?php echo $deepPath.$packages["boat-types"]["parent"].$packages["boat-types"]["path"] ?>"><?php echo ($packages["boat-types"]["name"] == $package ? $chcked : '' ) ?><?php echo $packages["boat-types"]["title"]?></a></li>
                        <li><a href="<?php echo $deepPath.$packages["boat-materials"]["parent"].$packages["boat-materials"]["path"] ?>"><?php echo ($packages["boat-materials"]["name"] == $package ? $chcked : '' ) ?><?php echo $packages["boat-materials"]["title"]?></a></li>
                        <li><a href="<?php echo $deepPath.$packages["boat-colors"]["parent"].$packages["boat-colors"]["path"] ?>"><?php echo ($packages["boat-colors"]["name"] == $package ? $chcked : '' ) ?><?php echo $packages["boat-colors"]["title"]?></a></li>
                        <li><a href="<?php echo $deepPath.$packages["boat-status"]["parent"].$packages["boat-status"]["path"] ?>"><?php echo ($packages["boat-status"]["name"] == $package ? $chcked : '' ) ?><?php echo $packages["boat-status"]["title"]?></a></li>
                        <li><a href="<?php echo $deepPath.$packages["amyen-types"]["parent"].$packages["amyen-types"]["path"] ?>"><?php echo ($packages["amyen-types"]["name"] == $package ? $chcked : '' ) ?><?php echo $packages["amyen-types"]["title"]?></a></li>
                        <li class="divider"></li>
                        <li><a href="<?php echo $deepPath.$packages["engine-power-types"]["parent"].$packages["engine-power-types"]["path"] ?>"><?php echo ($packages["engine-power-types"]["name"] == $package ? $chcked : '' ) ?><?php echo $packages["engine-power-types"]["title"]?></a></li>
                        <li><a href="<?php echo $deepPath.$packages["engine-types"]["parent"].$packages["engine-types"]["path"] ?>"><?php echo ($packages["engine-types"]["name"] == $package ? $chcked : '' ) ?><?php echo $packages["engine-types"]["title"]?></a></li>
                        <li><a href="<?php echo $deepPath.$packages["movement-types"]["parent"].$packages["movement-types"]["path"] ?>"><?php echo ($packages["movement-types"]["name"] == $package ? $chcked : '' ) ?><?php echo $packages["movement-types"]["title"]?></a></li>
                        <li><a href="<?php echo $deepPath.$packages["engine-kinds"]["parent"].$packages["engine-kinds"]["path"] ?>"><?php echo ($packages["engine-kinds"]["name"] == $package ? $chcked : '' ) ?><?php echo $packages["engine-kinds"]["title"]?></a></li>
                        <li><a href="<?php echo $deepPath.$packages["engine-status"]["parent"].$packages["engine-status"]["path"] ?>"><?php echo ($packages["engine-status"]["name"] == $package ? $chcked : '' ) ?><?php echo $packages["engine-status"]["title"]?></a></li>
                        <li><a href="<?php echo $deepPath.$packages["engine-brands"]["parent"].$packages["engine-brands"]["path"] ?>"><?php echo ($packages["engine-brands"]["name"] == $package ? $chcked : '' ) ?><?php echo $packages["engine-brands"]["title"]?></a></li>
                        <li class="divider"></li>
                        <li><a href="<?php echo $deepPath.$packages["owner-status"]["parent"].$packages["owner-status"]["path"] ?>"><?php echo ($packages["owner-status"]["name"] == $package ? $chcked : '' ) ?><?php echo $packages["owner-status"]["title"]?></a></li>
                        <li class="divider"></li>
                        <li><a href="<?php echo $deepPath.$packages["page-records"]["parent"].$packages["page-records"]["path"] ?>"><?php echo ($packages["page-records"]["name"] == $package ? $chcked : '' ) ?><?php echo $packages["page-records"]["title"]?></a></li>
                    </ul>
                </li>
                <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
                <li<?php echo ($packages["options"]["name"] == $package ? $active : '' ) ?>><a href="<?php echo $deepPath.$packages["options"]["parent"].$packages["options"]["path"] ?>"><?php echo $packages["options"]["title"] ?></a></li>
                <li<?php echo ($packages["users"]["name"] == $package ? $active : '' ) ?>><a href="<?php echo $deepPath.$packages["users"]["parent"].$packages["users"]["path"] ?>"><?php echo $packages["users"]["title"] ?></a></li>
                <?php } ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li<?php echo ($packages["profile"]["name"] == $package ? $active : '' ) ?>><a href="<?php echo $deepPath.$packages["profile"]["parent"].$packages["profile"]["path"] ?>"><span class="glyphicon glyphicon-user"></span> <?php echo $User["username"] ?></a></li>
                <li><a href="<?php echo $deepPath?>logout/">Έξοδος</a></li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
